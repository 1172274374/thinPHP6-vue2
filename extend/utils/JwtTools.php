<?php


namespace utils;

use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use think\exception\ValidateException;

class JwtTools
{

    protected $issuedBy;
    protected $permittedFor;
    protected $issuedAt;
    protected $expiresAtAccess;
    protected $secrect;

    public function __construct($config)
    {
        $this->issuedBy             = $config['jwt_issued_by'];
        $this->permittedFor         = $config['jwt_permitted_for'];
        $this->secrect              = $config['jwt_secrect'];
        $this->issuedAt             = new \DateTimeImmutable();
        $this->expiresAtAccess      = $this->issuedAt->modify($config['jwt_expires']);
    }

    /**
     * 生成Jwt配置对象
     * @return Configuration
     */
    private function createJwt()
    {
        return Configuration::forSymmetricSigner(new Sha256(), InMemory::base64Encoded($this->secrect));
    }


    public function getToken($bind)
    {
        $config     = $this->createJwt();
        $builder    = $config->builder();
        $builder->withClaim('payload', json_encode($bind));
        $token = $builder
            ->issuedBy($this->issuedBy)
            ->permittedFor($this->permittedFor)
            ->issuedAt($this->issuedAt)
            ->canOnlyBeUsedAfter($this->issuedAt->modify('+1 second'))
            ->expiresAt($this->expiresAtAccess)
            ->getToken($config->signer(), $config->signingKey());
        sleep(1);
        return $token->toString();

    }

    /**
     * 校验Token
     * @param $token
     * @return bool
     */
    public function verify($token)
    {
        $config = $this->createJwt();
        try {
            $token = $config->parser()->parse($token);
            assert($token instanceof UnencryptedToken);
        } catch (\Exception $e) {
            \think\facade\Log::error('令牌解析失败[1]：' . $e->getMessage());
            return ['status' => 1, 'msg' => '令牌解析错误'];
        }

        // 验证签发端是否匹配
        $validate_issued = new IssuedBy($this->issuedBy);
        $config->setValidationConstraints($validate_issued);
        $constraints = $config->validationConstraints();
        try {
            $config->validator()->assert($token, ...$constraints);
        } catch (RequiredConstraintsViolated $e) {
            \think\facade\Log::error('令牌验证失败[2]：' . $e->getMessage());
            return ['status' => 2, 'msg' => '签发错误'];
        }

        //验证客户端是否匹配
        $validate_permitted_for = new PermittedFor($this->permittedFor);
        $config->setValidationConstraints($validate_permitted_for);
        $constraints = $config->validationConstraints();
        try {
            $config->validator()->assert($token, ...$constraints);
        } catch (RequiredConstraintsViolated $e) {
            \think\facade\Log::error('令牌验证失败[3]：' . $e->getMessage());
            return ['status' => 3, 'msg' => '客户端错误'];
        }

        // 验证是否过期
        $timezone = new \DateTimeZone('Asia/Shanghai');
        $time = new SystemClock($timezone);
        $validate_exp = new StrictValidAt($time);
        $config->setValidationConstraints($validate_exp);
        $constraints = $config->validationConstraints();
        try {
            $config->validator()->assert($token, ...$constraints);
        } catch (RequiredConstraintsViolated $e) {
            \think\facade\Log::error('令牌验证失败[4]：' . $e->getMessage());
            return ['status' => 4, 'msg' => '登录状态已过期，请重新登录'];
        }

        // 验证令牌是否已使用预期的签名者和密钥签名
        $validate_signed = new SignedWith(new Sha256(), InMemory::base64Encoded($this->secrect));
        $config->setValidationConstraints($validate_signed);
        $constraints = $config->validationConstraints();
        try {
            $config->validator()->assert($token, ...$constraints);
        } catch (RequiredConstraintsViolated $e) {
            \think\facade\Log::error('令牌验证失败[5]：' . $e->getMessage());
            return ['status' => 5, 'msg' => '签名错误'];
        }

        return ['status' => 0, 'msg' => '验证通过'];
    }


    /**
     * 获取token的载体内容
     * @param $token
     * @return mixed
     */
    public function getTokenContent($token)
    {
        $config = $this->createJwt();
        try {
            $decode_token = $config->parser()->parse($token);
            $claims = json_decode(base64_decode($decode_token->claims()->toString()), true);
        } catch (\Exception $e) {
            throw new ValidateException($e->getMessage());
        }
        return $claims;
    }

}
