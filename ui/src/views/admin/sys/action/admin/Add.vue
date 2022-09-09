<template>
  <div>
    <vxe-modal
        v-model="show" id="add" width="60%" height="80vh" :size="size" :position="{top: '10%'}"
        @close="closeForm()" @show="open" show-zoom="" resize="" transfer="" show-footer="">
      <!-- storage 将窗口拖动的状态保存到本地 remember 再次打开窗口时还原窗口状态-->
      <!--标题-->
      <template #title>
        <span style="font-weight: bold;">{{ menu.title }} - 创建&添加</span>
      </template>
      <!--主体-->
      <template #default>
        <el-form :size="size" ref="form" :model="form" class="form" :rules="rules" label-width="90px">
          <el-tabs v-model="activeName" type="card">
            <el-tab-pane label="基本信息" name="基本信息">
              <el-row>
                <el-form-item label="方法类型" prop="type">
                  <el-select style="width:100%" v-model="form.type" @change="selectType" filterable placeholder="请选择">
                    <el-option v-for="(item,i) in action" :key="i" :label="item.name" :value="item.type"></el-option>
                  </el-select>
                  <span class="form-desc">内置预定义的方法列表</span>
                </el-form-item>
              </el-row>
              <el-row>
                <el-col :span="12">
                  <el-form-item label="方法名称" prop="name">
                    <el-input v-model="form.name" clearable placeholder="方法中文名称"/>
                    <span class="form-desc">方法名称</span>
                  </el-form-item>
                </el-col>
                <el-col :span="12">
                  <el-form-item label="英文名称" prop="action_name">
                    <el-input v-model="form.action_name" clearable placeholder="方法英文名称"/>
                    <span class="form-desc">方法函数名</span>
                  </el-form-item>
                </el-col>
              </el-row>
              <el-row v-if="form.type == 15 || form.type == 16">
                <el-col :span="24">
                  <el-form-item label="跳转地址" prop="jump">
                    <div>
                      <el-input v-model="form.jump" clearable placeholder="跳转地址" style="width: 50%;"/>
                      <el-select v-model="form.jump" placeholder="请选择">
                        <el-option v-for="item in url" :key="item.value" :label="item.title" :value="item.component_path"></el-option>
                      </el-select>
                    </div>
                    <span class="form-desc">点击此方法自动创建新的标签，并打开此页面</span>
                  </el-form-item>
                </el-col>
              </el-row>
              <el-row v-if="dialog">
                <el-form-item :label="form.type ==5 ? '显示字段' : '操作字段'" prop="fields">
                  <el-checkbox-group v-model="form.fields">
                    <el-checkbox v-if="defaultFields.indexOf(item.field) == -1" v-for="item in post_fields" :label="item.field" :key="item.field">{{ item.title }}</el-checkbox>
                  </el-checkbox-group>
                  <span class="form-desc">是否操作指定字段</span>
                </el-form-item>
              </el-row>

              <el-row v-if="form.type == 7">
                <el-form-item label="状态值" prop="status_val">
                  <el-input v-model="form.status_val" placeholder="状态值"/>
                  <span class="form-desc">设置指定值</span>
                </el-form-item>
              </el-row>

              <el-row v-if="button">
                <el-form-item label="按钮图标" prop="icon">
                  <el-input v-model="form.icon" placeholder="点击选择图标" clearable>
                    <el-button type="success" slot="append" icon="el-icon-thumb" :size="size" style="height: 20px;" @click="iconOpen('icon')">请选择</el-button>
                  </el-input>
                  <span class="form-desc">按钮中显示的图标</span>
                </el-form-item>
              </el-row>

              <el-row v-if="button">
                <el-form-item label="按钮颜色" prop="button_color">
                  <div>
                    <el-select style="width:100%" v-model="form.button_color" :size="size" clearable filterable placeholder="请选择">
                      <el-option key="1" style="background:#409eff" label="primary" value="primary"></el-option>
                      <el-option key="2" style="background:#67c23a" label="success" value="success"></el-option>
                      <el-option key="3" style="background:#909399" label="info" value="info"></el-option>
                      <el-option key="4" style="background:#e6a23c" label="warning" value="warning"></el-option>
                      <el-option key="5" style="background:#f56c6c" label="danger" value="danger"></el-option>
                    </el-select>
                  </div>
                  <span class="form-desc">按钮颜色参照ElementUI内置颜色方案</span>
                </el-form-item>

              </el-row>

              <el-row v-if="form.type == 1">
                <el-col :span="12">
                  <el-form-item label="分页大小" prop="type">
                    <div>
                      <el-select v-model="form.pagesize" placeholder="请选择">
                        <el-option key="1" label="10条每页" value="10"></el-option>
                        <el-option key="2" label="20条每页" value="20"></el-option>
                        <el-option key="3" label="50条每页" value="50"></el-option>
                        <el-option key="4" label="100条每页" value="100"></el-option>
                        <el-option key="5" label="200条每页" value="200"></el-option>
                      </el-select>
                    </div>
                    <span class="form-desc">生成的表格默认分页大小</span>
                  </el-form-item>
                </el-col>

                <el-col :span="12">
                  <el-form-item label="排序" prop="orderby">
                    <el-input v-model="form.orderby" placeholder="如 id desc"/>
                    <span class="form-desc">查询默认的排序规则</span>
                  </el-form-item>
                </el-col>

              </el-row>

              <el-row v-if="form.type == 1">
                <el-col :span="24">
                  <el-form-item label="树table配置" prop="tree_config">
                    <el-input v-model="form.tree_config" placeholder="父类字段名"/>
                    <span class="form-desc">树表的配置格式为：【pid,name】；第一个是父类属性名，第二个是指定列进行层级折叠</span>
                  </el-form-item>
                </el-col>

              </el-row>

              <el-row v-if="form.type == 1">
                <el-col :span="12">
                  <el-form-item label="选中方式" prop="select_type">
                    <el-radio-group v-model="form.select_type">
                      <el-radio :label="3">不需要</el-radio>
                      <el-radio :label="1">多选</el-radio>
                      <el-radio :label="2">单选</el-radio>
                    </el-radio-group>
                    <span class="form-desc">表格默认是单选(显示单选框)还是多选(显示多选框)</span>
                  </el-form-item>
                </el-col>
                <el-col :span="12">
                  <el-form-item label="操作列宽度" prop="action_width">
                    <el-input v-model="form.action_width" placeholder="操作列宽度"/>
                    <span class="form-desc">表格最右侧操作列宽度</span>
                  </el-form-item>
                </el-col>
              </el-row>
              <el-row v-if="form.type == 1">
                <el-form-item label="过滤条件" prop="list_filter">
                  <div>
                    <el-row v-for="(item,i) in form.list_filter" :key="i">
                      <el-col :span="12">
                        <el-form-item class="activeItem">
                          <el-select style="width:100%" v-model="item.searchField" filterable placeholder="请选择字段">
                            <el-option v-for="(vo,i) in post_fields" :key="i" :value="vo.field">{{ vo.title }}({{ vo.field }})</el-option>
                          </el-select>
                        </el-form-item>
                      </el-col>
                      <el-col :span="6">
                        <el-form-item class="activeItem">
                          <el-input style="position:relative; left:10px" v-model="item.serachVal" placeholder="值"/>
                        </el-form-item>
                      </el-col>
                      <el-col :span="6">
                        <el-button type="danger" :size="size" style="position:relative;left:15px" icon="el-icon-close" @click="deleteItem('list_filter',i)"></el-button>
                      </el-col>
                    </el-row>
                    <el-button type="success" icon="el-icon-plus" style="padding:5px 7px" :size="size" @click="addItem('list_filter')">追加</el-button>
                    <el-button v-if="form.list_filter.length > 0" type="warning" icon="el-icon-delete" style="padding:5px 7px" :size="size" @click="clearItem('list_filter')">清空</el-button>
                  </div>
                  <span class="form-desc">附加的查询条件，只能进行指定指定过滤</span>
                </el-form-item>
              </el-row>

              <el-row v-if="form.type == 2 || form.type ==3 || form.type == 14">
                <el-form-item label="选项卡配置" prop="tab_config">
                  <div>
                    <el-row v-for="(item,i) in form.tab_config" :key="i">
                      <el-col :span="8">
                        <el-form-item class="activeItem">
                          <el-input clearable v-model="item.tab_name" placeholder="选项卡名称"/>
                        </el-form-item>
                      </el-col>
                      <el-col :span="12">
                        <el-form-item class="activeItem">
                          <el-select style="width:100%;position:relative; left:10px" clearable v-model="item.tab_fields" multiple collapse-tags placeholder="包含字段">
                            <el-option v-for="(vo,i) in tab_fields" :key="i" :value="vo.field">{{ vo.title }}({{ vo.field }})</el-option>
                          </el-select>
                        </el-form-item>
                      </el-col>
                      <el-col :span="2">
                        <el-button type="danger" :size="size" style="position:relative;left:15px" icon="el-icon-close" @click="deleteItem('tab_config',i)"></el-button>
                      </el-col>
                    </el-row>
                    <el-button type="success" icon="el-icon-plus" style="padding:5px 7px" :size="size" @click="addItem('tab_config')">追加</el-button>
                    <el-button v-if="form.tab_config.length > 0" type="warning" icon="el-icon-delete" style="padding:5px 7px" :size="size" @click="clearItem('tab_config')">清空</el-button>
                  </div>
                  <span class="form-desc">为添加，修改，配置表单设置选项卡</span>
                </el-form-item>
              </el-row>

              <el-row v-if="form.type==1">
                <el-form-item label="侧栏列表sql" prop="left_tree_sql">
                  <el-input v-model="form.left_tree_sql" placeholder="通过sql语句生成table侧栏列表"/>
                  <span class="form-desc">侧栏列表或者树表的SQL查询语句</span>
                </el-form-item>
              </el-row>
            </el-tab-pane>
            <!-- 多表关联 -->
            <el-tab-pane v-if="[1,2,3,4,5,11].includes(form.type)" style="padding-top:10px" label="多表配置" name="多表配置">
              <el-form-item label="关联模型" prop="with_join">
                <div>
                  <el-row :gutter="2" v-for="(item,index) in form.with_join" :key="index">
                    <el-col style="margin-left:0px" :span="5">
                      <el-form-item class="activeItem" prop="fk">
                        <el-select style="width:100%" v-model="item.fk" filterable placeholder="主表外键">
                          <el-option v-for="(vo,i) in model_fields" :key="i" :value="vo.field"> {{ vo.title }}({{ vo.field }})</el-option>
                        </el-select>
                      </el-form-item>
                    </el-col>
                    <el-col :span="5">
                      <el-form-item class="activeItem" prop="relative_table">
                        <el-select style="width:100%" v-model="item.relative_table" filterable placeholder="模型">
                          <el-option v-for="(vo,i) in tableList" :key="i" :value="vo.controller_name">{{ vo.controller_name }}</el-option>
                        </el-select>
                      </el-form-item>
                    </el-col>
                    <el-col :span="5">
                      <el-form-item class="activeItem" prop="pk">
                        <el-select @focus="getTableFields(index)" style="width:100%" v-model="item.pk" filterable placeholder="关联键">
                          <el-option v-for="(vo,i) in table_fields" :key="i" :value="vo.Field">{{ vo.Field }}({{ vo.Comment }})</el-option>
                        </el-select>
                      </el-form-item>
                    </el-col>
                    <el-col :span="7">
                      <el-form-item class="activeItem" prop="fields">
                        <el-select @focus="getTableFields(index)" style="width:100%" multiple collapse-tags v-model="item.fields" filterable :placeholder="form.type == 1 ? '关联表查询字段':'操作字段'">
                          <el-option v-for="(vo,i) in table_fields" :key="i" :value="vo.Field"> {{ vo.Field }}({{ vo.Comment }})</el-option>
                        </el-select>
                      </el-form-item>
                    </el-col>
                    <el-col :span="1">
                      <el-button type="danger" :size="size" style="position:relative;left:5px" icon="el-icon-close" @click="deleteItem('with_join',i)"></el-button>
                    </el-col>
                  </el-row>
                  <el-button type="success" icon="el-icon-plus" style="padding:5px 7px" :size="size" @click="addItem('with_join')">追加</el-button>
                  <el-button v-if="form.tab_config.length > 0" type="warning" icon="el-icon-delete" style="padding:5px 7px" :size="size" @click="clearItem('with_join')">清空</el-button>
                </div>
                <span class="form-desc">定义本表中的外键字段，并定义其与那个表的那个列关联</span>
              </el-form-item>

              <el-row v-if="form.type==1">
                <el-form-item label="table列表sql" prop="sql">
                  <el-input v-model="form.sql" type="textarea" placeholder="通过sql语句生成table列表"/>
                  <span class="form-desc">表格数据对应查询的原生SQL定义</span>
                </el-form-item>
              </el-row>
            </el-tab-pane>
          </el-tabs>
        </el-form>
      </template>
      <!--底部-->
      <template #footer>
        <el-button :size="size" :loading="loading" type="primary" @click="submit">
          <span v-if="!loading">确 定</span><span v-else>提 交 中...</span>
        </el-button>
        <el-button :size="size" @click="closeForm">取 消</el-button>
      </template>
    </vxe-modal>
    <div>
      <Icon :iconshow.sync="iconDialogStatus" :currentIconModel.sync="currentIconModel" @selectIcon="selectIcon" :size="size"></Icon>
    </div>
  </div>
