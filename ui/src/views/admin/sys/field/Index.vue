<template>
  <div>
    <!-- 主要操作区域以card样式出现 -->
    <el-card shadow="always">
      <div slot="header" class="clearfix">
        <span style="font-size:14px;font-weight: bold">{{ menu.title }} 字段列表</span>
        <el-button :size="formSize" @click="jump" type="primary" icon="el-icon-back" style="float:right">返回</el-button>
      </div>
      <!-- 主要操作区域 -->
      <div class="main_area">
        <!-- 操作区域 -->
        <div class="action_area">
          <vxe-toolbar ref="xToolbar" :size="formSize" custom :refresh="{ query: index }">
            <template #buttons>
              <div>
                <el-button type="success" :size="formSize" icon="el-icon-plus" @click="addAdmin()" v-if="app_type == 1">新增</el-button>
                <el-button type="success" :size="formSize" icon="el-icon-plus" @click="addApi()" v-if="app_type == 2">新增</el-button>
                <el-button type="danger" :size="formSize" icon="el-icon-delete" :disabled="multiple" @click="deleteField(ids)">删除</el-button>
                <el-button type="info" :size="formSize" icon="el-icon-edit-outline" @click="create">生成代码</el-button>
                <el-button type="warning" :size="formSize" icon="el-icon-edit-outline" @click="exportExcel">导出</el-button>
              </div>
            </template>
          </vxe-toolbar>
        </div>
        <!-- 表格区域 -->
        <div class="table_area_tags">
          <vxe-table
              ref="xTable" id="xTable" border height="100%" :data="list" :loading="loading" :size="formSize"
              :keyboard-config="keyboardConfig" :custom-config="customConfig"
              :column-config="columnConfig" :row-config="rowConfig"
              :checkbox-config="checkboxConfig" :edit-config="editConfig"
              @checkbox-change="checkboxChange">
            <template>
              <vxe-column type="checkbox" width="40"></vxe-column>
              <vxe-column title="编号" field="id" width="80" sortable></vxe-column>
              <vxe-column title="字段标题" field="title" min-width="100" :edit-render="{}">
                <template #edit="{ row }">
                  <el-input v-model="row.title" type="text" :size="formSize" @change="updateFieldExt(row,'title')"></el-input>
                </template>
              </vxe-column>
              <vxe-column title="字段名称" field="field" min-width="100" :edit-render="{}">
                <template #edit="{ row }">
                  <el-input v-model="row.field" type="text" :size="formSize" @change="updateFieldExt(row,'field')"></el-input>
                </template>
              </vxe-column>
              <vxe-column title="字段类型" field="type" width="140" :edit-render="{}">
                <template #default="{ row }">
                  <el-select v-model="row.type" :size="formSize" @change="updateFieldExt(row,'type')" filterable placeholder="请选择">
                    <el-option v-for="(item,i) in field" :key="i" :label="item.name" :value="item.type"></el-option>
                  </el-select>
                </template>
              </vxe-column>

              <vxe-column title="搜索类型" field="search_type" width="120" :edit-render="{}">
                <template #default="{ row }">
                  <el-select v-model="row.search_type" @change="updateFieldExt(row,'search_type')" :size="formSize" placeholder="请选择">
                    <el-option-group label="是否搜索">
                      <el-option key="1" label="否" :value="0"></el-option>
                    </el-option-group>
                    <el-option-group label="搜索模式">
                      <el-option key="1" label="=" :value="1"></el-option>
                      <el-option key="2" label="like" :value="2"></el-option>
                    </el-option-group>
                  </el-select>
                </template>
              </vxe-column>

              <vxe-column title="数据类型" field="datatype" width="100"></vxe-column>
              <vxe-column title="显示状态" field="list_show" width="120" :edit-render="{}">
                <template #default="{ row }">
                  <template v-if="app_type == 1">
                    <el-select v-model="row.list_show" @change="updateFieldExt(row,'list_show')" :size="formSize" filterable placeholder="请选择">
                      <el-option-group label="显示状态">
                        <el-option key="2" label="不显示" :value="0"></el-option>
                        <el-option key="3" label="隐藏" :value="1"></el-option>
                      </el-option-group>
                      <el-option-group label="显示位置">
                        <el-option key="1" label="居中" :value="2"></el-option>
                        <el-option key="2" label="居左" :value="3"></el-option>
                        <el-option key="3" label="居右" :value="4"></el-option>
                      </el-option-group>
                    </el-select>
                  </template>
                  <template v-if="app_type == 2">
                    <el-switch v-model="row.list_show" @change="updateFieldExt(row,'list_show')" :size="formSize" :active-value="1" :inactive-value="0"></el-switch>
                  </template>
                </template>
              </vxe-column>
              <vxe-column title="创建字段" field="create_table_field" width="80" align="center">
                <template #default="{ row }">
                  <el-tag :size="formSize" :type="row.create_table_field=='1'?'primary':'info'" effect="dark">
                    {{ row.create_table_field == '1' ? '是' : '否' }}
                  </el-tag>
                </template>
              </vxe-column>
              <vxe-column title="录入状态" field="post_status" width="70" :cell-render="{}">
                <template #default="{ row }">
                  <el-switch v-model="row.post_status" :active-value="1" :inactive-value="0" @change="updateFieldExt(row,'post_status')"></el-switch>
                </template>
              </vxe-column>
              <template v-if="app_type == 1">
                <template v-if="menu.page_type != 2">
                  <vxe-column title="宽度" field="width" width="70" :edit-render="{}" :key="Math.random()">
                    <template #edit="{ row }">
                      <el-input v-model="row.width" type="text" :size="formSize" @change="updateFieldExt(row,'width')"></el-input>
                    </template>
                  </vxe-column>
                  <vxe-column title="可编辑" field="editable" width="70" :cell-render="{}" :key="Math.random()" align="center">
                    <template #default="{ row }">
                      <el-switch :active-value="1" :inactive-value="0" @change="updateFieldExt(row,'editable')" v-model="row.editable" :disabled="[1,2,3,4,5,6,8,9,17,29].indexOf(row.type) == -1"></el-switch>
                    </template>
                  </vxe-column>
                  <vxe-column title="可排序" field="sortable" width="70" :cell-render="{}" :key="Math.random()" align="center">
                    <template #default="{ row }">
                      <el-switch :active-value="1" :inactive-value="0" @change="updateFieldExt(row,'sortable')" v-model="row.sortable"></el-switch>
                    </template>
                  </vxe-column>
                  <vxe-column title="可筛选" field="is_filter" width="70" :cell-render="{}" :key="Math.random()" align="center">
                    <template #default="{ row }">
                      <el-switch :active-value="1" :inactive-value="0" @change="updateFieldExt(row,'is_filter')" v-model="row.is_filter"></el-switch>
                    </template>
                  </vxe-column>
                </template>
                <vxe-column title="字段说明" field="note" min-width="100" :visible="false" :edit-render="{}" :key="Math.random()" show-overflow>
                  <template #edit="{ row }">
                    <el-input v-model="row.note" type="text" :size="formSize"></el-input>
                  </template>
               </vxe-column>
                <vxe-column title="表单说明" field="desc" min-width="100" :visible="false" :edit-render="{}" :key="Math.random()" show-overflow>
                  <template #edit="{ row }">
                    <el-input v-model="row.desc" type="text" :size="formSize"></el-input>
                  </template>
                </vxe-column>
              </template>
              <vxe-column title="排序" field="sortid" width="70" :edit-render="{}">
                <template #edit="{ row }">
                  <el-input v-model="row.sortid" type="text" :size="formSize" @change="updateFieldExt(row,'sortid')"></el-input>
                </template>
              </vxe-column>
              <vxe-column title="操作" align="center" width="200" fixed="right" :key="Math.random()">
                <template #default="{ row }">
                  <el-button :size="formSize" type="primary"  @click="editorAdmin(row)" v-if="app_type == 1">
                    <i class="el-icon-edit-outline"/>修改
                  </el-button>
                  <el-button :size="formSize" type="primary"  @click="editorApi(row)" v-if="app_type == 2">
                    <i class="el-icon-edit-outline"/>修改
                  </el-button>
                  <el-button :size="formSize"  type="danger" @click="deleteField(row)">
                    <i class="el-icon-delete"/>删除
                  </el-button>
                </template>
              </vxe-column>
            </template>
          </vxe-table>
        </div>
        <!-- 分页区域 -->
        <div class="pagination_area">
          <vxe-pager
              :size="formSize" :loading="loading" :current-page="page_data.page"
              :page-size="page_data.limit" :total="page_data.total"
              :layouts="['PrevPage', 'JumpNumber', 'NextPage', 'FullJump', 'Sizes', 'Total']"
              @page-change="pageChange">
          </vxe-pager>
        </div>
      </div>
    </el-card>

    <!--添加-->
    <AdminAdd
        :show.sync="dialog.addAdminDialogStatus"
        :field="field"
        :itemList="itemList"
        :size="formSize"
        :menu="menu"
        @refesh_list="index"></AdminAdd>

    <ApiAdd
        :show.sync="dialog.addApiDialogStatus"
        :field="field"
        :itemList="itemList"
        :size="formSize"
        :menu="menu"
        @refesh_list="index"></ApiAdd>

    <!--修改-->
    <AdminUpdate
        :show.sync="dialog.updateAdminDialogStatus"
        :info="info"
        :field="field"
        :itemList="itemList"
        :size="formSize"
        :menu="menu"
        @refesh_list="index"></AdminUpdate>

    <ApiUpdate
        :show.sync="dialog.updateApiDialogStatus"
        :info="info"
        :field="field"
        :itemList="itemList"
        :size="formSize"
        :menu="menu"
        @refesh_list="index"></ApiUpdate>

  </div>
