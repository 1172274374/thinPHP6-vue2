<template>
    <div>
        <v-md-editor v-model="text" :disabled-menus="[]" @upload-image="handleUploadImage" height="400px"></v-md-editor>
    </div>
</template>

<script>
import Vue from 'vue'
import VueMarkdownEditor from '@kangc/v-md-editor';
import '@kangc/v-md-editor/lib/style/base-editor.css';
import vuepressTheme from '@kangc/v-md-editor/lib/theme/vuepress.js';
import '@kangc/v-md-editor/lib/theme/style/vuepress.css';
VueMarkdownEditor.use(vuepressTheme);
Vue.use(VueMarkdownEditor);
export default {

  name:'Mdeditor',

  props: {
    mdContent:{
      type:String,
      default:""
    },
    upload_config_id:{
      type:Number,
    }
  },
  watch: {
    mdContent(val){
      this.text = val
    },
    text(val) {
       this.$emit('update:mdContent',val)
    }
  },
  data() {
    return {
      text: this.mdContent ? this.mdContent : '',
    };
  },
  methods: {
    handleUploadImage(event, insertImage, files) {
      let formdata = new FormData()
      formdata.append('file', files[0])
      formdata.append('edit', true)
      formdata.append('upload_config_id', this.upload_config_id)
      this.$axios.post('/Upload/upload', formdata).then(res => {
         insertImage({url:res.data.data,desc:''})
      })
    },
  },
};
</script>