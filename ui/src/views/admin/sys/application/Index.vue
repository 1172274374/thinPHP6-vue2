<template>
  <div>
    <el-card shadow="always">
      <div class="main_area">
        <!-- 操作区域 -->
        <div class="action_area">
          <vxe-toolbar ref="xToolbar" :size="formSize" custom :refresh="{ query: index }">
            <template #buttons>
              <div>
                <el-button type="success" :size="formSize" icon="el-icon-plus" @click="add()">新增</el-button>
                <el-button type="primary" :size="formSize" icon="el-icon-edit-outline" :disabled="single" @click="editor(ids)">修改</el-button>
                <el-button type="warning" :size="formSize" icon="el-icon-folder-checked" :disabled="single" @click="buildApplication(ids)">生成应用</el-button>
                <el-button type="danger" :size="formSize" icon="el-icon-delete" :disabled="single" @click="deleteApplication(ids)">卸载</el-button>
                <el-button type="success" :size="formSize" icon="el-icon-notebook-2" :disabled="selectRow.app_type != 2" @click="getDoc()">生成文档 </el-button>
              </div>
            </template>
          </vxe-toolbar>
        </div>
        <!-- 表格区域 -->
        <div class="table_area">
          <vxe-table ref="xTable" id="xTable" border height="100%" :data="list" :loading="loading" :size="formSize"
                     :keyboard-config="keyboardConfig" :custom-config="customConfig" :column-config="columnConfig"
                     :row-config="rowConfig" :edit-config="editConfig" :checkbox-config="checkboxConfig"
                     @checkbox-change="checkboxChange" @checkbox-all="checkboxChange">
            <vxe-column type="checkbox" width="40"></vxe-column>
            <vxe-column title="编号" field="app_id" width="65" sortable></vxe-column>
            <vxe-column title="应用名称" field="application_name" width="100"></vxe-column>
            <vxe-column title="应用目录" field="app_dir" width="100"></vxe-column>
            <vxe-column title="应用类型" field="app_type" width="90">
              <template #default="{ row }">
                <el-tag type="warning" v-if="row.app_type == '0'" :size="formSize" effect="dark">空应用</el-tag>
                <el-tag type="primary" v-if="row.app_type == '1'" :size="formSize" effect="dark">后台应用</el-tag>
                <el-tag type="warning" v-if="row.app_type == '2'" :size="formSize" effect="dark">API应用</el-tag>
              </template>
            </vxe-column>
            <vxe-column title="状态" field="status" width="80" align="center">
              <template #default="{ row }">
                <el-tag type="primary" v-if="row.status == '1'" :size="formSize" effect="dark">启用</el-tag>
                <el-tag type="warning" v-if="row.status == '0'" :size="formSize" effect="dark">禁用</el-tag>
              </template>
            </vxe-column>
            <vxe-column title="域名" field="domain"></vxe-column>
            <vxe-column title="操作" width="200">
              <template #default="{ row }">
                <el-button :size="formSize" type="primary" @click="editor(row)"> <i class="el-icon-edit-outline"/>修改 </el-button>
                <el-button :size="formSize"  type="danger" @click="deleteApplication(row)"> <i class="el-icon-delete"/>卸载 </el-button>
              </template>
            </vxe-column>
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
    <Add :show.sync="dialog.addDialogStatus" :size="formSize" @refesh_list="index"></Add>
    <!--修改-->
    <Update :show.sync="dialog.updateDialogStatus" :info="info" :size="formSize" @refesh_list="index"></Update>
  </div>
</template>
<script>
import {applicationList, getApplicationInfo, deleteApplication, buildApplication, getDoc} from '../sys'
import Add from './Add.vue'
import Update from './Update.vue'
import {confirm} from '@/utils/common'
import mixinVexTable from "@/mixin/vxeTable"
export default {
  components: {Add, Update},
  mixins: [mixinVexTable],
  data() {
    return {
      dialog: {
        addDialogStatus: false,
        updateDialogStatus: false,
      },
      info: {},
      page_data: { limit: 10, page: 1, total: 50, },
      selectRow: { app_type: 0, app_dir: '', app_id: 0, },
      app_type: [{label: '空应用', value: 1},{label: 'API应用', value: 2},],
      statusList: [{label: '启用', value: 1},{label: '禁用', value: 0}],

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
        enabled: false,
        trigger: 'dblclick',
        mode: 'cell',
      },

    };
  },
  activated(){
    // 页面每次载入都执行
    this.index()
  },
  methods: {
    // 选中行
    checkboxChange({ row }){
      // 选中的行
      const checkedRecords = this.$refs.xTable.getCheckboxRecords()
      // app_id 的数组
      this.ids = checkedRecords.map(item => item.app_id)
      // 当前选中的对象
      this.selectRow = row
      // 是否是单选
      this.single = checkedRecords.length != 1
      // 是否是多选
      this.multiple = !checkedRecords.length
    },
    // 载入数据
    index(val) {
      // 基本分页参数
      let param = {limit: this.page_data.limit, page: this.page_data.page}
      // 其他搜索参数
      if (val) param = Object.assign(param, val)
      // 开始显示载入效果
      this.loading = true
      // 获取应用列表
      applicationList(param).then(res => {
        if (res.status == 200) {
          this.list = res.data.data
          this.page_data.total = res.data.total
          this.loading = false
        }
      });
    },
    // 显示添加窗口
    add() {
      this.dialog.addDialogStatus = true
    },
    // 显示编辑窗口
    editor(row) {
      let id = row.app_id ? row.app_id : this.ids.join(',')
      getApplicationInfo({app_id: id}).then(res => {
        if (res.status == 200) {
          this.dialog.updateDialogStatus = true;
          this.info = res.data
        } else {
          this.$message.error('获取信息失败!')
        }
      })
    },
    // 生成应用
    buildApplication(row) {
      this.$MyUtils.confirm({content: '确定生成应用吗'}).then(() => {
        let id = row.app_id ? row.app_id : this.ids.join(',')
        buildApplication({app_id: id}).then(res => {
          if (res.status == 200) {
            this.$message({message: '生成成功', type: 'success'})
          }
        })
      })
    },
    // 删除应用
    deleteApplication(row) {
      this.$MyUtils.confirm({content: '确定要卸载应用吗'}).then(() => {
        let id = row.app_id ? row.app_id : this.ids.join(',')
        if (id == 1) {
          this.$message.error('该应用禁止卸载!')
        }
        deleteApplication({app_id: id}).then(res => {
          if (res.status == 200) {
            this.$message({message: '操作成功', type: 'success'})
            this.index()
          } else {
            this.$message.error('操作失败!')
          }
        });
      }).catch(() => {
      });
    },
    // 生成文档
    getDoc(){
      let content = '执行文档生成的前提是成功安装apidoc命令【npm install -g apidoc】，并验证其可用性；确定执行文档生成？';
      this.$MyUtils.confirm({content: content}).then(()=>{
        getDoc({app_id: this.selectRow.app_id}).then(res=>{
          // console.log(res)
          if (res.status == 200) {
            this.$message({message: res.msg, type: 'success'})
          }
        }).catch(() => {
        });
      })
    },
  },
};
</script>
<style lang="scss">
@import '@/assets/scss/common.scss';
</style>
