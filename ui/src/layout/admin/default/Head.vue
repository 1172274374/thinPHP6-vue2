<template>
  <div class="navbar">
    <div>
        <div class="left-menu" ref="left_menu">

          <i @click="toggleSideBar"
             :class="isCollapse?'el-icon-s-fold':'el-icon-s-unfold'"
             style="color: #fff;"></i>

          <i @click="reload()"
             class="el-icon-refresh hidden-sm-and-down"
             style="color: #fff;"></i>

          <div class="breadcrumb hidden-sm-and-down">
            <Breadcrumb></Breadcrumb>
          </div>

        </div>


        <div class="right-menu" ref="right_menu">

          <!-- 【顶部父菜单】顶部显示具有子菜单的所有父菜单。点击侧栏显示此菜单的子菜单 -->
          <div class="top-menus">
            <div class="menu-wrap hidden-md-and-down">
              <a v-for="(item,index) in menu"
                 v-if="checkPermission(item.path) && (item.children.length > 0 || item.menu_id == 3)"
                 v-bind:style="{backgroundColor: index == currentMenu ? headerActiveBackgroundColor : ''}"
                 @click="setCurrentMenu(index)"
                 style="cursor: pointer">
                <div class="top_menu">
                  <span class="top_menu_title" v-if="item.menu_id == 3">全部菜单</span>
                  <span class="top_menu_title" v-else>{{item.title}}</span>
                </div>
              </a>
            </div>
          </div>

          <!-- 【顶部快捷菜单】顶部显示需要在首页显示的菜单 -->
          <!-- div class="top-menus">
            <div class="menu-wrap hidden-md-and-down">
              <router-link
                  v-for="(item,index) in menus"
                  :key="index"
                  :to="item.url"
                  active-class="active">
                <div class="top_menu">
                  <span class="top_menu_title">{{item.title}}</span>
                </div>
              </router-link>
            </div>
          </div -->


          <el-tooltip content="清除缓存" effect="dark" placement="bottom">
            <div class="right-menu-item" @click="clearCache()">
              <i class="icontool el-icon-delete"></i>
            </div>
          </el-tooltip>

          <el-tooltip :content="!fullscreen?'全屏' : '取消全屏'" effect="dark" placement="bottom">
            <div class="right-menu-item hidden-sm-and-down" @click="change_full_screen()">
              <i :class="!fullscreen ? 'icontool el-icon-alizoom' : 'icontool el-icon-alizoomout'"></i>
            </div>
          </el-tooltip>

          <div class="right-menu-item" v-if="show_notice">
            <el-badge is-dot>
              <el-dropdown placement="bottom-start"  @click.native="getNotice" trigger="click">
                <i class="icontool el-icon-bell" style="font-size: 140%;"></i>
                <el-dropdown-menu slot="dropdown">
                      <div style="width:250px; height:200px; padding:10px 0; text-indent:10px;">
                          <ul>
                            <li v-for="(item,i) in notice_list" :key="i">
                              <router-link :to="item.url">
                              <svg class="icon" aria-hidden="true">
                                <use xlink:href="#el-icon-alidaichuli"></use>
                              </svg>
                              您有<i>{{item.num}}</i>{{item.title}}
                              </router-link>
                            </li>
                          </ul>
                      </div>
                </el-dropdown-menu>
              </el-dropdown>
            </el-badge>
          </div>

          <el-avatar :src="headimg ? headimg : './static/images/head.jpg'"></el-avatar>
          <el-dropdown trigger="click" placement="bottom" style="cursor: pointer;margin-right:15px;">
            <span class="el-dropdown-link" style="color: #FFF;">
              {{user}}<i class="icontool el-icon-arrow-down" style="color: #FFF;padding-left: 5px;"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item  disabled style="text-align: center;">{{ name }}</el-dropdown-item>
              <el-dropdown-item icon="el-icon-lock" @click.native.prevent="updatePassword">修改密码</el-dropdown-item>
              <el-dropdown-item icon="el-icon-back" @click.native.prevent="logout">退出</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
          <div class="right-menu-item"  @click="showdrawer">
              <i class="icontool el-icon-setting"></i>
          </div>

        </div>
    </div>
    <Password :show.sync="passwordDialogStatus"  size="small"></Password>

    <Setting :show.sync="drawer"></Setting>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { clearCache ,getNotice,homeData} from '@/api/admin/base'
