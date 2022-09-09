<template>
  <div>
    <vxe-modal
        v-model="show" id="add" width="60%" height="80vh" :size="size" :position="{top: '10%'}"
        @close="closeForm()" @show="open" show-zoom="" resize="" transfer="" show-footer="">
      <!-- storage 将窗口拖动的状态保存到本地 remember 再次打开窗口时还原窗口状态-->
      <!--标题-->
      <template #title>
        <span style="font-weight: bold;">编辑&保存</span>
      </template>
      <!--主体-->
      <template #default>
        <el-form :size="size" ref="form" :model="form" class="form" :rules="rules" label-width="90px">
          <div class="card">
            <el-row>
              <el-col :span="24">
                <el-form-item label="上级菜单" prop="pid">
                  <Treeselect :class="size" :default-expand-level="2" v-model="form.pid" :options="list" :normalizer="normalizer" :show-count="true" placeholder="选择上级菜单"/>
                  <span class="form-desc">选择上级菜单，如果是最顶级，则不需要选择</span>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="12">
                <el-form-item label="菜单名称" prop="title">
                  <el-input v-model="form.title" clearable placeholder="菜单名称"/>
                  <span class="form-desc">菜单列表和侧栏中显示的名称</span>
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item label="访问路径" prop="controller_name">
                  <el-input v-model="form.controller_name" clearable @input="setComponent" placeholder="同控制器生成地址"/>
                  <span class="form-desc">控制器的生成地址，比如Raise/Employee,表示在目录Raise目录下</span>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="24">
                <el-form-item label="组件路径" prop="component_path">
                  <el-input v-model="form.component_path" clearable placeholder="vue组件生成路径"/>
                  <span class="form-desc">前端组件的路径，系统将自动按照此路径创建前端组件</span>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="12">
                <el-form-item label="菜单状态" prop="status">
                  <div>
                    <el-radio v-model="form.status" :label="1">显示</el-radio>
                    <el-radio v-model="form.status" :label="0">隐藏</el-radio>
                  </div>
                  <span class="form-desc">是否在菜单栏中显示此菜单</span>
                </el-form-item>
              </el-col>
              <el-col :span="12"  v-if="form.status == 1">
                <el-form-item label="菜单图标" prop="icon">
                  <el-input v-model="form.icon" placeholder="点击选择图标" clearable>
                    <el-button type="success" slot="append" size="mini" icon="el-icon-thumb" style="height: 20px;" @click="iconOpen('icon')">请选择</el-button>
                  </el-input>
                  <span class="form-desc">菜单栏中菜单名称前面的图标</span>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="12">
                <el-form-item label="生成代码" prop="create_code">
                  <div>
                    <el-radio-group v-model="form.create_code" @change="changeCode">
                      <el-radio :label="1">是</el-radio>
                      <el-radio :label="0">否</el-radio>
                    </el-radio-group>
                  </div>
                  <span class="form-desc">是否由系统自动生成前后端代码</span>
                </el-form-item>
              </el-col>
              <el-col :span="12" v-if="form.create_code == 1">
                <el-form-item label="页面结构" prop="page_type">
                  <el-select style="width:100%" v-model="form.page_type" filterable placeholder="请选择页面显示结构">
                    <el-option v-for="item in page_type_list" :key="item.type" :label="item.name" :value="item.type"></el-option>
                  </el-select>
                  <span class="form-desc">页面模板分为表格模板和表单模板</span>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="12">
                <el-form-item label="创建数据表" prop="create_table">
                  <el-radio-group v-model="form.create_table">
                    <el-radio :label="1">是</el-radio>
                    <el-radio :label="0">否</el-radio>
                  </el-radio-group>
                  <span class="form-desc">是否为此菜单对应的模型创建数据库表</span>
                </el-form-item>
              </el-col>
              <el-col :span="12" v-if="form.create_code == 1">
                <el-form-item label="选择链接库" prop="connect">
                  <el-select style="width:100%" v-model="form.connect" filterable placeholder="请选择数据表">
                    <el-option v-for="item in connects" :key="item" :label="item" :value="item"></el-option>
                  </el-select>
                  <span class="form-desc">选择创建数据库表的数据库链接，不同的链接对应不同的服务器</span>
                </el-form-item>
              </el-col>
              <el-col :span="12" v-if="form.create_code == 1">
                <el-form-item label="数据表名" prop="table_name">
                  <el-input v-model="form.table_name" clearable placeholder="生成或者关联的数据表"/>
                  <span class="form-desc">数据库表名，推荐按照系统默认的表名创建表</span>
                </el-form-item>
              </el-col>
              <el-col :span="12" v-if="form.create_code == 1">
                <el-form-item label="主键ID" prop="pk">
                  <el-input v-model="form.pk" clearable placeholder="数据表主键"/>
                  <span class="form-desc">数据库表的主键，推荐按照系统默认的主键创建表</span>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row v-if="form.status == 1 ">
              <el-col :span="12">
                <el-form-item label="首页导航" prop="home_show">
                  <div>
                    <el-radio v-model="form.home_show" :label="1">显示</el-radio>
                    <el-radio v-model="form.home_show" :label="0">隐藏</el-radio>
                  </div>
                  <span class="form-desc">是否将此菜单显示在首页中</span>
                </el-form-item>
              </el-col>
              <el-col :span="12" v-if="form.home_show == 1">
                <el-form-item label="导航排序" prop="menu_pic">
                  <el-input v-model="form.home_sort" clearable placeholder="排序编号"/>
                  <span class="form-desc">如果显示在首页中，则可以设置显示的顺序</span>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row v-if="form.status == 1 ">
              <el-col :span="24" v-if="form.home_show == 1">
                <el-form-item label="导航图标" prop="menu_pic">
                  <ImagesUpload size="small" uploadType="2" fileType="image" :image.sync="form.menu_pic"></ImagesUpload>
                  <span class="form-desc">首页导航图标，建议使用标准图标</span>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="24">
                <el-form-item label="上传配置" prop="upload_config_id">
                  <el-select style="width:100%" v-model="form.upload_config_id" filterable placeholder="请选择">
                    <el-option v-for="(item,i) in upload_list" :key="i" :label="item.title" :value="item.id"></el-option>
                  </el-select>
                  <span class="form-desc">选择设置此菜单对应的模型的上传配置</span>
                </el-form-item>
              </el-col>
            </el-row>
          </div>
        </el-form>
      </template>
      <!--底部-->
      <template #footer>
        <el-button :size="size" :loading="loading" type="primary" @click="submit">
          <span v-if="!loading">确 定</span>
          <span v-else>提 交 中...</span>
        </el-button>
        <el-button :size="size" @click="closeForm">取 消</el-button>
      </template>
    </vxe-modal>
    <Icon :iconshow.sync="iconDialogStatus" :currentIconModel.sync="currentIconModel" @selectIcon="selectIcon" size="small"></Icon>
  </div>
