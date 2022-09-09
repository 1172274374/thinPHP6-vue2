import router from './router'
import store from './store'
import NProgress from 'nprogress' // 进度条
import 'nprogress/nprogress.css' // 进度条样式

NProgress.configure({ showSpinner: false }) // 进度条配置

const whiteList = ['/admin/login'] // 免登录白名单

router.beforeEach(async(to, from, next) => {
    
    NProgress.start()
    if (to.meta.title) {
        if(store.getters.site_title){
            document.title = to.meta.title +'-'+ store.getters.site_title
        }else{
            document.title = to.meta.title
        }
    }

    const adminToken = localStorage.getItem("adminToken")
    if(adminToken){
        if(to.path ==='/admin/login'){
            next('/admin/Home/index.html')
            NProgress.done()
        }else{
            if(store.getters.addRoutes.length > 0) {
                next()
            }else{
                store.dispatch('getUserInfo').then(()=>{
                    let routes = store.getters.addRoutes
                    routes.forEach((item)=>{
                        router.addRoute(item)
                    })
                    // router.addRoutes(routes)
                    next({ ...to, replace: true })
                }).catch(() => {
                    store.dispatch('logout').then(() => {
                        next({ path: '/' })
                    })
                })
            }
        }
    }else{
        if(whiteList.indexOf(to.path) !== -1) {
            next()
        } else {
            next('/admin/login')
            NProgress.done()
        }
    }

})

router.afterEach(() => {
    NProgress.done()
})