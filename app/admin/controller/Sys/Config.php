<?php
namespace app\admin\controller\Sys;

class Config
{

	public static function itemList(){
		$list = [
			[
				'name'=>'性别',
				'item'=>[
					['label'=>'男','value'=>'1','label_color'=>'primary'],
					['label'=>'女','value'=>'2','label_color'=>'warning'],
				]
			],
			[
				'name'=>'状态',
				'item'=>[
					['label'=>'正常','value'=>'1','label_color'=>'primary'],
					['label'=>'禁用','value'=>'0','label_color'=>'danger'],
				]
			],
			[
				'name'=>'开关',
				'item'=>[
					['label'=>'开启','value'=>'1','label_color'=>'primary'],
					['label'=>'关闭','value'=>'0','label_color'=>'danger'],
				]
			],
            [
                'name'=>'爱好',
                'item'=>[
                    ['label'=>'美食','value'=>'1','label_color'=>'primary'],
                    ['label'=>'摄影','value'=>'2','label_color'=>'info'],
                    ['label'=>'运动','value'=>'3','label_color'=>'warning'],
                    ['label'=>'阅读','value'=>'4','label_color'=>'danger'],
                    ['label'=>'音乐','value'=>'5','label_color'=>'success'],
                ]
            ],
		];

		return $list;
	}

	//字段验证规则列表
	public static function ruleList(){
		$list = [
			'邮箱'	=> '/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/',
			'网址'	=> '/^((ht|f)tps?):\/\/([\w\-]+(\.[\w\-]+)*\/)*[\w\-]+(\.[\w\-]+)*\/?(\?([\w\-\.,@?^=%&:\/~\+#]*)+)?/',
			'货币'	=> '/(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/',
			'数字'	=> '/^[0-9]*$/',
			'手机号'=> '/^1[3456789]\d{9}$/',
			'身份证'=> '/^[1-9]\d{5}(18|19|20|(3\d))\d{2}((0[1-9])|(1[0-2]))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/',
		];
		return $list;
	}


	//菜单页面显示结构
	public static function page_type_list(){
		$list = [
            ['name'=>'综合表格','type'=>1],
            ['name'=>'表单页面','type'=>2],
            ['name'=>'快捷表格','type'=>3],
        ];
		return $list;
	}


	//内置的短信平台
	public static function sms_list(){
		$list = [
			['name'=>'阿里','type'=>'Ali'],
			['name'=>'聚合','type'=>'Juhe'],
			['name'=>'极速','type'=>'Jisu'],
            ['name'=>'创瑞','type'=>'Cryun'],
		];
		return $list;
	}


