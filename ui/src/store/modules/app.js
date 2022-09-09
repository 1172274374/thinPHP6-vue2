export default {
    state :{
        currentMenu: 0,
        sidebar: {
            opened: true,
            withoutAnimation: false
        },
        device: 'desktop',
        isIframe: false,
    },
    mutations :{
        current_menu: (state, current_menu) => {
            state.currentMenu = current_menu
        },
        toggle_device: (state, device) => {
            state.device = device
        },
        toggle_sidebar: state => {
            state.sidebar.opened = !state.sidebar.opened
            state.sidebar.withoutAnimation = false
        },
        close_sidebar: (state, withoutAnimation) => {
            state.sidebar.opened = false
            state.sidebar.withoutAnimation = withoutAnimation
        },
        set_is_iframe(state,is_iframe){
            state.isIframe = is_iframe
        },
    },
    actions :{
        currentMenu({ commit }, current_menu) {
            commit('current_menu', current_menu)
        },
        toggleDevice({ commit }, device) {
            commit('toggle_device', device)
        },
        toggleSideBar({ commit }) {
            commit('toggle_sidebar')
        },
        closeSideBar({ commit }, { withoutAnimation }) {
            commit('close_sidebar', withoutAnimation)
        },
        setIsIframe({ commit }, is_iframe) {
            commit('set_is_iframe', is_iframe)
        },
    }
}