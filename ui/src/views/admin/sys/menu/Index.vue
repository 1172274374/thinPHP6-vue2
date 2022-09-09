<template>
  <div>
    <!-- 主要操作区域以card样式出现 -->
    <el-card shadow="always">
      <!-- 主要操作区域 -->
      <div class="main_area">
        <!-- 查询区域 -->
        <div class="search_area">
          <div style="padding-bottom: 10px">
            <el-menu :default-active="active" router class="el-menu-tab" @select="selectApp" mode="horizontal">
              <el-menu-item v-for="item in app_list" :key="item.app_id" :index="item.url">
                <router-link :to="item.url"> {{ item.application_name }} </router-link>
              </el-menu-item>
            </el-menu>
          </div>
        </div>
        <!-- 操作区域 -->
        <div class="action_area">
          <vxe-toolbar ref="xToolbar" :size="formSize" custom :refresh="{ query: index }" class-name="x-toolbar">
            <template #buttons>
              <div class="toolbar-button">
                <el-button type="success" :size="formSize" icon="el-icon-plus" @click="addAdmin()" v-if="app_type == 1">创建</el-button>
                <el-button type="success" :size="formSize" icon="el-icon-plus" @click="addApi()" v-if="app_type == 2">创建</el-button>
                <el-button type="danger" :size="formSize" icon="el-icon-delete" @click="deleteMenu()">卸载</el-button>
                <el-button type="warning" :size="formSize" icon="el-icon-plus" :disabled="selectRow && !selectRow.table_name" @click="field">字段管理</el-button>
                <el-button type="warning" :size="formSize" icon="el-icon-plus" :disabled="selectRow && !selectRow.table_name" @click="action">方法管理</el-button>
                <el-button type="info" :size="formSize" icon="el-icon-edit-outline" :disabled="selectRow && !selectRow.table_name" @click="create">生成代码</el-button>
                <el-select :size="formSize" clearable v-model="appid" placeholder="复制到" class="form-item-margin" v-if="app_type == 1">
                  <el-option v-for="(item,i) in copy_app_list" :key="i" :label="item.application_name" :value="item.app_id"></el-option>
                </el-select>
                <el-button type="primary" :size="formSize" icon="el-icon-check" @click="copyMenu()" v-if="app_type == 1">确定</el-button>
                <el-select :size="formSize"  @change="selectConnect" v-model="connect" placeholder="请选择链接库" class="form-item-margin">
                  <el-option v-for="item in connects" :key="item" :label="item" :value="item"></el-option>
                </el-select>
                <el-select :size="formSize" v-model="tablename" placeholder="请选择数据表" class="form-item-margin">
                  <el-option v-for="(item,i) in tableList" :key="i" :label="item" :value="item"></el-option>
                </el-select>
                <el-button type="primary" :size="formSize" icon="el-icon-check" @click="createByTable">根据表生成 </el-button>
                <el-button type="warning" :size="formSize" icon="el-icon-edit-outline" :disabled="selectRow && !selectRow.copy_from" @click="synCopy">同步复制 </el-button>
                <el-button type="success" size="mini" icon="el-icon-notebook-2" @click="getDoc(app_id)" v-if="app_type == 2">生成文档</el-button>
              </div>
            </template>
          </vxe-toolbar>
        </div>
        <!-- 表格区域 -->
        <div class="table_area_menu">
          <vxe-table
              ref="xTable" id="xTable" border height="100%" :data="list" :loading="loading" :size="formSize"
              :keyboard-config="keyboardConfig" :custom-config="customConfig"
              :column-config="columnConfig" :row-config="rowConfig"
              :tree-config="treeConfig" :edit-config="editConfig"
              :radio-config="radioConfig"
              @radio-change="radioChange">
            <template>
              <vxe-column type="radio" width="40"></vxe-column>
              <vxe-column title="编号" field="menu_id" width="100"></vxe-column>
              <vxe-column title="名称" field="title" tree-node></vxe-column>
              <vxe-column title="控制器" field="controller_name"></vxe-column>
              <vxe-column title="表名" field="table_name"></vxe-column>
              <vxe-column title="生成" field="create_code" width="80"  align="center">
                <template #default="{ row }">
                  <el-switch :active-value="1" :inactive-value="0" @change="update(row,'create_code')" v-model="row.create_code"></el-switch>
                </template>
              </vxe-column>
              <vxe-column title="菜单" field="status" width="80" v-if="app_type == 1" :key="Math.random()" align="center">
                <template #default="{ row }">
                  <el-switch :active-value="1" :inactive-value="0" @change="update(row,'status')" v-model="row.status"></el-switch>
                </template>
              </vxe-column>
              <vxe-column title="建表" field="create_table" width="80" align="center">
                <template #default="{ row }">
                  <el-tag :size="formSize" :type="row.create_table=='1'?'success':'info'" effect="dark">{{ row.create_table == '1' ? '是' : '否' }}</el-tag>
                </template>
              </vxe-column>
              <vxe-column title="首页" field="home_show" width="80" v-if="app_type == 1" :key="Math.random()" align="center">
                <template #default="{ row }">
                  <el-switch :active-value="1" :inactive-value="0" @change="update(row,'home_show')" v-model="row.home_show"></el-switch>
                </template>
              </vxe-column>
              <vxe-column title="顺序" field="home_sort" width="80" v-if="app_type == 1" :edit-render="{}">
                <template #edit="{ row }">
                  <el-input :size="formSize" placeholder="顺序" @blur.stop="update(row,'home_sort')" v-model="row.home_sort"></el-input>
                </template>
              </vxe-column>
              <vxe-column title="排序" field="sortid" width="80" :edit-render="{}">
                <template #edit="{ row }">
                  <el-input :size="formSize" placeholder="排序" @blur.stop="update(row,'sortid')" v-model="row.sortid"></el-input>
                </template>
              </vxe-column>
              <vxe-column title="操作" width="200" :key="Math.random()" align="center">
                <template #default="{ row }">
                  <el-button :size="formSize" @click="editorAdmin(row)" type="primary" v-if="app_type == 1"><i class="el-icon-edit-outline"/>修改 </el-button>
                  <el-button :size="formSize" @click="editorApi(row)" type="primary" v-if="app_type == 2"><i class="el-icon-edit-outline"/>修改 </el-button>
                  <el-button :size="formSize" @click="deleteMenu(row)" type="danger"><i class="el-icon-delete"/>卸载 </el-button>
                </template>
              </vxe-column>
            </template>
          </vxe-table>
        </div>
      </div>
    </el-card>
    <div>

      <!--添加-->
      <AdminAdd
          :show.sync="dialog.addAdminDialogStatus"
          :app_id="app_id"
          :page_type_list="page_type_list"
          :list="listTree"
          :connects="connects"
          :size="formSize"
          @refesh_list="index"></AdminAdd>

      <ApiAdd
          :show.sync="dialog.addApiDialogStatus"
          :app_id="app_id"
          :page_type_list="page_type_list"
          :list="listTree"
          :connects="connects"
          :size="formSize"
          @refesh_list="index"></ApiAdd>

      <!--修改-->
      <AdminUpdate
          :show.sync="dialog.updateAdminDialogStatus"
          :info="info"
          :page_type_list="page_type_list"
          :app_id="app_id"
          :list="listTree"
          :connects="connects"
          :size="formSize"
          @refesh_list="index"></AdminUpdate>

      <ApiUpdate
          :show.sync="dialog.updateApiDialogStatus"
          :info="info"
          :page_type_list="page_type_list"
          :app_id="app_id"
          :list="listTree"
          :connects="connects"
          :size="formSize"
          @refesh_list="index"></ApiUpdate>

    </div>

  </div>

