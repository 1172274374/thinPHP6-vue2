import defaultSettings from '@/settings'
import { getSetting } from '@/api/admin/base'

const {  defaultSetting } = defaultSettings

export default {

    state :{
        tagsView: true,
        formSize: 'mini',
        inputWidth: 150,
        labelWidth: 100,
        mainBackgroundColor: '#F2F6FC',
        asideBackgroundColor: '#2f4050',
        asideActiveTextColor: '#67c23a',
        asideTextColor: '#FFFFFF',
        headerBackgroundColor: '#438eb9',
        headerActiveBackgroundColor: '#6fb3e0',
    },
    mutations :{
        CHANGE_SETTING: (state, { key, value }) => {
            if (state.hasOwnProperty(key)) {
              state[key] = value
            }
        },
        CHANGE_SETTING_BATCH: (state, array)=>{
            array.forEach((item,index)=>{
                if (state.hasOwnProperty(item.key)) {
                    state[item.key] = item.value
                }
            })
        }
    },
    actions :{
        changeSetting({ commit }, data) {
            commit('CHANGE_SETTING', data)
        },
        changeSettingBatch({ commit }, data){
            commit('CHANGE_SETTING_BATCH', data)
        },
        getSetting({ commit }){
            commit('CHANGE_SETTING_BATCH', defaultSetting)
            getSetting().then(res=>{
                if(res.status == 200 && res.data){
                    commit('CHANGE_SETTING_BATCH', JSON.parse(res.data))
                }
            })
        }
    }
}
