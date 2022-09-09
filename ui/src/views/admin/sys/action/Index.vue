<template>
  <div>
    <!-- 主要操作区域以card样式出现 -->
    <el-card shadow="always">
      <div slot="header" class="clearfix">
        <span style="font-size:14px;font-weight: bold">{{ menu.title }} 方法列表</span>
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
                <el-button type="danger" :size="formSize" icon="el-icon-delete" :disabled="multiple" @click="deleteAction(ids)">删除</el-button>
                <el-button type="info" :size="formSize" icon="el-icon-edit-outline" @click="create">生成代码</el-button>
                <template v-if="app_type == 1">
                  <el-select :size="formSize" style="margin-left:5px; width:150px;" class="select" multiple collapse-tags v-model="select_actions" placeholder="选择方法">
                    <el-option v-for="(item,i) in default_actions" :key="i" :label="item.name" :value="item.type"></el-option>
                  </el-select>
                  <el-button type="primary" :size="formSize" style="margin-left:5px;" icon="el-icon-check" @click="quckCreateAction">快速创建</el-button>
                </template>
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
              <vxe-column title="编号" field="id" width="90" sortable></vxe-column>
              <vxe-column title="操作名称" field="name" min-width="100" :edit-render="{}">
                <template #edit="{ row }">
                  <el-input v-model="row.name" type="text" :size="formSize" @change="update(row,'name')"></el-input>
                </template>
              </vxe-column>
              <vxe-column title="方法名称" field="action_name" min-width="100" :edit-render="{}">
                <template #edit="{ row }">
                  <el-input v-model="row.action_name" type="text" :size="formSize" @change="update(row,'action_name')"></el-input>
                </template>
              </vxe-column>
              <vxe-column title="方法类型" field="type" min-width="100" width="140">
                <template #default="{ row }">
                  <el-select v-model="row.type" :size="formSize" @change="update(row,'type')" clearable filterable placeholder="请选择">
                    <el-option v-for="item in app_type == 2 ? apiaction : action" :key="item.name" :label="item.name" :value="item.type"></el-option>
                  </el-select>
                </template>
              </vxe-column>
              <vxe-column title="生成后端" field="server_create_status" min-width="70" width="70" align="center">
                <template #default="{ row }">
                  <el-switch @change="update(row,'server_create_status')" :size="formSize" :active-value="1" :inactive-value="0" v-model="row.server_create_status"></el-switch>
                </template>
              </vxe-column>
              <template v-if="app_type == 1">
                <vxe-column title="生成前端" field="vue_create_status" min-width="60" width="70" align="center" :key="Math.random()">
                  <template #default="{ row }">
                    <el-switch @change="update(row,'vue_create_status')" :size="formSize" :active-value="1" :inactive-value="0" v-model="row.vue_create_status"></el-switch>
                  </template>
                </vxe-column>
                <vxe-column title="组按钮" field="group_button_status" min-width="60" width="70" align="center" :key="Math.random()">
                  <template #default="{ row }">
                    <el-switch @change="update(row,'group_button_status')" :size="formSize" :active-value="1" :inactive-value="0" v-model="row.group_button_status"></el-switch>
                  </template>
                </vxe-column>
                <vxe-column title="列表按钮" field="list_button_status" min-width="60" width="70" align="center" :key="Math.random()">
                  <template #default="{ row }">
                    <el-switch @change="update(row,'list_button_status')" :size="formSize" :active-value="1" :inactive-value="0" v-model="row.list_button_status"></el-switch>
                  </template>
                </vxe-column>
                <vxe-column title="按钮样式" field="button_color" min-width="100" width="120" align="center" :key="Math.random()">
                  <template #default="{ row }">
                    <el-select v-model="row.button_color" @change="update(row,'button_color')" clearable :size="formSize" filterable placeholder="请选择">
                      <el-option key="1" style="background:#409eff" label="primary" value="primary"></el-option>
                      <el-option key="2" style="background:#67c23a" label="success" value="success"></el-option>
                      <el-option key="3" style="background:#909399" label="info" value="info"></el-option>
                      <el-option key="4" style="background:#e6a23c" label="warning" value="warning"></el-option>
                      <el-option key="5" style="background:#f56c6c" label="danger" value="danger"></el-option>
                    </el-select>
                  </template>
                </vxe-column>
                <vxe-column title="弹窗大小" field="dialog_size" min-width="60" width="90" :edit-render="{}" :key="Math.random()">
                  <template #edit="{ row }">
                    <el-input v-model="row.dialog_size" type="text" :size="formSize" @change="update(row,'dialog_size')"></el-input>
                  </template>
                </vxe-column>
              </template>
              <template v-if="app_type == 2">
                <vxe-column title="Token验证" field="api_auth" min-width="90" width="90" align="center" :edit-render="{}" :key="Math.random()">
                  <template #default="{ row }">
                    <el-switch @change="update(row,'api_auth')" :size="formSize" :active-value="1" :inactive-value="0" v-model="row.api_auth"></el-switch>
                  </template>
                </vxe-column>
                <vxe-column title="短信验证" field="sms_auth" min-width="80" width="80" align="center" :key="Math.random()">
                  <template #default="{ row }">
                    <el-switch @change="update(row,'sms_auth')" :size="formSize" :active-value="1" :inactive-value="0" v-model="row.sms_auth"></el-switch>
                  </template>
                </vxe-column>
                <vxe-column title="图片验证" field="img_auth" min-width="80" width="80" align="center" :key="Math.random()">
                  <template #default="{ row }">
                    <el-switch @change="update(row,'img_auth')" :size="formSize" :active-value="1" :inactive-value="0" v-model="row.img_auth"></el-switch>
                  </template>
                </vxe-column>
                <vxe-column title="缓存时间" field="cache_time" min-width="80" width="100" align="center" :edit-render="{}" :key="Math.random()">
                  <template #edit="{ row }">
                    <el-input v-model="row.cache_time" type="text" :size="formSize" @change="update(row,'cache_time')"></el-input>
                  </template>
                </vxe-column>
              </template>
              <vxe-column title="排序" field="sortid" min-width="100" width="80" :edit-render="{}">
                <template #edit="{ row }">
                  <el-input v-model="row.sortid" type="text" :size="formSize" @change="update(row,'sortid')"></el-input>
                </template>
              </vxe-column>
              <vxe-column title="操作" width="200" fixed="right" :key="Math.random()">
                <template #default="{ row }">
                  <el-button :size="formSize" type="primary" @click="editorAdmin(row)" v-if="app_type == 1">
                    <i class="el-icon-edit-outline"/>修改
                  </el-button>
                  <el-button :size="formSize" type="primary" @click="editorApi(row)" v-if="app_type == 2">
                    <i class="el-icon-edit-outline"/>修改
                  </el-button>
                  <el-button :size="formSize" type="danger" @click="deleteAction(row)">
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
              :size="formSize"
              :loading="loading"
              :current-page="page_data.page"
              :page-size="page_data.limit"
              :total="page_data.total"
              :layouts="['PrevPage', 'JumpNumber', 'NextPage', 'FullJump', 'Sizes', 'Total']"
              @page-change="pageChange">
          </vxe-pager>
        </div>
      </div>
    </el-card>
    <div>

      <!--添加-->
      <AddAdmin :show.sync="dialog.addAdminDialogStatus" :info="info" :action="action" :size="formSize" :menu="menu" @refesh_list="index"></AddAdmin>

      <AddApi :show.sync="dialog.addApiDialogStatus" :info="info" :action="apiaction" :size="formSize" :menu="menu" @refesh_list="index"></AddApi>

      <!--修改-->
      <UpdateAdmin :show.sync="dialog.updateAdminDialogStatus" :info="info" :action="action" :size="formSize" :menu="menu" @refesh_list="index"></UpdateAdmin>

      <UpdateApi :show.sync="dialog.updateApiDialogStatus" :info="info" :action="apiaction" :size="formSize" :menu="menu" @refesh_list="index"></UpdateApi>

    </div>

  </div>

