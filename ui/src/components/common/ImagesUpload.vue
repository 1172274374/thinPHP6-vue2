<template>
  <div>
    <div v-if="fileType == 'images'" class="image-list" style="width:100%">
      <draggable v-model="imageList" v-bind="{group:'item'}" @change="changeSort">
        <div v-for="(item, index) in imageList" :key="index" class="image-wrap">
          <div class="imgStyle">
            <img :src="item.url"/><br/>
            <el-input size="mini" v-if="showtitle" style="width:102px; position:relative; bottom:10px;"
                      v-model="item.name" placeholder="图片名称"></el-input>
          </div>
          <div class="icon-wrap" :style="{bottom:bottom}">
            <i class="el-icon-delete" @click.stop="handleRemoveImages(index)"></i>&nbsp;&nbsp;
            <i style="cursor:pointer" :class="showtitle ? 'el-icon-top' : 'el-icon-bottom'"
               @click.stop="handlePictureCardPreview(index)"></i>
          </div>
        </div>
        <el-upload action="#" ref="upload" class="image-uploader" :before-upload="beforeUpload" :http-request="upload"
                   multiple>
          <i :class="loading ? 'el-icon-loading' : 'el-icon-plus'"></i>
        </el-upload>
      </draggable>
    </div>
    <div v-else-if="fileType == 'image'" class="image-list" style="width:100%">
      <el-row v-if="uploadType == '1'">
        <div class="image-wrap" v-if="siglepic">
          <div class="imgStyle">
            <img :src="siglepic"/>
          </div>
          <div class="icon-wrap" :style="{bottom:bottom}">
            <i class="el-icon-delete" @click.stop="handleRemoveImage()"></i>
          </div>
        </div>
        <el-upload action="#" ref="upload" class="image-uploader" :before-upload="beforeUpload" :http-request="upload">
          <i :class="loading ? 'el-icon-loading' : 'el-icon-plus'"></i>
        </el-upload>
      </el-row>
      <el-row v-else>
        <el-col :span="16">
          <el-popover v-if="siglepic" placement="top" width="200" trigger="hover">
            <img style="max-width:200px; max-height:200px" :src="siglepic">
            <el-input :size="size" slot="reference" v-model="siglepic" autoComplete="off" clearable
                      placeholder="请上传图片"></el-input>
          </el-popover>
          <el-input v-else :size="size" v-model="siglepic" autoComplete="off" clearable placeholder="请上传图片"></el-input>
        </el-col>
        <el-col :span="8">
          <el-upload class="upload-demo" ref="upload" action="#" :before-upload="beforeUpload" :http-request="upload">
            <el-button class="el-icon-upload" :size="size" type="primary">上传图片</el-button>
          </el-upload>
          <el-tooltip class="item hidden-sm-and-down" effect="dark" :content="'选择图片'" placement="top">
            <el-button class="el-icon-picture" :size="size" @click="openImagesPick"></el-button>
          </el-tooltip>
        </el-col>

      </el-row>
    </div>
    <div v-else-if="fileType == 'file'" class="image-list" style="width:100%">
      <el-upload class="upload-demo" ref="upload" action="#" :before-upload="beforeUpload" :http-request="upload">
        <el-button :size="size" type="primary">点击上传</el-button>
      </el-upload>
      <li v-if="sigleFile" tabindex="0" class="el-upload-list__item is-success">
        <a @click.stop="handleRemoveFile" class="el-upload-list__item-name"><i class="el-icon-document"></i>{{sigleFile}}</a>
        <label class="el-upload-list__item-status-label">
          <i class="el-icon-upload-success el-icon-circle-check"></i>
        </label>
        <i class="el-icon-close"></i>
      </li>
    </div>
    <div v-else class="image-list" style="width:100%">
      <el-upload action="#" ref="upload" :before-upload="beforeUpload" :http-request="upload" multiple>
        <el-button :size="size" type="primary">点击上传</el-button>
      </el-upload>
      <ul class="el-upload-list el-upload-list--text">
        <li v-for="(item,i) in fileList" :tabindex="i" :key="i" class="el-upload-list__item is-success">
          <a @click.stop="handleRemoveFiles(i)" class="el-upload-list__item-name"><i class="el-icon-document"></i>{{item.name}}</a>
          <label class="el-upload-list__item-status-label">
            <i class="el-icon-upload-success el-icon-circle-check"></i>
          </label>
          <i class="el-icon-close"></i>
        </li>
      </ul>
    </div>
    <el-progress v-if="progress" :stroke-width="5" :percentage="progressPercent"></el-progress>
    <ImagesPick :show.sync="imagesDialogStatus" :siglepic.sync="siglepic" :size="size"></ImagesPick>
  </div>