</template>

<script>
import {updateMenu, getAppInfo, getUploadList} from '../../sys'
import ImagesUpload from '@/components/common/ImagesUpload.vue'
import Treeselect from "@riophae/vue-treeselect"
import "@riophae/vue-treeselect/dist/vue-treeselect.css"
import Icon from '@/components/common/Icon.vue'

export default {
  name: 'updateMenu',
  components: {Treeselect, Icon, ImagesUpload},
  props: {
    show: {
      type: Boolean,
      default: false
    },
    size: {
      type: String,
      default: 'small'
    },
    info: {
      type: Object,
    },
    list: {
      type: Array,
    },
    connects: {
      type: Array,
    },
    page_type_list: {
      type: Array,
    }
  },
  data() {
    return {
      form: {
        pid: null,
        title: null,
        controller_name: null,
        create_code: 1,
        status: 1,
        component_path: null,
        table_name: null,
        pk: null,
        create_table: 1,
        connect: 'mysql',
        page_type: 1,
        icon: null,
        upload_config_id: 1,
        home_show: 0,
        menu_pic: null,
        home_sort: null,
        is_post: 0,
        app_id: '',
      },
      app_name: '',
      app_type: '',
      iconDialogStatus: false,
      currentIconModel: '',
      loading: false,
      upload_list: [],
      rules: {
        title: [{required: true, message: '菜单名称不能为空', trigger: 'blur'}],
      },
    }
  },
  methods: {
    submit() {
      this.$refs['form'].validate(valid => {
        if (valid) {
          /**
           * 父菜单目录不选择，应该是没有父目录的
           */
          if (!this.form.pid) this.form.pid = 0;
          if(!this.form.table_name) this.form.page_type = null;
          if(this.form.create_code && this.form.page_type == null){
            this.$message({message: '请选择页面类型', type: 'error'})
            this.loading = false
            return
          }
          this.loading = true
          updateMenu(this.form).then(res => {
            if (res.status == '200') {
              this.$message({message: '操作成功', type: 'success'})
              this.$emit('refesh_list')
              this.closeForm()
            } else {
              this.loading = false
            }
          }).catch(() => {
            this.loading = false
          })
        }
      })
    },
    open() {
      if (!this.info.pid && this.info.pid == '0') {
        this.$delete(this.info, 'pid')
      }
      this.form = this.info
      getUploadList().then(res => {
        if (res.status == 200) {
          this.upload_list = res.data
        }
      })
      getAppInfo().then(res=>{
        this.app_name = res.app_name;
      })
    },
    /** 转换菜单数据结构 */
    normalizer(node) {
      if (node.children && !node.children.length) {
        delete node.children;
      }
      return {
        id: node.menu_id,
        label: node.title,
        children: node.children
      };
    },
    iconOpen(model) {
      this.iconDialogStatus = true
      this.currentIconModel = model
    },
    selectIcon(icon) {
      this.form[this.currentIconModel] = icon
    },
    setComponent(val) {
      if(this.form.create_code == '1'){
        if(this.app_name && val){
          this.form.component_path = this.app_name + '/' + val.toLowerCase() + '/index.vue'
        }
        this.form.table_name = val.replace(/\//g, '_').toLowerCase()
        let table_name_array = this.form.table_name.split('_');
        this.form.pk = table_name_array[table_name_array.length - 1] + '_id'
      }
    },
    changeCode(val) {
      if (!val) {
        this.form.component_path = ''
        this.form.create_table = 0
        this.form.pk = ''
        this.form.table_name = ''
        this.disableCreateTable = true
      } else {
        this.form.create_table = 1
        this.disableCreateTable = false
        this.setComponent(this.form.controller_name)
      }
    },
    closeForm() {
      this.$emit('update:show', false)
      this.loading = false
      this.$refs['form'].resetFields()
    }
  },
}
</script>
<style lang="scss">
@import '@/assets/scss/common.scss';
</style>
