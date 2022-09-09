import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import '@/assets/scss/reset.scss'
import '@/assets/scss/index.scss'
import VueCookies from 'vue-cookies'
import axios from 'axios'

// element-ui
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import 'element-ui/lib/theme-chalk/display.css';
import '@/assets/icon/iconfont.css'
import '@/assets/icon/iconfont.js'
import echarts from 'echarts'

// vxe-table
import XEUtils from 'xe-utils'
import VXETable from 'vxe-table'
import 'vxe-table/lib/style.css'

// 代码高亮
import hljs from "highlight.js"
import 'highlight.js/styles/default.css';
import 'github-markdown-css'

import MyUtils from "@/utils/common.js"
import './permission'

Vue.use(VueCookies)
Vue.use(ElementUI)
Vue.use(VXETable)
Vue.prototype.$XEUtils = XEUtils
Vue.prototype.$MyUtils = MyUtils
Vue.prototype.$axios = axios
Vue.prototype.$echarts = echarts

Vue.config.productionTip = false
Vue.prototype.checkPermission = MyUtils.checkPermission // 权限检查

// 定义自定义指令 highlight 代码高亮
Vue.directive('highlight',function (el) {
  let blocks = el.querySelectorAll('pre code');
  blocks.forEach((block)=>{
    hljs.highlightElement(block)
  })
})


new Vue({
  router,
  store,
  data: {
    eventVue: new Vue()
  },
  render: h => h(App),
}).$mount('#app')
