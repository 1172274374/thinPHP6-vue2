<template>
  <el-aside
      id="aside"
      style="overflow-x: hidden;overflow-y: hidden">
    <el-scrollbar
        class="scrollbar-wrapper"
        v-bind:style="{ backgroundColor: asideBackgroundColor, height: '100%' }">
      <div>
        <!-- 侧栏顶部 logo 区域 -->
        <div style="height:50px;color:#fff;text-align:center;">
          <div class="site_title" v-if="!isCollapse">{{ site_title }}</div>
          <el-image v-else :src="site_logo" style="width:30px; height:30px;margin-top:15px;"></el-image>
        </div>

        <!-- 默认顶部菜单为首页时，展示完整的菜单树 -->
        <el-menu
            v-if="currentMenu == 0"
            class="el-menu-vertical-demo"
            router
            :default-active="activie_index"
            :unique-opened="true"
            :background-color="asideBackgroundColor"
            :active-text-color="asideActiveTextColor"
            :text-color="asideTextColor"
            :collapse="isCollapse">
          <!-- 一级菜单 -->
          <template v-for="item in menu">
            <el-submenu
                v-if="item.children && item.children.length"
                :index="item.path"
                :key="item.menu_id">
              <template slot="title">
                <i :class="item.icon"></i><span>{{ item.title }}</span>
              </template>

              <!-- 二级菜单 -->
              <template v-for="itemChild in item.children">
                <el-submenu
                    v-if="itemChild.children && itemChild.children.length"
                    :index="itemChild.path"
                    :key="itemChild.menu_id">
                  <template slot="title">
                    <i :class="itemChild.icon"></i><span>{{ itemChild.title }}</span>
                  </template>
                  <!-- 三级级菜单 -->
                  <template v-for="_itemChild in itemChild.children">
                    <el-submenu
                        v-if="_itemChild.children && _itemChild.children.length"
                        :index="_itemChild.path"
                        :key="_itemChild.menu_id">
                      <template slot="title">
                        <i :class="_itemChild.icon"></i><span>{{ _itemChild.title }}</span>
                      </template>
                      <!-- 四级菜单 -->
                      <el-menu-item
                          v-for="itemChild_Child in _itemChild.children"
                          :index="itemChild_Child.path"
                          :key="itemChild_Child.menu_id">
                        <i :class="itemChild_Child.icon"></i><span slot="title">{{ itemChild_Child.title }}</span>
                      </el-menu-item>
                    </el-submenu>

                    <el-menu-item
                        v-else
                        :index="_itemChild.path"
                        :key="_itemChild.menu_id">
                      <i :class="_itemChild.icon"></i><span slot="title">{{ _itemChild.title }}</span>
                    </el-menu-item>
                  </template>
                </el-submenu>

                <el-menu-item
                    v-else
                    :index="itemChild.path"
                    :key="itemChild.menu_id">
                  <i :class="itemChild.icon"></i><span slot="title">{{ itemChild.title }}</span>
                </el-menu-item>
              </template>

            </el-submenu>
            <el-menu-item
                v-else
                :index="item.path"
                :key="item.menu_id">
              <i :class="item.icon"></i><span slot="title">{{ item.title }}</span>
            </el-menu-item>
          </template>
        </el-menu>

        <!-- 如果点击了顶部的非首页菜单，则只展示此菜单下面的子菜单 -->
        <el-menu
            v-else
            class="el-menu-vertical-demo"
            router
            :default-active="activie_index"
            :unique-opened="true"
            :background-color="asideBackgroundColor"
            :active-text-color="asideActiveTextColor"
            :text-color="asideTextColor"
            :collapse="isCollapse">
          <!-- 二级菜单 -->
          <template v-for="itemChild in menu[currentMenu].children">
            <el-submenu
                v-if="itemChild.children && itemChild.children.length"
                :index="itemChild.path"
                :key="itemChild.menu_id">
              <template slot="title">
                <i :class="itemChild.icon"></i><span>{{ itemChild.title }}</span>
              </template>

              <!-- 三级级菜单 -->
              <template v-for="_itemChild in itemChild.children">
                <el-submenu
                    v-if="_itemChild.children && _itemChild.children.length"
                    :index="_itemChild.path"
                    :key="_itemChild.menu_id">
                  <template slot="title">
                    <i :class="_itemChild.icon"></i><span>{{ _itemChild.title }}</span>
                  </template>
                  <!-- 四级菜单 -->
                  <el-menu-item
                      v-for="itemChild_Child in _itemChild.children"
                      :index="itemChild_Child.path"
                      :key="itemChild_Child.menu_id">
                    <i :class="itemChild_Child.icon"></i><span slot="title">{{ itemChild_Child.title }}</span>
                  </el-menu-item>
                </el-submenu>
                <el-menu-item
                    v-else
                    :index="_itemChild.path"
                    :key="_itemChild.menu_id">
                  <i :class="_itemChild.icon"></i><span slot="title">{{ _itemChild.title }}</span>
                </el-menu-item>
              </template>
            </el-submenu>
            <el-menu-item
                v-else
                :index="itemChild.path"
                :key="itemChild.menu_id">
              <i :class="itemChild.icon"></i><span slot="title">{{ itemChild.title }}</span>
            </el-menu-item>
          </template>
        </el-menu>

      </div>
    </el-scrollbar>
  </el-aside>
</template>
<script>
import {mapGetters} from 'vuex'

export default {
  name: 'Side',
  computed: {
    ...mapGetters([
      'menu', 'sidebar', 'site_title', 'site_logo', 'currentMenu',
      'asideBackgroundColor', 'asideActiveTextColor', 'asideTextColor', 'headerBackgroundColor']),
    isCollapse() {
      return !this.sidebar.opened
    }
  },
  data() {
    return {
      activie_index: this.$route.path,
    }
  },
  watch: {
    $route() {
      // console.log(this.menu)
      this.activie_index = this.$route.path
    }
  },
};
</script>
<style lang="scss">
.site_title{
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
  width:100%;
  font-weight: bold;
  white-space:normal;
  word-wrap:break-word;
  overflow:auto;
}
.horizontal-collapse-transition {
  transition: 0s width ease-in-out, 0s padding-left ease-in-out, 0s padding-right ease-in-out;
}

.el-menu {
  border-right: 0 !important;
}

#aside::-webkit-scrollbar {
  display: none;
}

#aside {
  height: 100vh;
  overflow-x: hidden;
  overflow-y: scroll;
}

.el-submenu .el-menu-item {
  height: 40px;
  line-height: 40px;
}

.el-menu-item, .el-submenu__title {
  height: 45px;
  line-height: 45px;
}

</style>
