<template>

  <div>

    <vxe-modal
        v-model="show" id="add" width="60%" :size="size" :position="{top: 100}"
        @close="closeForm()" show-zoom="" resize="" transfer="" show-footer="">
      <!-- storage 将窗口拖动的状态保存到本地 remember 再次打开窗口时还原窗口状态-->

      <!--标题-->
      <template #title>

        <span style="font-weight: bold;">修改信息</span>

      </template>

      <!--主体-->
      <template #default>
        <el-form :size="size" ref="form" :model="form" class="form" :rules="rules" label-width="100px">
          <el-form-item label="应用名" prop="application_name">
            <el-input v-model="form.application_name" placeholder="请输入应用名称"/>
            <span class="form-desc">此名称为后端管理界面中的应用名称</span>
          </el-form-item>
          <el-form-item label="应用目录名" prop="app_dir">
            <el-input v-model="form.app_dir" placeholder="请输入应用目录名"/>
            <span class="form-desc">此名称为URL的虚拟目录名称</span>
          </el-form-item>
          <el-form-item label="访问域名" prop="domain">
            <el-input v-model="form.domain" placeholder="请输入访问域名"/>
            <span class="form-desc">输入实际开发或者部署的地址;比如开发时监听8000端口：http://127.0.0.1:8000/api</span>
          </el-form-item>
          <el-form-item label="生成状态" prop="status">
            <el-radio v-model="form.status" :label="1">启用</el-radio>
            <el-radio v-model="form.status" :label="0">禁用</el-radio>
            <span class="form-desc">是否允许生成应用代码</span>
          </el-form-item>
        </el-form>
      </template>

      <!--底部-->
      <template #footer>

        <el-button :size="size" :loading="loading" type="primary" @click="submit" >

          <span v-if="!loading">确 定</span>

          <span v-else>提 交 中...</span>

        </el-button>

        <el-button :size="size" @click="closeForm">取 消</el-button>

      </template>

    </vxe-modal>

  </div>

</template>

<script>
import {updateApplication,getTableList} from '../sys'
    export default {
        props: {
            show: {
                type: Boolean,
                default: false
            },
            size: {
                type: String,
                default: false
            },
            info: {
                type: Object,
                default: false
            }
        },
        data() {
            return {
                form: {
                    status: 1,
                    app_type: 2
                },
                loading:false,
                tableList:[],
                rules: {
                    application_name: [{ required: true, message: '请输入应用名', trigger: 'blur' }],
                    app_dir: [{ required: true, message: '请输入应用目录名', trigger: 'blur' }],
                },
            }
        },
        watch: {
            show() {
              console.log(this.info)
                this.form = this.info
                getTableList().then(res => {
                    this.tableList = res.data
                })
            }
        },
        methods: {
            submit(){
                this.$refs['form'].validate(valid => {
                    if (valid) {
                        this.loading = true
                        updateApplication(this.form).then(res => {
                            if(res.status == '200'){
                                this.$message({message: '操作成功', type: 'success'})
                                this.$emit('refesh_list',res.data)
                                this.closeForm()
                            }else{
                                this.loading = false
                            }
                        }).catch(()=>{
                            this.loading = false
                        })
                    }
                })
            },
            closeForm(){
               this.$emit('update:show', false)
               this.loading = false
               this.$refs['form'].resetFields()
            }
        },
    }
</script>