</template>

<script>
import draggable from "vuedraggable"
import ImagesPick from '@/components/common/ImagesPick.vue'
import {createFile} from '@/api/admin/base'

export default {
  name: 'ImagesUpload',
  components: {draggable, ImagesPick},
  props: {
    picker: {
      type: String,
      default: '',
    },
    images: {
      type: Array,
      default: () => [{
        url: '',
        name: '',
      }]
    },
    image: {
      type: String,
    },
    file: {
      type: String,
    },
    files: {
      type: Array,
      default: () => []
    },
    fileType: {
      type: String
    },
    uploadType: {
      type: String,
      default: '1',
    },
    upload_config_id: {
      type: Number,
      default: 1,
    },
    size: {
      type: String,
      default: 'mini'
    }
  },
  data() {
    return {
      loading: false,
      showtitle: false,
      siglepic: this.image,
      imageList: this.images,
      sigleFile: this.file,
      fileList: this.files,
      bottom: '12px',
      videoFlag: true,
      progressPercent: 0,
      progress: false,
      imagesDialogStatus: false,
      uploadUrl: '',
    }
  },
  watch: {
    image() {
      this.siglepic = this.image
    },
    images() {
      this.imageList = this.images
    },
    file() {
      this.sigleFile = this.file
    },
    files() {
      this.fileList = this.files
    },
    siglepic() {
      this.$emit('update:image', this.siglepic)
    }
  },
  methods: {
    beforeUpload(item) {
      if (this.fileType == 'image' || this.fileType == 'images') {
        if (item.type.split('/')[0] !== 'image') {
          this.$message.error('请选择图片')
          return false
        }
      }
      this.loading = true
    },
    upload(item) {
      let formdata = new FormData()
      let ali = new FormData()  //此处非常坑 阿里上传file属性必须要放到最后  所以只能赋值对象
      formdata.append('file', item.file)
      formdata.append('upload_config_id', this.upload_config_id)
      const config = {
        onUploadProgress: progressEvent => {
          if (progressEvent.lengthComputable) {
            this.progress = true
            this.progressPercent = Number((progressEvent.loaded / progressEvent.total * 100).toFixed(2))
          }
        }
      }
      try {
        this.$axios.post('/Upload/upload', formdata, config).then(res => {
          if (res.data.status == 200) {
            if (res.data.filestatus) {
              this.setFilePath(item, res.data.data)
            } else {
              if (res.data.data.type == 'qiniuyun') {
                formdata.append('token', res.data.data.token)
                formdata.append('key', res.data.data.key)
                this.$axios.post(res.data.data.serverurl, formdata, config).then(ret => {
                  if (ret.data.key) {
                    createFile({filepath: res.data.data.domain + '/' + ret.data.key}).then(result => {
                      this.setFilePath(item, res.data.data.domain + '/' + ret.data.key)
                    })
                  }
                })
              } else if (res.data.data.type == 'ali') {
                ali.append('Signature', res.data.data.sign)
                ali.append('callback', res.data.data.callback)
                ali.append('OSSAccessKeyId', res.data.data.OSSAccessKeyId)
                ali.append('policy', res.data.data.policy)
                ali.append('key', res.data.data.key)
                ali.append('file', item.file)
                this.$axios.post(res.data.data.serverurl, ali, config).then(ret => {
                  if (ret.data.code == 1) {
                    createFile({filepath: ret.data.data}).then(result => {
                      this.setFilePath(item, ret.data.data)
                    })
                  }
                })
              } else {
                this.setFilePath(item, res.data.data)
              }
            }
          }
        })
      } catch (error) {
        this.$message.error('上传失败')
      } finally {
        this.loading = false
        this.$refs.upload && this.$refs.upload.clearFiles()
      }
    },
    setFilePath(item, filepath) {
      this.progressPercent = 0
      this.progress = false
      if (this.fileType == 'images') {
        this.imageList.push({url: filepath, name: item.file.name})
        this.$emit('update:images', this.imageList)
      } else if (this.fileType == 'image') {
        this.siglepic = filepath
        this.$emit('update:image', filepath)
      } else if (this.fileType == 'files') {
        this.fileList.push({url: filepath, name: item.file.name})
        this.$emit('update:files', this.fileList)
      } else {
        this.sigleFile = filepath
        this.$emit('update:file', filepath)
      }
    },
    handleRemoveImages(index) {
      this.$MyUtils.confirm({content: '确定要删除吗'}).then(() => {
        this.imageList.splice(index, 1)
        this.$emit('update:images', this.imageList)
      }).catch(() => {
      })
    },
    handleRemoveFiles(index) {
      this.$MyUtils.confirm({content: '确定要删除吗'}).then(() => {
        this.fileList.splice(index, 1)
        this.$emit('update:files', this.fileList)
      }).catch(() => {
      })
    },
    handleRemoveImage() {
      this.$MyUtils.confirm({content: '确定要删除吗'}).then(() => {
        this.siglepic = ''
        this.$emit('update:image', '')
      }).catch(() => {
      })
    },
    handleRemoveFile() {
      this.$MyUtils.confirm({content: '确定要删除吗'}).then(() => {
        this.sigleFile = ''
        this.$emit('update:file', '')
      }).catch(() => {
      })
    },
    handlePictureCardPreview(i) {
      this.showtitle = !this.showtitle
      this.showtitle == true ? this.bottom = "45px" : this.bottom = "12px"
    },
    changeSort() {
      this.$emit('update:images', this.imageList)
    },
    openImagesPick() {
      this.imagesDialogStatus = true
    }
  }
}
</script>

