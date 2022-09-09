<template>
  <div id="tags-view-container" class="tags-view-container">
    <el-row>
      <el-col :span="22">
        <scroll-pane ref="scrollPane" class="tags-view-wrapper">
          <router-link
              v-for="tag in tags"
              ref="tag"
              :key="tag.path"
              :class="isActive(tag)?'active':''"
              :to="{ path: tag.path, query: tag.query, fullPath: tag.fullPath }"
              class="tags-view-item">
            {{ tag.title }}
            <span
                v-if="tag.name !== 'Home'"
                style="color:#606266; font-size:50%"
                class="el-icon-close"
                @click.prevent.stop="closeSelectedTag(tag)"/>
          </router-link>
        </scroll-pane>
      </el-col>
      <el-col :span="2" class="moretag">
        <el-dropdown
            trigger="click"
            style="cursor: pointer;">
          <span class="el-dropdown-link">
            <span v-if="$store.getters.device!=='mobile'">其它操作</span>
            <i class="icontool el-icon-arrow-down"></i>
          </span>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item icon="el-icon-back" @click.native.prevent="closeLeftTags">关闭左侧</el-dropdown-item>
            <el-dropdown-item icon="el-icon-right" @click.native.prevent="closeRightTags">关闭右侧</el-dropdown-item>
            <el-dropdown-item icon="el-icon-close" @click.native.prevent="closeOthersTags">关闭其它</el-dropdown-item>
            <el-dropdown-item icon="el-icon-circle-close" @click.native.prevent="closeAllTags">全部关闭</el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import ScrollPane from './ScrollPane'
import { recursion } from '@/utils/common'
export default {
  components: {ScrollPane},
  computed: {
    tags() {
      return this.$store.getters.tags
    },
    routes() {
      return this.$store.getters.addRoutes
    },
  },
  watch: {
    $route() {
      // 添加标签
      this.addTags()
      // 路由变化的时候，自动存储当前的标签信息
      this.updateTagsLocal()
    }
  },
  data() {
    return {
      visible: false,
      tagsLocal: {
        path: null,
        query: null,
        fullPath: null,
        title: null,
        meta: {
          title: null
        },
        name: null
      }
    }
  },
  mounted() {
    this.initTag()
    this.addTags()
  },
  methods: {
    isActive(route) {
      return route.path === this.$route.path
    },
    addTags() {
      // 因为存在循环引用问题，不能直接存储route对象，需要把需要的属性单独组建一个对象
      const {name} = this.$route
      if (name) {
        Object.assign(this.tagsLocal,this.$route)
        this.$store.dispatch('addTags', this.tagsLocal)
      }
      return false
    },
    initTag() {
      // 先检查本地是否缓存了标签信息，如果有则载入标签信息
      let loaclTags = JSON.parse(sessionStorage.getItem('tagsLocal'));
      if(loaclTags){
        for(const tag of loaclTags){
          Object.assign(this.tagsLocal,tag)
          this.$store.dispatch('addTags', this.tagsLocal)
        }
      } else {
        // 本地没有存储，则只载入首页
        for (const tag of this.routes[0].children) {
          if (tag.name == 'Home') {
            Object.assign(this.tagsLocal,tag)
            this.$store.dispatch('addTags', this.tagsLocal)
          }
        }
      }
    },
    // 删除当前的
    closeSelectedTag(view) {
      this.$store.dispatch('deletetag', view).then(({tags}) => {
        if (this.isActive(view)) {
          this.toLastView(tags, view)
        }
      })
    },
    // 删除其他的
    closeOthersTags() {
      this.$store.dispatch('delOthersViews', this.$route).then(()=>{
        // 因为路由没有变化，必须手工更新本地缓存
        this.updateTagsLocal()
      })
    },
    // 删除全部的，因为删除全部，需要将首页路由加入，路由发生变化，自动更新本地缓存
    closeAllTags(view) {
      this.$store.dispatch('delAllViews').then(({tags}) => {
        this.$router.push('/admin/Home/index.html')
      })
    },
    // 删除右边的，路由没有变化，需要手工更新本地缓存
    closeRightTags() {
      this.$store.dispatch('delRightTags', this.$route).then(tags => {
        // 更新缓存
        this.updateTagsLocal()
        if (!tags.find(i => i.fullPath === this.$route.fullPath)) {
          this.toLastView(visitedViews)
        }
      })
    },
    // 删除左边的，路由没有变化，需要手工更新本地缓存
    closeLeftTags() {
      this.$store.dispatch('delLeftTags', this.$route).then(tags => {
        // 更新缓存
        this.updateTagsLocal()
        if (!tags.find(i => i.fullPath === this.$route.fullPath)) {
          this.toLastView(visitedViews)
        }
      })
    },
    //关闭当前路由后跳转到下一个路由
    toLastView(tags, view) {
      const latestView = tags.slice(-1)[0]
      if (latestView) {
        if (latestView.name == 'Home') {
          this.$router.push('/admin/Home/index.html')
        } else {
          this.$router.push(latestView.fullPath)
        }
      } else {
        this.$router.push('/admin/Home/index.html')
      }
    },
    // 更新本地存储
    updateTagsLocal(){
      let tagsObjArray = []
      this.$store.getters.tags.forEach((item,index)=>{
        tagsObjArray.push({
          path: item.path,
          query: item.query,
          fullPath: item.fullPath,
          title: item.meta.title,
          meta: {title: item.meta.title},
          name: item.name
        })
      })
      sessionStorage.setItem('tagsLocal',JSON.stringify(tagsObjArray))
    }
  },
}
</script>

<style lang="scss" scoped>
.tags-view-container {
  width: 100%;
  margin-bottom: 5px;
  margin-top: -10px;

  .moretag {
    line-height: 32px;
    text-align: right;
    color: #808695;
    font-size: 14px;
  }

  .tags-view-wrapper {
    .tags-view-item {
      display: inline-block;
      position: relative;
      cursor: pointer;
      height: 26px;
      line-height: 26px;
      color: #808695;
      background: #fff;
      padding: 3px 12px;
      font-size: 14px;
      margin-right: 5px;

      &.active {
        color: #42b983;
      }

      &:hover {
        color: #606266
      }
    }
  }
}
</style>