</template>
<script>
import Sortable from 'sortablejs'
import {fieldList, updateFieldExt, getFieldInfo, deleteField, updateFieldSort, create, exportExcel} from '../sys'
import AdminAdd from './admin/Add.vue'
import AdminUpdate from './admin/Update.vue'
import ApiAdd from './api/Add.vue'
import ApiUpdate from './api/Update.vue'
import mixinVexTable from "@/mixin/vxeTable"

export default {
  name: 'Filed',
  components: { AdminAdd, AdminUpdate, ApiAdd, ApiUpdate},
  mixins: [mixinVexTable],
  data() {
    return {
      dialog: {
        addAdminDialogStatus: false,
        updateAdminDialogStatus: false,
        addApiDialogStatus: false,
        updateApiDialogStatus: false,
      },

      app_type: 1,
      app_id: null,
      menu_id: null,

      menu: {},
      ids: {},
      field_titles: [],
      list: [],
      field: [],
      itemList: [],
      info: {},
      loading: false,
      page_data: {
        limit: 20,
        page: 1,
        total: 50,
      },
      file: '',
      filename: '',

      // 表格配置
      keyboardConfig: {
        isArrow: true,
      },
      customConfig: {
        storage: true,
      },
      columnConfig: {
        isCurrent: true,
        isHover: true,
        resizable: true,
      },
      rowConfig: {
        keyField: 'user_id',
        isCurrent: true,
        isHover: true,
      },
      checkboxConfig: {
        range: true,
        reserve: true,
        trigger: 'row',
        // highlight: true,
      },
      editConfig: {
        enabled: true,
        trigger: 'dblclick',
        mode: 'cell',
      },
    };
  },
  activated(){
    // 页面每次载入都执行
    this.app_type = this.$route.query.app_type ? this.$route.query.app_type : 1
    this.app_id = this.$route.query.app_id ? this.$route.query.app_id : null
    this.menu_id = this.$route.query.menu_id
    this.index()
  },
  mounted() {
    this.rowDrop()
  },
  methods: {
    // 选中行
    checkboxChange({ row }){
      // 选中的行
      const checkedRecords = this.$refs.xTable.getCheckboxRecords()
      // id 的数组
      this.ids = checkedRecords.map(item => item.id)
      // 当前选中的对象
      this.selectRow = row
      // 是否是单选
      this.single = checkedRecords.length != 1
      // 是否是多选
      this.multiple = !checkedRecords.length
    },
    index() {
      let param = {
        limit: this.page_data.limit,
        page: this.page_data.page,
        menu_id: this.menu_id
      }
      this.loading = true
      fieldList(param).then(res => {
        this.menu = res.menu
        this.list = res.data.data
        // 快速编辑，暂时开放的类型
        if(res.menu.page_type == 3) {
          res.typeField.forEach((item,index)=>{
            if([1,2,3,4,5,6,8,9,17,29].indexOf(item.type) != -1){
              this.field.push(item)
            }
          })
        } else {
          this.field = res.typeField
        }

        this.itemList = res.itemList
        this.page_data.total = res.data.total
        this.loading = false
      })
      this.$refs.xTable.reloadData(this.list)
    },
    // 添加管理字段
    addAdmin() {
      this.selectRow = null
      this.dialog.addAdminDialogStatus = true
    },
    // 编辑管理字段
    editorAdmin(row) {
      this.selectRow = row
      let id = row.id ? row.id : this.ids.join(',')
      getFieldInfo({id: id}).then(res => {
        this.dialog.updateAdminDialogStatus = true
        this.info = res.data
      })
    },
    // 添加API字段
    addApi() {
      this.selectRow = null
      this.dialog.addApiDialogStatus = true
    },
    // 编辑API字段
    editorApi(row) {
      this.selectRow = row
      let id = row.id ? row.id : this.ids.join(',')
      getFieldInfo({id: id}).then(res => {
        this.dialog.updateApiDialogStatus = true
        this.info = res.data
      })
    },
    rowDrop() {
      const el = this.$refs.xTable.$el.querySelectorAll('.body--wrapper>.vxe-table--body tbody')[0]
      this.sortable = Sortable.create(el, {
        ghostClass: 'sortable-ghost',
        setData: function (dataTransfer) {
          dataTransfer.setData('Text', '')
        },
        onEnd: e => {
          console.log(e)
          const targetRow = this.list.splice(e.oldIndex, 1)[0]
          this.list.splice(e.newIndex, 0, targetRow)
          let currentId = this.list[e.newIndex].id
          let preId,nextId
          if( this.list[e.newIndex-1]){
            preId = this.list[e.newIndex-1].id
          }else {
            preId = ""
          }
          if( this.list[e.newIndex+1]){
            nextId = this.list[e.newIndex+1].id
          }else {
            nextId = ""
          }
          updateFieldSort({currentId:currentId,preId:preId,nextId:nextId,menu_id:this.$route.query.menu_id}).then(res => {
            if(res.status == 200){
              this.list = []
              this.index()
              this.$message({message: '操作成功', type: 'success'})
            }
          })
        }
      })
    },
    // 返回按钮
    jump() {
      // 删除当前标签
      Object.assign(this.tagsLocal,this.$route)
      this.$store.dispatch('deletetag',this.tagsLocal)
      // 回到菜单管理
      this.$router.push({
        path: '/admin/Menu/index.html', query: {
          app_id: this.$route.query.app_id,
          app_type: this.$route.query.app_type,
        }
      })
    },
    deleteField(row) {
      this.$MyUtils.confirm({content: '确定删除字段吗'}).then(() => {
        let ids = row.id ? row.id : this.ids
        deleteField({id: ids, menu_id: this.$route.query.menu_id}).then(res => {
          this.$message({message: res.pk_status ? '操作成功,其中主键无法删除' : '操作成功', type: 'success'})
          this.$refs.xTable.remove(row)
        })
      }).catch(() => {
      })
    },
    updateFieldExt(row, field) {
      if (field == 'search_type' && row.search_type != '0' && row.field == "creater_id") {
        this.$message({message: '系统默认是数据隔离的，对create_id列的搜索无效', type: 'error'})
        return;
      }
      updateFieldExt({id: row.id, [field]: row[field]}).then(res => {
        this.$message({message: '操作成功', type: 'success'})
      })
    },
    create() {
      this.$MyUtils.confirm({content: '确定生成吗?'}).then(() => {
        create({menu_id: this.$route.query.menu_id}).then(res => {
          this.$message({message: '操作成功', type: 'success'})
        })
      }).catch(() => {
      })
    },
    defaultField() {
      this.dialog.fieldDialogStatus = true
    },
    exportExcel(){
      this.$MyUtils.confirm({content: '当前操作将<span style="color: red">导出数据</span>，您确定要继续吗？'}).then(() => {
        let param = {
          type_name: 'field',
          menu_id: this.$route.query.menu_id
        }
        exportExcel(param).then(res => {
          if (res.status == 200) {
            let a = document.createElement('a') //创建一个a标签
            a.href = res.data.file;
            a.download = res.data.filename;
            a.click();
            URL.revokeObjectURL(a.href); //释放之前创建的url对象
            this.index()
          }
        })
      })
    },
  },

};
</script>
<style lang="scss">
@import '@/assets/scss/common.scss';
</style>
