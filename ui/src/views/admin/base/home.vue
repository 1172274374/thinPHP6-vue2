<template>
  <div class="home">
    <el-card shadow="never">
      <!-- 存在统计或者启动按钮 -->
      <div v-if="config.show_chart == 1 || config.show_statisic == 1 || config.show_menu == 1">
        <el-row :gutter="10">
          <!-- 顶部统计 -->
          <el-col :span="24" style="margin-bottom: 10px" v-if="config.show_statisic == 1">
            <el-card shadow="always">
              <div class="chart" style="height: 100%;">
                <DataCard :data="statisic"></DataCard>
              </div>
            </el-card>
          </el-col>
          <!-- 快捷菜单 -->
          <el-col :span="24" style="margin-bottom: 10px"  v-if="config.show_menu == 1">
            <el-card shadow="always">
              <el-row :gutter="15" v-if="menus" style="margin-top: 5px; text-align:center">
                <el-col :xs="24" :sm="12" :md="6" :lg="3" :xl="3" v-for="(item,index) in menus" :key="index">
                  <router-link :to="item.url">
                    <el-card shadow="hover">
                      <el-image :src="item.menu_pic" style="width: 32px; height: 32px"></el-image>
                      <div class="icon_title">{{ item.title }}</div>
                    </el-card>
                  </router-link>
                </el-col>
              </el-row>
            </el-card>
          </el-col>
          <!-- 统计图表 -->
          <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" style="margin-bottom: 10px"  v-if="config.show_chart == 1"  v-for="(item,index) in chart" :key="index">
            <el-card>
              <div class="chart">
                <LineChart :id="'chart' + index" :data="item"></LineChart>
              </div>
            </el-card>
          </el-col>
          <!-- 项目说明 -->
          <el-col :span="24" style="margin-bottom: 10px">
            <el-card shadow="always">
              <el-row :gutter="15" style="margin-top: 5px; text-align:center">
                <el-col :xs="24" :sm="12" :md="4" :lg="4" :xl="4">
                  <el-card shadow="never">
                    <div slot="header" class="clearfix">
                      <span>技术讨论群</span>
                    </div>
                    <div class="info_item">
                      <el-image fit="contain" src="https://rds-share.oss-cn-hangzhou.aliyuncs.com/admin/202206/202206211643150157962.png"></el-image>
                    </div>
                  </el-card>
                </el-col>
                <el-col :xs="24" :sm="12" :md="4" :lg="4" :xl="4">
                  <el-card shadow="never">
                    <div slot="header" class="clearfix">
                      <span>联系作者</span>
                    </div>
                    <div class="info_item">
                      <el-image fit="contain" src="https://rds-share.oss-cn-hangzhou.aliyuncs.com/admin/202206/202206211633500118510.png"></el-image>
                    </div>
                  </el-card>
                </el-col>
                <el-col :xs="24" :sm="12" :md="4" :lg="4" :xl="4">
                  <el-card shadow="never">
                    <div slot="header" class="clearfix">
                      <span>文档及教程</span>
                    </div>
                    <div class="info_item">
                      <a href="https://www.yuque.com/rapid_dev_system" target="_blank">
                        <el-image fit="contain" src="https://rds-share.oss-cn-hangzhou.aliyuncs.com/admin/202206/rdscode.cn.png"></el-image>
                      </a>
                    </div>
                  </el-card>
                </el-col>
                <el-col :xs="24" :sm="12" :md="4" :lg="4" :xl="4">
                  <el-card shadow="never">
                    <div slot="header" class="clearfix">
                      <span>功能演示站</span>
                    </div>
                    <div class="info_item">
                      <a href="http://demo.raiseinfo.cn" target="_blank">
                        <el-image fit="contain" src="https://rds-share.oss-cn-hangzhou.aliyuncs.com/admin/202206/202206211724300159324.png"></el-image>
                      </a>
                    </div>
                  </el-card>
                </el-col>
                <el-col :xs="24" :sm="12" :md="4" :lg="4" :xl="4">
                  <el-card shadow="never">
                    <div slot="header" class="clearfix">
                      <span>微信支付</span>
                    </div>
                    <div class="info_item">
                      <el-image fit="contain" src="https://rds-share.oss-cn-hangzhou.aliyuncs.com/admin/202206/202206211702210153075.png"></el-image>
                    </div>
                  </el-card>
                </el-col>
                <el-col :xs="24" :sm="12" :md="4" :lg="4" :xl="4">
                  <el-card shadow="never">
                    <div slot="header" class="clearfix">
                      <span>支付宝支付</span>
                    </div>
                    <div class="info_item">
                      <el-image fit="contain" src="https://rds-share.oss-cn-hangzhou.aliyuncs.com/admin/202206/202206211702240167270.png"></el-image>
                    </div>
                  </el-card>
                </el-col>
              </el-row>
            </el-card>
          </el-col>
        </el-row>
        <!-- 底部设置按钮 -->
        <div>
          <div v-if="config.show_chart == 1">
            <el-button
                style="float: right; padding: 10px 5px 0px 0px"
                type="text"
                v-if="checkPermission('/Admin/Chart/add.html') || checkPermission('/Admin/Chart/update.html') || checkPermission('/Admin/Chart/delete.html')"
                @click="setChart">图表设置</el-button>
          </div>
          <div v-if="config.show_statisic == 1">
            <el-button
                style="float: right; padding: 10px 5px 0px 0px"
                type="text"
                v-if="checkPermission('/Admin/Statisic/add.html') || checkPermission('/Admin/Statisic/update.html') || checkPermission('/Admin/Statisic/delete.html')"
                @click="setStatisic">统计设置</el-button>
          </div>
        </div>
      </div>
      <!-- 不存在统计或者启动按钮 -->
      <div v-else style="height: 80vh">
        <el-card>
          <div slot="header">
            <span style="font-weight: bold">RDS快速开发系统</span>
          </div>
          <div style="margin-bottom: 18px;">
            <i class="el-icon-success"></i>
            <span style="margin-left: 10px;">代码生成: 一键生成前后端代码,结构简单,排版工整;</span>
          </div>
          <div style="margin-bottom: 18px;">
            <i class="el-icon-success"></i>
            <span style="margin-left: 10px;">表单组件: 内置常见表单组件,开箱即用;</span>
          </div>
          <div style="margin-bottom: 18px;">
            <i class="el-icon-success"></i>
            <span style="margin-left: 10px;">内置方法: 内置常用方法,基本功能无需任何修改即可使用,并且可定制;</span>
          </div>
          <div style="margin-bottom: 18px;">
            <i class="el-icon-success"></i>
            <span style="margin-left: 10px;">复杂开发: 支持一对多,多对一基本操作,可解决大部分业务需求;</span>
          </div>
          <div style="margin-bottom: 18px;">
            <i class="el-icon-success"></i>
            <span style="margin-left: 10px;">强大的数据表: 支持高性能虚拟滚动,几千行数据处理性能优秀;</span>
          </div>
          <div style="margin-bottom: 18px;">
            <i class="el-icon-success"></i>
            <span style="margin-left: 10px;">一键API: 支持前端API应用开发,可从后端功能复制到前端,生成API接口;</span>
          </div>
          <div style="margin-bottom: 18px;">
            <i class="el-icon-success"></i>
            <span style="margin-left: 10px;">支持数据权限: 支持基于部门的数据权限和数据隔离;</span>
          </div>
          <div style="margin-bottom: 18px;">
            <i class="el-icon-success"></i>
            <span style="margin-left: 10px;">易于使用: 完备的文档支持和在线技术支持,丰富的表单解释信息,拿来即可上手;</span>
          </div>
          <div style="margin-bottom: 18px;">
            <i class="el-icon-success"></i>
            <span style="margin-left: 10px;">首页可配置: 统计和图表通过设置即可生成,支持固定数据设置和SQL查询设置;</span>
          </div>
        </el-card>
      </div>
      <!-- 底部链接 -->
      <div style="display: flex;width: 100%;justify-content: center;">
        <el-link type="info" href="http://doc.rdscode.cn" target="_blank">RDS业务快速开发系统(文档)</el-link>
      </div>
    </el-card>

    <!--统计设置-->
    <SetStatisic :info="setStatisicInfo" :show.sync="dialog.setStatisicDialogStatus" :size="formSize" refesh_list="homeData"></SetStatisic>

    <!--设置图表-->
    <SetChart :info="setChartInfo" :show.sync="dialog.setChartDialogStatus" :size="formSize" refesh_list="homeData"></SetChart>
  </div>
