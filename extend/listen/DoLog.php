<?php

//操作日志写入数据库

namespace listen;
use app\admin\model\Admin\Log as LogModel;

class DoLog{

    public function handle($user){
        // 默认只记录增加，修改，删除操作
        if(in_array(request()->action(),['add','update','delete'])){
			$content = request()->except(['s', '_pjax']);
			if ($content) {
				foreach ($content as $k => $v) {
					if (is_string($v) && strlen($v) > 200 || stripos($k, 'password') !== false) {
						unset($content[$k]);
					}
				}
			}

			$data['application_name'] = app('http')->getName();
			$data['username'] = $user;
			$data['url'] = request()->url(true);
			$data['ip'] = request()->ip();
			$data['useragent'] = request()->server('HTTP_USER_AGENT');
			$data['content'] = json_encode($content,JSON_UNESCAPED_UNICODE);
			$data['create_time'] = time();
			$data['type'] = 2;

			LogModel::create($data);
		}

    }
}