</template>
<script>
import {
  getMenuList,
  getMenuInfo,
  getTableList,
  updateMenuExt,
  deleteMenu,
  create,
  createByTable,
  copyMenu,
  getDoc,
  exportMenuSQL,
  synCopy,
} from '../sys'

import AdminAdd from './admin/Add.vue'
import AdminUpdate from './admin/Update.vue'
import ApiAdd from './api/Add.vue'
import ApiUpdate from './api/Update.vue'
import mixinVexTable from "@/mixin/vxeTable"

export default {
  name: 'Menu',
  mixins: [mixinVexTable],
  components: {AdminAdd, AdminUpdate, ApiAdd, ApiUpdate},
  data() {
    return {
      dialog: {
        addAdminDialogStatus: false,
        updateAdminDialogStatus: false,
        addApiDialogStatus: false,
        updateApiDialogStatus: false,
      },
      listTree: [],
      row: {},
      menu_id: null,
      connects: [],
      connect: '',
      tablename: '',
      tableList: [],
      page_type_list: [],
      app_id: null,
      appid: null,
      app_type: 1,
      app_list: [],
      copy_app_list: [],
      info: {},
      loading: false,
      active: '/admin/Menu/index.html?app_id=1&app_type=1',

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
        keyField: 'menu_id',
        isCurrent: true,
        isHover: true,
      },
      radioConfig: {
        trigger: 'row'
      },
      editConfig: {
        enabled: true,
        trigger: 'dblclick',
        mode: 'cell',
      },
      treeConfig: {
        transform: true,
        rowField: 'menu_id',
        parentField: 'pid',
        trigger: 'cell',
        expandAll: true,
        // accordion: true
      },

      tagsLocal: {
        path: null,
        query: null,
        fullPath: null,
        title: null,
        meta: {
          title: null
        },
        name: null
      }

    };
  },
  activated(){
    // 页面每次载入都执行
    this.index()
    this.app_type = this.$route.query.app_type ? this.$route.query.app_type : '1'
    this.app_id = this.$route.query.app_id ? this.$route.query.app_id : null
    if (this.app_id) {
      this.active = this.$route.fullPath
    } else {
      this.app_id = '1'
      this.active = '/admin/Menu/index.html?app_id=1&app_type=1'
    }
  },
  methods: {
    // 选中行
    radioChange({row}) {
      // app_id 的数组
      this.menu_id = row.menu_id
      // 所选行数据
      this.selectRow = row
      // 是否是单选
      this.single = row.menu_id ? true : false
      // 是否是多选
      this.multiple = false
    },
    // 添加菜单
    addAdmin() {
      this.selectRow = null
      this.dialog.addAdminDialogStatus = true
    },
    // 编辑菜单
    editorAdmin(row) {
      this.selectRow = row
      getMenuInfo({menu_id: this.menu_id}).then(res => {
        this.dialog.updateAdminDialogStatus = true
        this.info = res.data
      })
    },
    // 添加API
    addApi() {
      this.selectRow = null
      this.dialog.addApiDialogStatus = true
    },
    // 编辑API
    editorApi(row) {
      this.selectRow = row
      getMenuInfo({menu_id: this.menu_id}).then(res => {
        this.dialog.updateApiDialogStatus = true
        this.info = res.data
      })
    },
    // 导航到字段管理
    field() {
      let app_type = this.$route.query.app_type ? this.$route.query.app_type : this.app_type
      this.$router.push({
        path: '/admin/Field/index.html',
        query: {app_id: this.app_id, app_type: app_type, menu_id: this.menu_id},
      })
      Object.assign(this.tagsLocal,this.$route)
      this.$store.dispatch('deletetag',this.tagsLocal)
    },
    // 导航到方法管理
    action() {
      let app_type = this.$route.query.app_type ? this.$route.query.app_type : this.app_type
      this.$router.push({
        path: '/admin/Action/index.html',
        query: {app_id: this.app_id, app_type: app_type, menu_id: this.menu_id},
      })
      Object.assign(this.tagsLocal,this.$route)
      this.$store.dispatch('deletetag',this.tagsLocal)
    },
    // 选则数据库链接
    selectConnect(val) {
      getTableList({connect: this.connect}).then(res => {
        this.tableList = res.data
      })
    },
    // 选择菜单
    selectApp(index) {
      this.list = []
      this.app_id = this.$MyUtils.param2Obj(index).app_id
      this.app_type = this.$MyUtils.param2Obj(index).app_type
      this.active = index
      this.index()
    },
    // 生成API文档
    getDoc(app_id) {
      this.$MyUtils.confirm({content: '执行文档生成的前提是成功安装apidoc命令【npm install -g apidoc】，并验证其可用性；确定执行文档生成？'}).then(() => {
        getDoc({app_id: app_id}).then(res => {
          // console.log(res)
          if (res.status == 200) {
            this.$message({message: res.msg, type: 'success'})
          }
        }).catch(() => {
        });
      })
    },
    // 删除菜单
    deleteMenu(row) {
      this.$MyUtils.confirm({content: '确定要卸载吗?'}).then(() => {
        deleteMenu({menu_id: this.menu_id}).then(res => {
          this.$message({message: '操作成功', type: 'success'})
          this.$refs.xTable.remove(row)
        })
      }).catch(() => {
      })
    },
    // 行内修改
    update(row, field) {
      updateMenuExt({menu_id: row.menu_id, [field]: row[field]}).then(res => {
        this.$message({message: '操作成功', type: 'success'})
      })
    },
    // 生成代码
    create() {
      this.$MyUtils.confirm({content: '确定生成吗?'}).then(() => {
        create({menu_id: this.menu_id}).then(res => {
          this.$message({message: '操作成功', type: 'success'})
        })
      }).catch(() => {
      })
    },
    // 导出SQL
    exportMenuSQL() {
      this.$MyUtils.confirm({content: '确定导出菜单定义数据吗?'}).then(() => {
        exportMenuSQL({menu_id: this.menu_id}).then(res => {
          if (res.status == 200) {
            let a = document.createElement('a') //创建一个a标签
            a.href = res.data.file;
            a.download = res.data.filename;
            a.click();
            URL.revokeObjectURL(a.href); //释放之前创建的url对象
          } else {
            this.$message({message: res.msg, type: 'error'})
          }
        }).catch(() => {
        })
      }).catch(() => {
      })
    },
    // 通过数据库表生成
    createByTable() {
      if (this.tablename == '') {
        this.$message.error('请选择生成表')
      } else {
        this.$MyUtils.confirm({content: '确定生成吗?'}).then(() => {
          createByTable({
            connect: this.connect,
            table_name: this.tablename,
            page_type: 1,
            app_id: this.app_id,
            app_type: this.app_type
          }).then(res => {
            this.$message({message: '操作成功', type: 'success'})
            this.index()
          })
        }).catch(() => {
        })
      }
    },
    // 拷贝菜单
    copyMenu() {
      if (this.menu_id == '' || this.appid == '') {
        this.$message.error('请选择菜单或者应用')
      } else {
        this.$MyUtils.confirm({content: '确定复制吗?'}).then(() => {
          copyMenu({appid: this.appid, menu_id: this.menu_id}).then(res => {
            this.$message({message: '操作成功', type: 'success'})
            this.index()
          })
        }).catch(() => {
        })
      }
    },
    // 同步复制的列
    synCopy() {
      if (this.menu_id == '') {
        this.$message.error('请选择菜单')
      } else {
        this.$MyUtils.confirm({content: '同步将会把删除的列恢复，确定同步吗?'}).then(() => {
          synCopy({appid: this.app_id, menu_id: this.menu_id}).then(res => {
            this.$message({message: res.msg, type: 'success'})
            this.index()
          })
        }).catch(() => {
        })
      }
    },
    // 载入菜单
    index() {
      this.loading = true
      getMenuList({app_id: this.app_id}).then(res => {
        this.list = res.list
        this.listTree = res.listTree
        this.connects = res.connects
        this.connect = res.defaultConnect
        this.tableList = res.tableList
        this.app_list = res.app_list
        this.copy_app_list = res.app_list.filter(item => item.app_type !== 3)
        this.page_type_list = res.page_type_list
        this.loading = false
        this.$nextTick(() => this.$refs.xTable.setAllTreeExpand(true));
      })
    },
  },
}
</script>
<style lang="scss">
@import '@/assets/scss/common.scss';

.el-menu-tab{
  .el-menu-item {
    height: 40px;
    line-height: 40px;
    &:hover {
      // 背景色
      background-color: #fff !important;
    }
  }
}
</style>