	//默认创建的字段
	public static function defaultFields(){
		$list = [

			[
                // 'field'          =>  'xxx_id', 主键字段不设置field属性
			    'title'             =>  '编号',
                'type'              =>  1,
                'datatype'          =>  'int',
                'length'            =>  11,
                'list_show'         =>  1,
                'create_table_field'=>  1,
                'sortid'            =>  1,
                'primary'           =>  true,           // 是否创建默认字段的标记
                'width'             =>  70,
                'note'              =>  '不建议修改其属性',
            ],
            [
                'field'             =>  'sort_id',      // 非主键列，必须设置字段名
                'title'             =>  '排序',         // 字段标题
                'type'              =>  29,            // 排序类型
                'datatype'          =>  'int',         // 数据类型
                'length'            =>  11,            // 字段长度
                'list_show'         =>  1,             // 0不显示，1隐藏，2居中，3居左，4居右
                'search_type'       =>  0,             // 0不搜索，1精确匹配，2模糊匹配
                'post_status'       =>  1,             // 录入
                'create_table_field'=>  1,             // 创建表字段
                'sortid'            =>  99995,          // 排序序号
                'primary'           =>  true,          // 是否创建默认字段的标记
                'width'             =>  70,            // 列宽
                'indexdata'         =>  'index',        // 创建普通索引
                'note'              =>  '不建议修改其属性',
            ],
            [
                'field'             =>  'status',      // 非主键列，必须设置字段名
                'title'             =>  '状态',         // 字段标题
                'type'              =>  6,             // 开关
                'datatype'          =>  'tinyint',     // 数据类型
                'length'            =>  4,             // 字段长度
                'list_show'         =>  1,             // 0不显示，1隐藏，2居中，3居左，4居右
                'search_type'       =>  1,             // 0不搜索，1精确匹配，2模糊匹配
                'post_status'       =>  1,             // 录入
                'create_table_field'=>  1,             // 创建表字段
                'sortid'            =>  99996,          // 排序序号
                'primary'           =>  true,          // 是否创建默认字段的标记
                'width'             =>  70,            // 列宽
                'item_config'       => '[{"label":"开启","value":"1","label_color":"primary"},{"label":"关闭","value":"0","label_color":"danger"}]',
                'indexdata'         =>  'index',        // 创建普通索引
                'note'              =>  '不建议修改其属性',
            ],
            [
                'field'             =>  'creater_id',  // 非主键列，必须设置字段名
                'title'             =>  '所有者',       // 字段标题
                'type'              =>  1,             // 排序类型
                'datatype'          =>  'int',         // 数据类型
                'length'            =>  11,            // 字段长度
                'list_show'         =>  0,             // 0不显示，1隐藏，2居中，3居左，4居右
                'search_type'       =>  0,             // 0不搜索，1精确匹配，2模糊匹配
                'post_status'       =>  1,             // 不录入
                'create_table_field'=>  1,             // 创建表字段
                'sortid'            =>  99997,          // 排序序号
                'primary'           =>  true,          // 是否创建默认字段的标记
                'width'             =>  70,            // 列宽
                'indexdata'         =>  'index',        // 创建普通索引
                'note'              =>  '自动生成无录入表单',
            ],
            [
                'field'             =>  'create_time',  // 非主键列，必须设置字段名
                'title'             =>  '创建时间',      // 字段标题
                'type'              =>  11,             // 日期时间（后端录入）
                'datatype'          =>  'int',          // 数据类型
                'length'            =>  11,             // 字段长度
                'list_show'         =>  1,              // 0不显示，1隐藏，2居中，3居左，4居右
                'search_type'       =>  0,              // 0不搜索，1精确匹配，2模糊匹配
                'post_status'       =>  1,              // 录入
                'create_table_field'=>  1,              // 创建表字段
                'sortid'            =>  99998,            // 排序序号
                'datetime_config'   =>  'datetime',     // 年月日时分秒
                'primary'           =>  true,           // 是否创建默认字段的标记
                'width'             =>  200,            // 列宽
                'indexdata'         =>  'index',        // 创建普通索引
                'note'              =>  '不建议修改其属性',
            ],
            [
                'field'             =>  'update_time',  // 非主键列，必须设置字段名
                'title'             =>  '更新时间',      // 字段标题
                'type'              =>  12,             // 日期时间（后端录入）
                'datatype'          =>  'int',          // 数据类型
                'length'            =>  11,             // 字段长度
                'list_show'         =>  1,              // 0不显示，1隐藏，2居中，3居左，4居右
                'search_type'       =>  0,              // 0不搜索，1精确匹配，2模糊匹配
                'post_status'       =>  1,              // 录入
                'create_table_field'=>  1,              // 创建表字段
                'sortid'            =>  99999,            // 排序序号
                'datetime_config'   =>  'datetime',     // 年月日时分秒
                'primary'           =>  true,           // 是否创建默认字段的标记
                'width'             =>  200,            // 列宽
                'indexdata'         =>  'index',        // 创建普通索引
                'note'              =>  '不建议修改其属性',
            ],
		];

		return $list;
	}



    //字段列表
    public static function fieldList(){
        // 之所以必须提供字段field表达式，主要是为了不包含id列
        $list = \think\facade\Db::name('sys_field_type')
            ->field('name,type,property,item,search')
            ->where(['status'=>1])
            ->select()
            ->toArray();
        foreach ($list as $k => $v){
            $list[$k]['item'] = $v['item'] == 1 ? true : false;
            $list[$k]['search'] = $v['search'] == 1 ? true : false;
        }
        return $list;
    }

    /*
    name 方法名
    type 方法类型
    dialog 是否弹窗
    button 是否按钮
    view  是否生成视图文件
    */
    public static function actionList(){
        // 之所以必须提供字段field表达式，主要是为了不包含id列
        $list = \think\facade\Db::name('sys_action_type')
            ->field('`name`,`type`,`dialog`,`button`,`icon`,`button_color`,`view`,`action_name`,`pagesize`,`group_button_status`,`list_button_status`,`sortid`,`is_editable_table`,`default_create`,`dialog_size`,`show_admin`,`show_api`')
            ->where(['status'=>1])
            ->select()
            ->toArray();
        foreach ($list as $k => $v){
            $list[$k]['dialog']                 = $v['dialog'] == 1 ? true : false;
            $list[$k]['button']                 = $v['button'] == 1 ? true : false;
            $list[$k]['view']                   = $v['view'] == 1 ? true : false;
            $list[$k]['group_button_status']    = $v['group_button_status'] == 1 ? true : false;
            $list[$k]['list_button_status']     = $v['list_button_status'] == 1 ? true : false;
            $list[$k]['default_create']         = $v['default_create'] == 1 ? true : false;
            $list[$k]['show_admin']             = $v['show_admin'] == 1 ? true : false;
            $list[$k]['show_api']               = $v['show_api'] == 1 ? true : false;
            $list[$k]['default_create']         = $v['default_create'] == 1 ? true : false;
            $list[$k]['is_editable_table']      = $v['is_editable_table'] == 1 ? true : false;
        }
        return $list;
    }


    //字段的数据结构
    public static function propertyField(){
        // 之所以必须提供字段field表达式，主要是为了不包含id列
        $list = \think\facade\Db::name('sys_field_property')
            ->field('`type`,`name`,`maxlen`,`decimal`')
            ->select()
            ->toArray();
        return $list;
    }



}
