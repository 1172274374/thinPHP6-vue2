<?php
namespace app\admin\controller;
use think\Console;
use think\exception\ValidateException;
use think\facade\Db;

//仪表盘

class Home extends Admin
{


    //首页动态数据
    function index(){
        // 查询首页配置：是否显示统计数据，是否显示菜单启动按钮，是否显示图表
        $data['config'] = $this->getShowConfig();
        // 首页顶部统计数据
        $data['statisic'] = $this->getStatisic();
        // 首页菜单启动项
        $data['menus'] = $this->getMenuLink();
        // 首页图表
        $data['chart'] = $this->getChart();

        $data['status'] = 200;

        return json($data);

    }

    /**
     * 图表数据
     * @return array
     * [
     *      'name'   => '产品销售数据',
     *      'type'    => 1, // 1： 柱状图； 2：折线图
     *      'source_data' => [
     *          ['product', '2015', '2016', '2017'], // 第一行为维度数据
     *          ['Matcha Latte', 43.3, 85.8, 93.7],
     *      ]
     */
    function getChart(){
        try{
            if(config('admin_config.show_chart') == 1){
                $chartData = \app\admin\model\Admin\Chart::where(['status'=>1])->select()->toArray();
                foreach ($chartData as $k => $v){
                    // 维度转换为数组
                    $chartData[$k]['dimensions'] = explode(',',strtoupper($v['dimensions']));
                    // 基础数据源的时候，需要把cvs转换为二维数组
                    if($v['source'] == 1){
                        $temp = str_replace(array("\r\n", "\r", "\n"), '`', $v['source_data']);
                        $lineArray = explode('`',$temp);
                        foreach ($lineArray as $_k => $_v){
                            $lineArray[$_k] = explode(',',$_v);
                        }
                        $chartData[$k]['source_data'] = $lineArray;
                    }
                    if($v['source'] == 2 && !empty($v['source_sql'])){
                        $sqlData = \think\facade\Db::query(strtolower($v['source_sql']));
                        $sqlArray = [];
                        foreach ($sqlData as $_k => $_v){
                            $sqlArray[$_k] = array_values($_v);
                        }
                        $chartData[$k]['source_data'] = $sqlArray;
                    }
                    // 将维度添加到数据的最前面
                    array_unshift($chartData[$k]['source_data'],$chartData[$k]['dimensions']);
                }
                // 返回需要的格式化的数据
                return $chartData;
            }
        } catch (\Exception $e){
            throw new ValidateException($e->getMessage());
        }
    }

    /**
     * 查询首页统计数据
     * @return array
     * ['unit_color' => '#4AB7BD','unit'=>'日','current_name' =>  '当日订单（平方米）','unit'=>'日','current_value'=>67,'total_name'=>'累计订单（平方米）','total_value'=>678],
     */
    function getStatisic(){
        try{
            if(config('admin_config.show_statisic') == 1){
                $statisicList = \app\admin\model\Admin\Statisic::where(['status' => 1])->select()->toArray();
                foreach ($statisicList as $k => $v){
                    if($v['current_type'] == 2 && !empty($v['current_sql'])){
                        $currentData = \think\facade\Db::query(strtolower($v['current_sql']));
                        $statisicList[$k]['current_value'] = $currentData[0]['value'];
                    }
                    if($v['total_type'] == 2 && !empty($v['total_sql'])){
                        $totalData = \think\facade\Db::query(strtolower($v['total_sql']));
                        $statisicList[$k]['total_value'] = $totalData[0]['value'];
                    }
                }
                return $statisicList;
            }
        } catch (\Exception $e){
            throw new ValidateException($e->getMessage());
        }
    }

    // 查询首页显示控制的值
    function getShowConfig(){
        return [
            'show_statisic'   => config('admin_config.show_statisic'),
            'show_menu'         => config('admin_config.show_menu'),
            'show_chart'        => config('admin_config.show_chart'),
        ];
    }

    //头部消息通知
    function getNotice(){
        $data = [
            ['num'=>5,'title'=>'条日志需要查看','url'=>(string)url('admin/Admin.Log/index'),],
            ['num'=>12,'title'=>'个用户需要维护信息','url'=>(string)url('admin/Admin.User/index'),],
        ];
        return json(['status'=>200,'data'=>$data]);
    }

    //获取首页快捷导航
    private function getMenuLink(){
        try {
            if(config('admin_config.show_menu') == 1){
                $data = Db::name('sys_menu')
                    ->field('title,menu_pic,controller_name')
                    ->where('home_show',1)
                    ->order('home_sort asc')
                    ->select()
                    ->toArray();
                foreach($data as $k=>$v){
                    $data[$k]['url'] = (string)url('admin/'.str_replace('/','.',$v['controller_name']).'/index');
                }
                return $data;
            }
        } catch (\Exception $e){
            throw new ValidateException($e->getMessage());
        }
    }


}
