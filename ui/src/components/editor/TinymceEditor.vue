<template>
  <div class='tinymce'>
    <editor :id='tinymceId' v-model='tinymceHtml' :init='init'></editor>
  </div>
</template>

<script>
import tinymce from 'tinymce/tinymce'
import 'tinymce/themes/silver/theme'
import editor from '@tinymce/tinymce-vue'
import 'tinymce/plugins/image'
import 'tinymce/plugins/link'
import 'tinymce/plugins/code'
import 'tinymce/plugins/table'
import 'tinymce/plugins/lists'
//import 'tinymce/plugins/contextmenu'
import 'tinymce/plugins/wordcount'
//import 'tinymce/plugins/colorpicker'
//import 'tinymce/plugins/textcolor'
import "tinymce/icons/default/icons"
export default {
  name: "tinymce",
  components: {editor},
  props: {
    tinymceContent:{
      type:String,
      default:""
    },
    tinymceId:{
      type:String,
    },
    upload_config_id:{
      type:Number,
    }
  },
  watch: {
    tinymceContent(val){
      this.tinymceHtml = val
    },
    tinymceHtml(val) {
       this.$emit('update:tinymceContent',val)
    }
  },
  data () {
    return {
      tinymceHtml: this.tinymceContent,
      init: {
        language_url: './static/tinymce/zh_CN.js',
        language: 'zh_CN',
        skin_url: './static/tinymce/skins/ui/oxide',
        content_css: './static/tinymce/skins/content/default/content.css',
        height: 400,
        //plugins: 'link lists image code table colorpicker textcolor wordcount contextmenu',
        plugins: 'link lists image code table wordcount',
        toolbar:'bold italic underline strikethrough | fontsizeselect | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | link unlink image code | removeformat',
        branding: false,
        images_upload_handler: (blobInfo, success, failure) => {
          this.handleImgUpload(blobInfo, success, failure)
        }
      }
    }
  },
  mounted () {
    tinymce.init({})
  },
  methods: {
    handleImgUpload(blobInfo, success, failure) {
      let formdata = new FormData()
      formdata.append('file', blobInfo.blob())
      formdata.append('edit', true)
      formdata.append('upload_config_id', this.upload_config_id)
      this.$axios.post('/Upload/upload', formdata).then(res => {
        success(res.data.data);
      })
    }
  },
  
}
</script>