</template>
<script>
import Sortable from 'sortablejs'
import {actionList,deleteAction,updateActionExt,getActionInfo,updateActionSort,quckCreateAction,create,exportExcel} from '../sys'
import AddAdmin from './admin/Add.vue'
import UpdateAdmin from './admin/Update.vue'
import AddApi from './api/Add.vue'
import UpdateApi from './api/Update.vue'
import mixinVexTable from "@/mixin/vxeTable"
export default {
  name:'Action',
  mixins: [mixinVexTable],
  components: { AddAdmin,UpdateAdmin,AddApi,UpdateApi },
  data() {
    return {
      dialog: {
        addAdminDialogStatus : false,
        updateAdminDialogStatus : false,
        addApiDialogStatus : false,
        updateApiDialogStatus : false,
      },
      app_type: 1,
      app_id: null,
      menu_id: null,
      menu:{},
      ids: {},
      list: [{
        id: null,
        name: null,
        action_name: null,
        type: null,
        server_create_status: null,
        vue_create_status: null,
        group_button_status: null,
        list_button_status: null,
        button_color: null,
        dialog_size: null,
        api_auth: null,
        sms_auth: null,
        img_auth: null,
        cache_time: null,
        sortid: null,
      }],
      action:[],
      apiaction:[],
      info:{},
      select_actions:[],
      default_actions:[],
      loading: false,
      page_data: {
        limit: 20,
        page: 1,
        total: 50,
      },

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
      // app_id 的数组
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
      this.list = []
      actionList(param).then(res => {
        this.menu = res.menu
        this.list = res.data.data
        this.action =  res.actionList.filter(item=>item.show_admin)
        this.apiaction =  res.actionList.filter(item=>item.show_api)
        this.default_actions = res.actionList.filter(item=>item.default_create)
        this.page_data.total = res.data.total
        this.loading = false
      })
    },
    addAdmin() {
      this.selectRow = null
      this.dialog.addAdminDialogStatus = true;
    },
    editorAdmin(row) {
      this.selectRow = row
      let id = row.id ? row.id : this.ids.join(',')
      getActionInfo({ id: id, menu_id: this.menu.menu_id}).then(res => {
        if(res.status == 200){
            this.dialog.updateAdminDialogStatus = true
            this.info = res.data
        }else{
            this.$message.error('获取信息失败!')
        }
      })
    },
    addApi() {
      this.selectRow = null
      this.dialog.addApiDialogStatus = true;
    },
    editorApi(row) {
      this.selectRow = row
      let id = row.id ? row.id : this.ids.join(',')
      getActionInfo({ id:id, menu_id: this.menu.menu_id }).then(res => {
        if(res.status == 200){
            this.dialog.updateApiDialogStatus = true
            this.info = res.data
        }else{
            this.$message.error('获取信息失败!')
        }
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
          updateActionSort({currentId:currentId,preId:preId,nextId:nextId,menu_id:this.$route.query.menu_id}).then(res => {
            if(res.status == 200){
              this.list = []
              this.index()
              this.$message({message: '操作成功', type: 'success'})
            }
          })
        }
      })
    },
    jump(){
      // 删除当前标签
      Object.assign(this.tagsLocal,this.$route)
      this.$store.dispatch('deletetag',this.tagsLocal)
      // 回到菜单管理
      this.$router.push({path:'/admin/Menu/index.html',query: { app_id: this.app_id, app_type: this.app_type, }})
    },
    deleteAction(row) {
        this.$MyUtils.confirm({content:'确定删除选定项'}).then(() => {
            let ids = row.id ? row.id : this.ids
            deleteAction({id:ids,menu_id:this.menu.menu_id}).then(res => {
              if(res.status == 200){
                this.$message({message: '操作成功', type: 'success'})
                this.index()
              }else{
                  this.$message.error('操作失败!')
              }
            })
        }).catch(() => {})
    },
    update(row,field){
      updateActionExt({id:row.id,[field]:row[field]}).then(res => {
        if(res.status !== 200){
            this.$message.error('操作失败!')
        }else{
            this.$message({message: '操作成功', type: 'success'})
        }
      })
    },
    quckCreateAction(){
      if(this.select_actions.length == 0){
          this.$message.error('请选择创建方法!')
      }else{
          this.$MyUtils.confirm({content:'确定创建方法吗'}).then(() => {
            quckCreateAction({actions:this.select_actions,menu_id:this.menu.menu_id}).then(res => {
              if(res.status == 200){
                  this.index()
                  this.select_actions = []
                  this.$message({message: res.exits_status?'操作成功,部分方法已存在 无法创建!':'操作成功!', type: 'success'})
              }else{
                  this.$message.error('操作失败!')
              }
            })
          }).catch(() => {})
      }
    },
    create(){
      this.$MyUtils.confirm({content:'确定生成吗?'}).then(() => {
        create({menu_id:this.menu.menu_id}).then(res => {
            this.$message({message: '操作成功', type: 'success'})
        })
      }).catch(() => {})
    },
    exportExcel(){
      this.$MyUtils.confirm({content: '当前操作将<span style="color: red">导出数据</span>，您确定要继续吗？'}).then(() => {
        let param = {
          type_name: 'action',
          menu_id: this.menu.menu_id
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