</template>

<script>
import {getPostField, getTableFields, createAction, getUrl, updateAction, deleteAction} from '../../sys'
import Icon from '@/components/common/Icon.vue'

export default {
  name: 'AddAction',
  components: {Icon},
  props: {
    show: {
      type: Boolean,
      default: false
    },
    size: {
      type: String,
      default: 'small'
    },
    action: {
      type: Array,
    },
    info: {
      type: Object,
    },
    menu: {
      Object,
    }
  },
  data() {
    return {
      defaultFields: ['sort_id','status','creater_id','create_time','update_time'],
      form: {
        pk: null,
        server_create_status: 1,
        vue_create_status: 1,
        button_color: 'primary',
        select_type: null,
        fields: [],
        list_filter: [],
        tab_config: [],
        with_join: [
          {
            fields: '',
            fk: "",
            relative_table: "",
            pk: "",
            table_name: "",
            connect: "",
          }
        ],
        relative_table: '',
        status_val: '',
        orderby: '',
        left_tree_sql: '',
        action_width: 80,
        tab_name: '',
        table_height: '',
        tree_config: '',
        icon: '',
        name: '',
        action_name: '',
        dialog_size: '600px',
        pagesize: '20',
        sql: '',
      },
      iconDialogStatus: false,
      currentIconModel: '',
      post_fields: [],
      tableList: [],
      dialog: true,
      button: true,
      loading: false,
      ischeck_fields: [],
      activeName: '基本信息',
      table_fields: [],
      tab_fields: [],
      model_fields: [],
      url: [],
      rules: {
        name: [{required: true, message: '方法中文名不能为空', trigger: 'blur'}],
        action_name: [{required: true, message: '方法英文名不能为空', trigger: 'blur'}],
        type: [{required: true, message: '方法类型不能为空', trigger: 'blur'}],
      },
    }
  },
  methods: {
    submit() {
      this.$refs['form'].validate(valid => {
        if (valid) {
          this.loading = true
          createAction(this.form).then(res => {
            if (res.status == '200') {
              this.$message({message: '操作成功', type: 'success'})
              this.loading = false
              this.$emit('refesh_list', res.data)
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
      this.form.menu_id = this.menu.menu_id
      this.setDefaultVal('list_filter')
      this.setDefaultVal('tab_config')
      this.setDefaultVal('fields')
      this.getPostField()
      getUrl().then(res => {
        this.url = res.data
      })
    },
    getPostField() {
      getPostField({menu_id: this.menu.menu_id}).then(res => {
        this.post_fields = res.data
        this.model_fields = res.model_fields
        this.tab_fields = res.tab_fields
        this.tableList = res.tableList
      })
    },
    addItem(key) {
      // 如果添加了多表配置，则需要添加选项卡前，保存方法
      if (key == 'tab_config' && this.form.with_join.length > 0) {
        this.$MyUtils.confirm({content: '方法保存以后才能设置选项，您现在就保存方法吗？'}).then(() => {
          this.submit()
        })
      } else {
        this.setDefaultVal(key)
        this.form[key].push({})
      }
    },
    deleteItem(key, index) {
      this.form[key].splice(index, 1)
    },
    clearItem(key) {
      this.form[key] = []
    },
    iconOpen(model) {
      this.iconDialogStatus = true
      this.currentIconModel = model
    },
    selectIcon(icon) {
      this.form[this.currentIconModel] = icon
    },
    setDefaultVal(key) {
      if (this.form[key] == null || this.form[key] == '') {
        this.form[key] = []
      }
    },
    selectType(val) {
      if (val !== 1) {
        this.form.list_filter = []
        this.form.with_join = []
      }
      if (val !== 3 || val !== 4) {
        this.form.tab_config = []
      }
      if (val !== 7) {
        this.form.dialog_size = ''
      }
      this.action.forEach(item => {
        if (this.form.type == item.type) {
          this.dialog = item.dialog
          this.button = item.button
          this.form.icon = item.icon
          this.form.button_color = item.button_color
          this.form.name = item.name
          this.form.action_name = item.action_name
        }
      })
    },
    getTableFields(i) {
      getTableFields({controller_name: this.form.with_join[i].relative_table}).then(res => {
        this.table_fields = res.filedList
      })
    },
    closeForm() {
      this.$emit('update:show', false)
      this.loading = false
      this.$nextTick(() => {
        this.form.dialog_size = ''
        this.form.icon = ''
        this.form.sql = ''
      })
      this.$refs['form'].resetFields()
    }
  },
}
</script>
<style scoped>
.el-checkbox-group li {
  float: left;
  margin-left: 10px;
  width: 150px;
  margin-top: 10px
}

.activeItem {
  margin-bottom: 3px
}
</style>
