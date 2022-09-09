<template>
	<div>
		<el-row :gutter="10">
			<el-col :span="24">
				<el-card shadow="always">
					<div class="main_area">
						<div class="search_area" v-if="searchVisible">
							<Search v-if="searchVisible" :formSize="formSize" :labelWidth="labelWidth" :inputWidth="inputWidth"
								:searchData.sync="searchData" :searchForm = "searchForm" @refesh_list="index" :showSearch="!searchVisible">
							</Search>
						</div>
						<div class="action_area">
							<vxe-toolbar 
								ref="xToolbar"
								:size="formSize"
								:export = "checkPermission('/Admin/Log/dumpdata.html') ? true : false"
								:print = "checkPermission('/Admin/Log/simplePrint.html') ? true : false"
								:custom="custom"
								:refresh="{ query: index }">
								<template #buttons>
									<div>
										<el-button  type="danger" :disabled="multiple"  :size="formSize"  icon="el-icon-delete"  @click="del(ids)"  v-if="checkPermission('/Admin/Log/delete.html')" >删除</el-button>
										<el-button  type="info" :disabled="single"  :size="formSize"  icon="el-icon-view"  @click="detail(ids)"  v-if="checkPermission('/Admin/Log/detail.html')" >查看详情</el-button>
										<el-button  type="success"   :size="formSize"  icon=""  @click="loginLogs(ids)"  v-if="checkPermission('/Admin/Log/loginLogs.html')" >登录日志</el-button>
										<el-button  type="primary"   :size="formSize"  icon=""  @click="actionLogs(ids)"  v-if="checkPermission('/Admin/Log/actionLogs.html')" >操作日志</el-button>
										<el-button  type="danger"   :size="formSize"  icon=""  @click="exceptionLogs(ids)"  v-if="checkPermission('/Admin/Log/exceptionLogs.html')" >异常日志</el-button>
									</div>
								</template>
								<template #tools>
									<div style="margin-right: 12px;" v-if="searchVisible">
										<el-button type="success" :size="formSize" icon="el-icon-search" @click="handleSearch" style="margin: 0px 2px;">查询</el-button>
										<el-button type="warning" :size="formSize" icon="el-icon-refresh" @click="resetForm">重置</el-button>
									</div>
									<vxe-button :icon="hiddenSearchIcon" circle style="margin-right: 12px" @click="hiddenSearchArea"></vxe-button>
								</template>
							</vxe-toolbar>
						</div>
						<div :class="tableClass">
							<vxe-table ref="xTable" id="xTable" border height="100%" :data="list" :loading="loading" :size="formSize"
								:keyboard-config="keyboardConfig" :custom-config="customConfig" :column-config="columnConfig"
								:row-config="rowConfig" :edit-config="editConfig" :export-config="exportConfig" :print-config="printConfig"
								:checkbox-config="checkboxConfig" @checkbox-all="checkboxChange"  @checkbox-change="checkboxChange">
								<template>
									<vxe-column type="checkbox" width="40"></vxe-column>
									<!-- 编号 -->
									<vxe-column field="id" title= "编号" align="center" :visible="true" min-width="80" width="70" show-overflow>
										<template #default="{ row }">
											<span v-if="">{{row.id}}</span> 
										</template>
									</vxe-column>
									<!-- 应用名 -->
									<vxe-column field="application_name" title= "应用名" align="center" :visible="true" min-width="80" width="100" show-overflow>
										<template #default="{ row }">
											<span>{{row.application_name}}</span> 
										</template>
									</vxe-column>
									<!-- 用户名 -->
									<vxe-column field="username" title= "用户名" align="center" :visible="true" min-width="80" width="100" show-overflow>
										<template #default="{ row }">
											<span>{{row.username}}</span> 
										</template>
									</vxe-column>
									<!-- 请求url -->
									<vxe-column field="url" title= "请求url" align="left" :visible="true" min-width="80" show-overflow>
										<template #default="{ row }">
											<span>{{row.url}}</span> 
										</template>
									</vxe-column>
									<!-- 客户端ip -->
									<vxe-column field="ip" title= "客户端ip" align="center" :visible="true" min-width="80" width="100" show-overflow>
										<template #default="{ row }">
											<span>{{row.ip}}</span> 
										</template>
									</vxe-column>
									<!-- 类型 -->
									<vxe-column field="type" title= "类型" align="center" :visible="true" min-width="80" width="100" show-overflow>
										<template #default="{ row }">
											<el-tag type="info" v-if="row.type == '1'" size="mini" effect="dark">登录日志</el-tag>
											<el-tag type="warning" v-if="row.type == '2'" size="mini" effect="dark">操作日志</el-tag>
											<el-tag type="danger" v-if="row.type == '3'" size="mini" effect="dark">异常日志</el-tag>
										</template>
									</vxe-column>
									<!-- 创建时间 -->
									<vxe-column field="create_time" title= "创建时间" align="center" :visible="true" min-width="80" width="200" show-overflow>
										<template #default="{ row }">
											{{$XEUtils.toDateString(row.create_time * 1000)}}
										</template>
									</vxe-column>
									<!-- 更新时间 -->
									<vxe-column field="update_time" title= "更新时间" :visible="false" min-width="80" width="200" show-overflow>									</vxe-column>
									<!-- 操作 -->
									<vxe-column title="操作"  align="center" width="140" :fixed="$store.getters.device !== 'mobile' ? 'right' : ''">
										<template #default="{ row }">
											<div v-if="row.id">
												<el-button :size="formSize" type="danger" @click="del(row)" v-if="checkPermission('/Admin/Log/delete.html')"><i class="el-icon-delete" />删除</el-button>
											</div>
										</template>
									</vxe-column>
								</template>
							</vxe-table>
						</div>
					</div>
					<div class="pagination_area">
						<vxe-pager
							:size="formSize" :loading="loading" :current-page="page_data.page" :page-size="page_data.limit" :total="page_data.total"
							:page-sizes="pageSizes" :layouts="['PrevPage', 'JumpNumber', 'NextPage', 'FullJump', 'Sizes', 'Total']" @page-change="pageChange">
						</vxe-pager>
					</div>
				</el-card>
			</el-col>
		</el-row>
		<!--查看详情-->
		<Detail :info="detailInfo" :show.sync="dialog.detailDialogStatus" :size="formSize" @refesh_list="index"></Detail>

	</div>
