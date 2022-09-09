<template>
  <div>

    <vxe-modal
        v-model="show" id="add" width="60%" :size="size" :position="{top: 100}"
        @close="closeForm()" @show="open" show-zoom="" resize="" transfer="" show-footer="">
      <!-- storage 将窗口拖动的状态保存到本地 remember 再次打开窗口时还原窗口状态-->

      <!--标题-->
      <template #title>

        <span style="font-weight: bold;">添加菜单</span>

      </template>

      <!--主体-->
      <template #default>

        <el-form :size="size" ref="form" :model="form" class="form" :rules="rules" label-width="90px">

          <el-row>

            <el-form-item label="所属父类" prop="pid">

              <Treeselect :class="size" :default-expand-level="1" v-model="form.pid" :options="list" :normalizer="normalizer" :show-count="true" placeholder="选择上级菜单"/>

              <span class="form-desc">选择上级菜单，如果是最顶级，则不需要选择</span>

            </el-form-item>

          </el-row>

          <el-row>

            <el-col :span="12">

              <el-form-item label="菜单名称" prop="title">

                <el-input v-model="form.title" clearable placeholder="菜单名称"/>
                <span class="form-desc">菜单列表和侧栏中显示的名称</span>

              </el-form-item>

            </el-col>

            <el-col :span="12">

              <el-form-item label="控制器名称" prop="controller_name">

                <el-input v-model="form.controller_name" clearable @input="setComponent" placeholder="同控制器生成地址"/>
                <span class="form-desc">控制器的生成地址，比如Raise/Employee,表示在目录Raise目录下</span>

              </el-form-item>

            </el-col>

          </el-row>

          <el-row>

            <el-col :span="24">

              <el-form-item label="生成代码" prop="create_code">

                <el-radio-group v-model="form.create_code">

                  <el-radio :label="1">是</el-radio>

                  <el-radio :label="0">否</el-radio>

                </el-radio-group>
                <span class="form-desc">是否由系统自动生成前后端代码</span>

              </el-form-item>

            </el-col>

          </el-row>

          <el-row>

            <el-col :span="12">

              <el-form-item label="数据表名" prop="table_name">

                <el-input v-model="form.table_name" clearable placeholder="生成或者关联的数据表"/>
                <span class="form-desc">数据库表名，推荐按照系统默认的表名创建表</span>

              </el-form-item>

            </el-col>

            <el-col :span="12">

              <el-form-item label="主键ID" prop="pk">

                <el-input v-model="form.pk" clearable placeholder="数据表主键"/>
                <span class="form-desc">数据库表的主键，推荐按照系统默认的主键创建表</span>

              </el-form-item>

            </el-col>

          </el-row>

          <el-row>

            <el-col :span="12">

              <el-form-item label="创建数据表" prop="create_table">

                <el-radio v-model="form.create_table" :label="1" checked>是</el-radio>

                <el-radio v-model="form.create_table" :label="0">否</el-radio>

                <span class="form-desc">是否为此菜单对应的模型创建数据库表</span>

              </el-form-item>

            </el-col>

            <el-col :span="12">

              <el-form-item label="选择链接库" prop="connect">

                <el-select style="width:100%" v-model="form.connect" filterable placeholder="请选择数据表">

                  <el-option v-for="item in connects" :key="item" :label="item" :value="item"></el-option>

                </el-select>

                <span class="form-desc">选择创建数据库表的数据库链接，不同的链接对应不同的服务器</span>

              </el-form-item>

            </el-col>

          </el-row>

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

  </div>

</template>

<script>
import {getAppInfo, updateMenu} from '../../sys'
import Treeselect from "@riophae/vue-treeselect"
import "@riophae/vue-treeselect/dist/vue-treeselect.css"

export default {
  name: 'updateMenu',
  components: {Treeselect},
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
        upload_config_id: null,
        home_show: 0,
        menu_pic: null,
        home_sort: null,
        is_post: 0,
        app_id: '',
      },
      app_name: '',
      loading: false,
      rules: {
        title: [{required: true, message: '菜单名称不能为空', trigger: 'blur'}],
      },
    }
  },
  methods: {
    submit() {
      this.$refs['form'].validate(valid => {
        if (valid) {
          if (!this.form.pid) this.form.pid = 0;
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
      if (this.info.pid == '0') {
        this.$delete(this.info, 'pid')
      }
      this.form = this.info
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
    setComponent(val) {
      if(this.app_name && val){
        this.form.component_path = this.app_name + '/' + val.toLowerCase() + '/index.vue'
      }
      this.form.table_name = val.replace(/\//g, '_').toLowerCase()
      let table_name_array = this.form.table_name.split('_');
      this.form.pk = table_name_array[table_name_array.length - 1] + '_id'
    },
    closeForm() {
      this.$emit('update:show', false)
      this.loading = false
      this.$refs['form'].resetFields()
    }
  },
}
</script>
