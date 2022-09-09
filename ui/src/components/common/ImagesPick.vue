<template>
  <div>
    <el-dialog title="图片管理" width="640px" class="icon-dialog" :visible.sync="show" @open="open"
               :before-close="closeForm" append-to-body>
      <div style="padding-left:10px">
        <el-row :gutter="20">
          <el-col :span="4">
            <el-upload class="upload-demo" ref="upload" multiple action="#" :before-upload="beforeUpload"
                       :http-request="upload">
              <el-button class="el-icon-upload" :size="size" type="primary"> 上传图片</el-button>
            </el-upload>
          </el-col>
          <el-col :span="8">
            <el-button class="el-icon-delete" :size="size" type="danger" @click="deletePic">批量删除</el-button>
          </el-col>
        </el-row>
        <el-progress v-if="progress" :stroke-width="5" :percentage="progressPercent"></el-progress>
        <el-row style="margin-top:15px;">
          <el-col :span="24">
            <el-row>
              <div class="image-wrap" v-for="(item,index) in list" :key="index" @click="selection(item.filepath)">
                <label v-for="(v, k) in files" :key="k" v-show="item.filepath === v">
                  <input :size="size" type="checkbox" :checked="item.filepath === v">
                  <i>✓</i>
                </label>
                <div class="imgStyle">
                  <img :src="item.filepath"/><br/>
                </div>
              </div>
            </el-row>
            <Page :total="page_data.total" :page.sync="page_data.page" :limit.sync="page_data.limit"
                  @pagination="index"/>
          </el-col>
        </el-row>
      </div>
      <div slot="footer" class="dialog-footer">
        <el-button :size="size" :loading="loading" type="primary" @click="submit">
          <span>确 定</span>
        </el-button>
        <el-button :size="size" @click="closeForm">取 消</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import {fileList, deleteFile, createFile} from '@/api/admin/base'
import Page from '@/components/common/Page.vue'

export default {
  components: {
    Page,
  },
  props: {
    show: {
      type: Boolean,
      default: false
    },
    size: {
      type: String,
      default: 'mini'
    }
  },
  watch: {
    show(value) {
      if (value) {
        this.index()
      }
    }
  },
  data() {
    return {
      files: [],
      list: [],
      loading: false,
      progressPercent: 0,
      progress: false,
      page_data: {
        limit: 20,
        page: 1,
        total: 20,
      },
    }
  },
  methods: {
    open() {
      this.files = []
    },
    index() {
      let param = {limit: this.page_data.limit, page: this.page_data.page}
      this.loading = true
      fileList(param).then(res => {
        this.list = res.data.data
        this.page_data.total = res.data.total
        this.loading = false
      })
    },
    selection(filepath) {
      let index = this.files.indexOf(filepath)
      if (index === -1) {
        this.files.push(filepath)
      } else {
        this.files.splice(index, 1)
      }
    },
    deletePic() {
      this.$MyUtils.confirm({content: '确定要操作吗'}).then(() => {
        deleteFile({filepath: this.files}).then(res => {
          this.$message({message: res.msg, type: 'success'})
          this.index()
        })
      })
    },
    beforeUpload(item) {
      if (item.type.split('/')[0] === 'image') {
        let tempSize = item.size / 5242880
        if (tempSize > 1) {
          this.$message.error('图片尺寸不得大于5M！')
          return false
        }
      }
    },
    upload(item) {
      let formdata = new FormData()
      let ali = new FormData()  //此处非常坑 阿里上传file属性必须要放到最后  所以只能赋值对象
      formdata.append('file', item.file)
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
            if (!res.data.filestatus) {
              if (res.data.data.type == 'qiniuyun') {
                formdata.append('token', res.data.data.token)
                formdata.append('key', res.data.data.key)
                this.$axios.post(res.data.data.serverurl, formdata, config).then(ret => {
                  if (ret.data.key) {
                    createFile({filepath: res.data.data.domain + '/' + ret.data.key}).then(res => {
                      this.progressPercent = 0
                      this.progress = false
                      this.index()
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
                    createFile({filepath: ret.data.data}).then(res => {
                      this.progressPercent = 0
                      this.progress = false
                      this.index()
                    })
                  }
                })
              } else {
                this.progressPercent = 0
                this.progress = false
                this.index()
              }
            } else {
              this.$message.error('文件已经存在')
            }
          }
        })
      } catch (error) {
        this.$message.error('上传失败')
      } finally {
        this.$refs.upload && this.$refs.upload.clearFiles()
      }
    },
    submit() {
      this.$emit('update:siglepic', this.files[0])
      this.closeForm()
    },
    closeForm() {
      this.$emit('update:show', false)
    },
  },
}
</script>

<style lang="scss" scoped>
.image-wrap {
  position: relative;
  display: inline-block;
  box-sizing: content-box;
  margin: 10px 15px 0 0;
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

label {
  font-size: 25px;
  cursor: pointer;
  position: absolute;
  top: -2px;
  right: 0px;
  z-index: 150;
}

label i {
  font-size: 15px;
  font-style: normal;
  display: inline-block;
  width: 18px;
  height: 18px;
  text-align: center;
  line-height: 18px;
  color: #fff;
  vertical-align: middle;
  margin: -3px 0px 1px 0px;
}

input[type="checkbox"],
input[type="radio"] {
  display: none;
  outline: none;
}

input[type="radio"] + i {
  border-radius: 7px;
}

input[type="checkbox"]:checked + i,
input[type="radio"]:checked + i {
  background: #67c23a;
  color: #FFF;
}

@import '@/assets/scss/common.scss'
</style>