</template>
<script>
	import {
		index,
		del,
		dumpdata,
		loginLogs,
		actionLogs,
		exceptionLogs,
	} from '@/api/admin/admin/log'
	import mixinVexTable from "@/mixin/vxeTable"
	import Search from '@/components/common/VxeSearch.vue'
	import Detail from '@/views/admin/admin/log/detail.vue'
	import XLSX from "xlsx"
	export default {
		name:'admin_log',
		components: { Search,Detail,		},
		mixins: [mixinVexTable],
		data(){
			return {
				dialog: {
					detailDialogStatus : false,
				},
				detailInfo:{},
				exceldata:[],
				dumppage:1,
				ws:{},
				dumpshow:false,
				percentage:'',
				filename:'',
				page_data: {
					limit: 20,
					page: 1,
					total:20,
				},
				searchVisible:false,
				searchForm:[],
				searchData:{},
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
					keyField: 'id',
					isCurrent: true,
					isHover: true,
				},
				checkboxConfig: {
					range: true,
					reserve: true,
					trigger: 'row',
					//highlight: true,// 高亮显示选中的行
				},
				editConfig: {
					enabled: false,
					trigger: 'dblclick',
					mode: 'cell',
				},
				exportConfig: {
					remote: true,
					exportMethod: this.dumpdata,
					types: ['xlsx', 'xls'],
					modes: ['current', 'selected'],
					filename: 'ExportFileName',
					sheetName: '数据',
					isFooter: false,
					useStyle: false,
				},
				printConfig: {
					style: `
						// 打印样式
						.vxe-table {
							color: #000000; // 修改表格默认颜色
							font-size: 12px; // 修改表格默认字体大小
							font-family: "Microsoft YaHei",微软雅黑,"MicrosoftJhengHei",华文细黑,STHeiti,MingLiu; // 修改表格默认字体
						}
						.vxe-table,
						.vxe-table thead th,
						.vxe-table tbody td,
						.vxe-table tfoot td  {
							border-color: #000000; // 修改表格边框颜色
						}
						.vxe-table thead th {
							color: green; // 修改表头字体颜色
							font-size: 14px; // 修改表头默认字体大小
						}
						.vxe-table tfoot td {
							color: red; // 修改表尾字体颜色
						}
						`,
				},
				listType:[],
				listStatus:[],
			}
		},
		watch: {
			page_data: {
				deep: true, immediate: false,
				handler: function (val){
					this.keepPager('admin_log', {limit: val.limit,page: val.page,})
				},
			}
		},
		activated(){
			this.index()
			if(this.checkPermission('/Admin/Log/updateExt.html')){
				this.editConfig.enabled = true
			}
		},
		mounted(){
			let pageStatus = JSON.parse(localStorage.getItem('pager_status'));
			Object.assign(this.page_data, pageStatus['admin_log'])
		},
		methods: {
			// 选中行
			checkboxChange({ row }){
				// 选中的行
				const checkedRecords = this.$refs.xTable.getCheckboxRecords()
				// 主键的数组
				this.ids = checkedRecords.map(item => item.id)
				// 当前选中的对象
				this.selectRow = row
				// 是否是单选
				this.single = checkedRecords.length != 1
				// 是否是多选
				this.multiple = !checkedRecords.length
			},
			index(){
				let param = {limit:this.page_data.limit,page:this.page_data.page}
				Object.assign(param, this.searchData,this.$MyUtils.param2Obj(this.$route.fullPath))
				this.loading = true
				index(param).then(res => {
					this.list = res.data.data
					this.page_data.total = res.data.total
					this.loading = false
					// 下拉框，复选框的选项数据
					this.listType = [{"label":"登录日志","value":"1"},{"label":"操作日志","value":"2"},{"label":"异常日志","value":"3"}]
					this.listStatus = [{"label":"开启","value":"1"},{"label":"关闭","value":"0"}]
					// 查询表单
					this.searchForm = [
						{type:'Input',label:'用户名',prop:'username'},
						{type:'Select',label:'类型',prop:'type',data: this.listType},
						{type:'Select',label:'状态',prop:'status',data: this.listStatus},
					]
				})
			},
			del(row){
				this.$MyUtils.confirm({content:'确定要操作吗'}).then(() => {
					let ids = row.id ? row.id : this.ids.join(',')
					del({id:ids}).then(res => {
						this.$message({message: res.msg, type: 'success'})
						this.index()
					})
				}).catch(() => {})
			},
			detail(row){
				this.dialog.detailDialogStatus = true
				this.detailInfo = {id:row.id ? row.id : this.ids.join(',')}
			},
			dumpdata({options}){
				// 数据导出;
				let params = options
				params.columns = options.columns.map(item => {return {'field': item.field,'title': item.title}})
				params.data = options.data.map(item => item.id)
				dumpdata(params).then(res=>{
					let a = document.createElement('a') //创建一个a标签
					a.href = res.data.file;
					a.download = res.data.filename;
					a.click();
					URL.revokeObjectURL(a.href); //释放之前创建的url对象
				})
			},
			loginLogs(){
				let param = {limit:this.page_data.limit,page:this.page_data.page}
				Object.assign(param, this.searchData,this.$MyUtils.param2Obj(this.$route.fullPath))
				this.loading = true
				loginLogs(param).then(res => {
					this.list = res.data.data
					this.page_data.total = res.data.total
					this.loading = false
					// 下拉框，复选框的选项数据
					this.listType = [{"label":"登录日志","value":"1"},{"label":"操作日志","value":"2"},{"label":"异常日志","value":"3"}]
					this.listStatus = [{"label":"开启","value":"1"},{"label":"关闭","value":"0"}]
					// 查询表单
					this.searchForm = [
						{type:'Input',label:'用户名',prop:'username'},
						{type:'Select',label:'类型',prop:'type',data: this.listType},
						{type:'Select',label:'状态',prop:'status',data: this.listStatus},
					]
				})
			},
			actionLogs(){
				let param = {limit:this.page_data.limit,page:this.page_data.page}
				Object.assign(param, this.searchData,this.$MyUtils.param2Obj(this.$route.fullPath))
				this.loading = true
				actionLogs(param).then(res => {
					this.list = res.data.data
					this.page_data.total = res.data.total
					this.loading = false
					// 下拉框，复选框的选项数据
					this.listType = [{"label":"登录日志","value":"1"},{"label":"操作日志","value":"2"},{"label":"异常日志","value":"3"}]
					this.listStatus = [{"label":"开启","value":"1"},{"label":"关闭","value":"0"}]
					// 查询表单
					this.searchForm = [
						{type:'Input',label:'用户名',prop:'username'},
						{type:'Select',label:'类型',prop:'type',data: this.listType},
						{type:'Select',label:'状态',prop:'status',data: this.listStatus},
					]
				})
			},
			exceptionLogs(){
				let param = {limit:this.page_data.limit,page:this.page_data.page}
				Object.assign(param, this.searchData,this.$MyUtils.param2Obj(this.$route.fullPath))
				this.loading = true
				exceptionLogs(param).then(res => {
					this.list = res.data.data
					this.page_data.total = res.data.total
					this.loading = false
					// 下拉框，复选框的选项数据
					this.listType = [{"label":"登录日志","value":"1"},{"label":"操作日志","value":"2"},{"label":"异常日志","value":"3"}]
					this.listStatus = [{"label":"开启","value":"1"},{"label":"关闭","value":"0"}]
					// 查询表单
					this.searchForm = [
						{type:'Input',label:'用户名',prop:'username'},
						{type:'Select',label:'类型',prop:'type',data: this.listType},
						{type:'Select',label:'状态',prop:'status',data: this.listStatus},
					]
				})
			},
		},
	}
</script>
<style  lang="scss">
@import '@/assets/scss/common.scss';
</style>