import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    redirect: '/admin/Home/index.html',
  },
  {
    path: '/admin/login',
    name: 'login',
    meta:{
        title:'系统登录'
    },
    component: resolve => require(['@/views/admin/base/login.vue'], resolve),
  },
  {
    path: '/404',
    name: '404',
    component: resolve => require(['@/views/error/404.vue'], resolve),
  },
]

const createRouter = () => new VueRouter({
  scrollBehavior: () => ({
    y: 0
  }),
  routes: routes
})

const originalPush = VueRouter.prototype.push
VueRouter.prototype.push = function push(location, onResolve, onReject) {
  if (onResolve || onReject) return originalPush.call(this, location, onResolve, onReject)
  return originalPush.call(this, location).catch(err => err)
}

const router = createRouter()

export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router