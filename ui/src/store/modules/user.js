import { login,getUserInfo,logout} from '@/api/admin/base'


export default {
    state :{
        addRoutes:[],
        menu:[],
        actions:[],
        name: '',
        user: '',
        headimg: '',
        site_logo:'',
        site_title:'',
        show_notice:true,
    },
    mutations :{
        setRoute(state,route){
            state.addRoutes = route
        },
        setMenu(state,menu){
            state.menu = menu
        },
        setActions(state,actions){
            state.actions = actions
        },
        setUserInfo(state,val){
            state.name = val.name
            state.user = val.user
            state.headimg = val.headimg
            state.role_id = val.role_id
            state.site_title = val.site_title
            state.site_logo = val.site_logo
            state.show_notice = val.show_notice
        }
    },
    actions :{
        login({commit},userInfo) {
            return new Promise((resolve, reject) => {
                login(userInfo).then(res => {
                    localStorage.setItem("adminToken", res.token)
                    resolve(res)
                }).catch(error => {
                    reject(error)
                })
            })
        },
        getUserInfo({commit}){
            return new Promise((resolve, reject) => {
                getUserInfo().then(res => {
                    // console.log('components',res.components)
                    if(res.status == 200){
                        let routes = res.components
                        let baseRoutes = [
                            {
                                path:'/',
                                component: (resolve) => require(['@/layout/admin/default/Main'], resolve),
                                children:[]
                            },
                        ]
                        routes.forEach(item =>{
                            if(item.component_path){
                                item.component = (resolve) => require([`@/views/${item.component_path}`],resolve)
                                baseRoutes[0].children.push(item)
                            }
                        })
                        commit('setRoute',baseRoutes.concat({path:'*',redirect:'/404'}))
                        commit('setMenu',res.menu)
                        commit('setActions',res.actions)
                        commit('setUserInfo',res.data)

                        console.log('baseRoutes::',baseRoutes)
                    }

                    resolve()
                }).catch(error => {
                    reject(error)
                })
            })
        },
        logout({commit}){
            return new Promise((resolve, reject) => {
                const adminToken = localStorage.getItem("adminToken")
                logout({adminToken:adminToken}).then(res => {
                    if(res.status == 200){
                        localStorage.setItem("adminToken", "")
                        commit('setRoute',[])
                        commit('setMenu',[])
                        commit('setActions',[])
                        commit('setUserInfo',{})
                    }
                    resolve()
                }).catch(error => {
                    reject(error)
                })
            })
        }

    }
}
