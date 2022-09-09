<template>
  <div class="body-bg">

    <el-card class="login-main" shadow="always">
      <div slot="header">
        <h2 class="site-title">{{ site_title }}</h2>
      </div>
      <div class="login-body">
            <el-form ref="login" :model="login" size="medium" :rules="loginRules" class="login-form" label-position="left">
              <el-form-item prop="username">
                <el-input
                    v-model="login.username"
                    type="text"
                    placeholder="用户名"
                    prefix-icon="el-icon-user"
                    autocomplete="off"
                    clearable/>
              </el-form-item>
              <el-form-item prop="password">
                <el-input
                    v-model="login.password"
                    type="text"
                    placeholder="密码"
                    prefix-icon="el-icon-lock"
                    @keyup.enter.native="handleLogin"
                    autocomplete="off"
                    clearable
                    show-password/>
              </el-form-item>
              <el-form-item prop="verify" v-if="verify_status">
                <el-col :span="13">
                  <el-input
                      v-model="login.verify"
                      type="text"
                      placeholder="验证码"
                      prefix-icon="el-icon-picture"
                      @keyup.enter.native="handleLogin"
                      autocomplete="off"
                      clearable/>
                </el-col>
                <el-col :span="11">
                  <el-image
                      :src="verifySrc"
                      alt="验证码"
                      title="点击刷新验证码"
                      @click="refeshVerifySrc"
                      style="width:108px;height:35px; margin-top:3px;float:right"/>
                </el-col>
              </el-form-item>
            </el-form>
            <el-checkbox v-if="remberMe" v-model="login.rememberMe" style="margin:0px 0px 25px 0px;">记住密码</el-checkbox>
            <el-button
                :loading="loading"
                size="small"
                type="primary"
                style="width:100%;"
                @click.native.prevent="handleLogin">
              <span v-if="!loading">登 录</span>
              <span v-else>登 录 中...</span>
            </el-button>
          </div>
      <div class="copyright">{{copyright}}</div>
    </el-card>
  </div>
</template>

<script>
import {captch} from '@/api/admin/base'
import {encrypt, decrypt} from '@/utils/jsencrypt'

export default {
  data() {
    return {
      loading: false,
      login: {
        username: '',
        password: '',
      },
      loginRules: {
        username: [{required: true, message: '请输入用户名', trigger: 'blur'}],
        password: [{required: true, message: '请输入密码', trigger: 'blur'}],
        verify: [{required: true, message: '请输入验证码', trigger: 'blur'}]
      },
      verifySrc: '',
      verify_status: true,
      remberMe: true,
      success_url: '',
      site_title: '',
      copyright: '',
    }
  },
  created() {
    this.captch()
    this.getCookie()
  },
  methods: {
    captch() {
      captch().then(res => {
        if (res.status == 200) {
          this.verifySrc = res.data.img
          this.login.key = res.data.key
          this.remberMe = res.rememberMe
          this.success_url = res.success_url
          this.site_title = res.site_title
          this.copyright = res.copyright
          this.verify_status = res.verify_status
        } else {
          this.$message.error('验证码获取失败!')
        }
      })
    },
    refeshVerifySrc() {
      this.captch()
    },
    getCookie() {
      const username = this.$cookies.get("username")
      const password = this.$cookies.get("password")
      const rememberMe = this.$cookies.get('rememberMe')

      this.login = {
        username: username === null ? this.login.username : username,
        password: password === null ? this.login.password : decrypt(password),
        rememberMe: rememberMe === null ? false : Boolean(rememberMe)
      }
    },
    handleLogin() {
      this.$refs['login'].validate(valid => {
        if (valid) {
          console.log('表单验证成功')
          this.loading = true
          if (this.login.rememberMe) {
            this.$cookies.set("username", this.login.username, {expires: 24 * 3600 * 15})
            this.$cookies.set("password", encrypt(this.login.password), {expires: 24 * 3600 * 15})
            this.$cookies.set('rememberMe', this.login.rememberMe, {expires: 24 * 3600 * 15})
          } else {
            this.$cookies.remove("username")
            this.$cookies.remove("password")
            this.$cookies.remove('rememberMe');
          }

          this.$store.dispatch('login', this.login).then(res => {
            this.$router.push(this.success_url)
            this.loading = false
          }).catch(err => {
            this.loading = false
            this.captch()
          })
        }
      })
    }
  },
}
</script>

<style lang="scss">
.body-bg {
  position: absolute;
  height: 100%;
  width: 100%;
  top: 0;
  left: 0;

  padding: 0;
  margin: 0;

  overflow-y: auto;
  // background:url(../../../assets/images/denglu.jpg) top center;
  background: url(../../../assets/images/bg.gif) fixed repeat;
  align-items: center;
}

.login-main {
  z-index: 1;
  border-radius: 10px;
  border: none;
  width: 90%;
  max-width:400px;
  min-width: 300px;
  margin: 150px auto;
  background: #FFF;
  .el-card__header {
    background: #287c98;
  }
}

.login-body {
  padding: 10px;
}

.site-title {
  font-weight: bold;
  font-size: 28px;
  text-align: center;
  color: #FFF;
}

.copyright {
  display: flex;
  justify-content: center;
  padding: 20px;
  font-size: 0.8em;
  word-wrap: break-word;
  text-align: center;
  line-height: 1.2em;
}
</style>