<style lang="scss">


.upload-demo {
  display: inline-block;
  margin-right: 5px;
}

.el-upload--picture-card {
  width: 100px;
  height: 100px;
  line-height: 100px;
}

.el-upload-list--picture-card .el-upload-list__item {
  width: 100px;
  height: 100px;
}

.el-upload-list__item-name {
  padding-left: 0;
}

.el-upload-list__item {
  line-height: 1.1;
}

.image-list, .image-item {
  .image-wrap {
    position: relative;
    display: inline-block;
    box-sizing: content-box;
    margin: 0 10px 0 0;
    vertical-align: top;

    &:hover {
      .icon-wrap {
        opacity: 1;
      }
    }

    .imgStyle img {
      width: 100px;
      height: 100px;
      border: 1px solid #d9d9d9;
      border-radius: 6px;
    }

    .icon-wrap {
      position: absolute;
      left: 0;
      border-radius: 6px;
      width: 102px;
      height: 102px;
      cursor: default;
      text-align: center;
      line-height: 100px;
      color: #fff;
      opacity: 0;
      font-size: 20px;
      background-color: rgba(0, 0, 0, .7);
      transition: opacity .3s;

      .el-icon-zoom-in {
        cursor: pointer;
        margin-right: 8px;
      }

      .el-icon-delete {
        cursor: pointer;
      }
    }
  }
}

.image-item {
  display: inline-flex;
}

.image-uploader {
  display: inline-block;

  .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    width: 100px;
    height: 100px;

    [class^="el-icon"] {
      line-height: 100px;
      font-size: 24px;
      color: #8c939d;
      text-align: center;
    }

    &:hover {
      border-color: #409EFF;
    }
  }
}
</style>
