export default {
    state :{
       tags:[],
    },
    mutations :{
        // 添加标签
        add_tags: (state, view) => {
            if (state.tags.some(v => v.path === view.path)) return
                state.tags.push(
                Object.assign({}, view, {
                    title: view.meta.title || 'no-name'
                })
            )
        },
        // 删除已打开的标签
        del_visited_view: (state, view) => {
            for (const [i, v] of state.tags.entries()) {
              if (v.path === view.path) {
                state.tags.splice(i, 1)
                break
              }
            }
        },
        // 删除其他已打开的标签
        del_other_visited_views: (state, view) => {
            state.tags = state.tags.filter(v => {
              return v.name == 'Home' || v.path === view.path
            })
        },
        // 删除全部打开的标签
        del_all_visited_views: state => {
            const affixTags = state.tags.filter(tag => tag.name == 'Home')
            state.tags = affixTags
        },
        // 更新打开的标签
        update_visited_view: (state, view) => {
            console.log('更新打开的标签')
            for (let v of state.tags) {
                if (v.path === view.path) {
                  v = Object.assign(v, view)
                  break
                }
            }
        },
        // 删除右边的标签
        del_right_tags: (state, view) => {
            const index = state.tags.findIndex(v => v.path === view.path)
            if (index === -1) {
              return
            }
            state.tags = state.tags.filter((item, idx) => {
              if (idx <= index) {
                return true
              }
              return false
            })
        },
        // 删除左边的标签
        del_left_tags: (state, view) => {
            const index = state.tags.findIndex(v => v.path === view.path)
            if (index === -1) {
              return
            }
            state.tags = state.tags.filter((item, idx) => {
              if (idx >= index || item.name == 'Home') {
                return true
              }
              return false
            })
        }
    },
    actions :{
        addTags({ commit }, route) {
            // console.log('添加标签')
            commit('add_tags', route)
        },

        deletetag({ dispatch, state }, view) {
            // console.log('删除标签')
            return new Promise(resolve => {
                dispatch('delVisitedView', view)
                resolve({
                    tags: [...state.tags],
                })
            })
        },
        delVisitedView({ commit, state }, view) {
            return new Promise(resolve => {
                commit('del_visited_view', view)
                resolve([...state.tags])
            })
        },
        updateVisitedView({ commit }, view) {
            commit('update_visited_view', view)
        },
        delOthersViews({ dispatch, state }, view) {
            return new Promise(resolve => {
              dispatch('delOthersVisitedViews', view)
              resolve({
                tags: [...state.tags],
              })
            })
        },
        delOthersVisitedViews({ commit, state }, view) {
            return new Promise(resolve => {
              commit('del_other_visited_views', view)
              resolve([...state.tags])
            })
        },
        delAllViews({ dispatch, state }, view) {
            return new Promise(resolve => {
              dispatch('delAllVisitedViews', view)
              resolve({
                tags: [...state.tags],
              })
            })
        },
        delAllVisitedViews({ commit, state }) {
            return new Promise(resolve => {
              commit('del_all_visited_views')
              resolve([...state.tags])
            })
        },
        delRightTags({ commit,state }, view) {
            return new Promise(resolve => {
              commit('del_right_tags', view)
              resolve([...state.tags])
            })
        },
        delLeftTags({ commit,state }, view) {
            return new Promise(resolve => {
              commit('del_left_tags', view)
              resolve([...state.tags])
            })
        }
    }
}