</template>
<script>
import {homeData} from '@/api/admin/base'
import LineChart from "@/components/echart/LineBarChart";
import DataCard from "@/components/echart/DataCard";
import SetStatisic from '@/views/admin/base/setStatisic.vue'
import SetChart from '@/views/admin/base/setChart.vue'
import {mapGetters} from "vuex";
export default {
  components: { DataCard, LineChart, SetStatisic, SetChart },
  computed: {
    ...mapGetters(['tagsView', 'formSize', 'inputWidth', 'labelWidth', 'tableHeight', 'rowHeight']),
  },
  data() {
    return {
      config: {
        show_chart: 1,
        show_menu: 1,
        show_statisic: 1,
      },
      statisic: [],
      menus: [],
      chart: [],
      dialog: {
        setStatisicDialogStatus : false,
        setChartDialogStatus : false,
      },
      setStatisicInfo:{},
      setChartInfo:{},
    }
  },
  methods: {
    homeData() {
      homeData().then(res => {
        console.log(res)
        if (res.status == 200) {
          this.config = res.config
          this.statisic = res.statisic
          this.menus = res.menus
          this.chart = res.chart
        }
      })
    },
    setStatisic(row){
      this.dialog.setStatisicDialogStatus = true
    },
    setChart(row){
      this.dialog.setChartDialogStatus = true
    }
  },
  beforeMount() {
    this.homeData()
  },
  mounted() {
  }
};
</script>
<style scoped>
.home {
  overflow-x:hidden;
  overflow-y:auto;
  width: 99%;
  height: 96%;
  padding-right: 15px;
  margin-bottom: 10px;
}

.chart {
  overflow-x:hidden;
  overflow-y:hidden;
  width: 100%;
  height: 300px;
}
.info_item {
  display: flex;
  justify-content: center;
}
</style>