import Breadcrumb from "@/components/common/Breadcrumb"
import Password from "@/views/admin/base/password"
import Setting from "./Setting"

export default {
  name:'Head',
  components: {Breadcrumb,Password,Setting},
  computed: {
    ...mapGetters(['name','user','headimg','device','sidebar','show_notice','menu','currentMenu', 'headerActiveBackgroundColor']),
    isCollapse() {
      return !this.sidebar.opened
    }
  },
  data() {
    return {
      passwordDialogStatus : false,
      fullscreen: false,
      drawer: false,
      notice_list:[],
      menus:[],
    };
  },
  mounted() {
    // 顶部快捷菜单
    homeData().then(res => {
      if(res.status == 200){
        this.menus = res.menus
      }
    })
    this.getNotice();
  },
  methods: {
    // 设置当前菜单
    setCurrentMenu(index){
      this.$store.dispatch('currentMenu',index)
    },
    // 侧栏
    toggleSideBar() {
      this.$store.dispatch('toggleSideBar')
    },

    logout(){
      this.$MyUtils.confirm({content:'确定注销并且退出系统?'}).then(()=>{
          this.$store.dispatch('logout').then(()=>{
              this.$message({message: '退出成功', type: 'success'})
              location.href = '/'
          }).catch(() => {})
      })
    },
    clearCache(){
      this.$MyUtils.confirm({content:'确定清除缓存吗?'}).then(()=>{
          clearCache().then(res => {
              if(res.status == '200'){
                this.$message({message: '操作成功', type: 'success'})
              }
          })
      })
    },
    getNotice(){
      getNotice().then(res => {
         this.notice_list = res.data
      })
    },
    reload(){
      this.$router.go(0)
    },
    updatePassword(){
      this.passwordDialogStatus = true
    },
    showdrawer(){
      this.drawer = true
    },
    change_full_screen() {
      //全屏切换函数
      let element = document.documentElement;
      if (this.fullscreen) {
        if (document.exitFullscreen) {
          document.exitFullscreen();
        } else if (document.webkitCancelFullScreen) {
          document.webkitCancelFullScreen();
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
        } else if (document.msExitFullscreen) {
          document.msExitFullscreen();
        }
      } else {
        if (element.requestFullscreen) {
          element.requestFullscreen();
        } else if (element.webkitRequestFullScreen) {
          element.webkitRequestFullScreen();
        } else if (element.mozRequestFullScreen) {
          element.mozRequestFullScreen();
        } else if (element.msRequestFullscreen) {
          // IE11
          element.msRequestFullscreen();
        }
      }
      this.fullscreen = !this.fullscreen; //判断全屏状态
    },
  },
};
</script>

<style scoped lang="scss">
.el-dropdown-menu {
  top: 38px !important;
  li{
    line-height:30px;
    padding-bottom: 5px;
    padding-top: 5px; color:#515a6e;
    font-size:14px;
    font-weight: 400;
    border-bottom: 1px solid #EBEEF5;
    cursor: pointer;
    i{font-weight: bold; color: #000;}
    &:hover {
      background: rgb(242, 246, 252);
    }

  }
}

.icon {
  width: 1.2em;
  height: 1.2em;
  vertical-align: -0.25em;
  fill: currentColor;
  overflow: hidden;
}
.top-menus {
  .menu-wrap {
    display: flex;
    padding: 0px 1px;
    border-right: 1px solid #f4f4f4;
    height: 50px;
    line-height: 48px;
    .top_menu{
      border-left: 1px solid #f4f4f4;
      width:80px;
      text-align: center;
      .top_menu_title{
        color: #FFF;
        font-size: 13px;
        padding-right: 5px;
        padding-left: 5px;
      }
    }
  }
}

.active {
  background-color: #6fb3e0;
  font-weight: bolder;
}

</style>
