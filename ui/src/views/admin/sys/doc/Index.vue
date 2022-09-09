<template>
  <div>
    <el-card shadow="never" class="card">
      <el-row>
        <el-tabs v-model="active">
          <el-tab-pane
              style="padding-top:10px"
              label="使用说明"
              name="使用说明">
            <div
                style="width: 100%;overflow:auto;"
                class="markdown-body"
                v-html="help"
                v-highlight></div>
          </el-tab-pane>
          <el-tab-pane
              v-for="(item,index) in app_list"
              :key="index"
              v-if="item.app_type == 2"
              style="padding-top:10px"
              :label="item.application_name"
              :name="index.application_name">


              <a :href="item.doc" target="_blank" style="color:#FFF;">
                <el-button type="primary">在新窗口中打开</el-button>
              </a>
          </el-tab-pane>
        </el-tabs>
      </el-row>
    </el-card>
  </div>
</template>
<script>
import {
  getAppDocList,getAppDocHelp
} from '../sys'
import {mapGetters} from "vuex";

export default {
  name: 'Menu',
  data() {
    return {
      help: '',
      app_list: [],
      loading: false,
      active: '使用说明'
    };
  },
  mounted() {
    let showdown =require('showdown')
    let convert = new showdown.Converter()
    getAppDocHelp().then(res=>{
      this.help = convert.makeHtml(res.data)
    })
    this.loading = true
    getAppDocList().then(res=>{
      //console.log(res)
      this.app_list = res.data
      this.loading = false
    })
  },
};
</script>
<style lang="scss">
@import '@/assets/scss/common.scss';
</style>
