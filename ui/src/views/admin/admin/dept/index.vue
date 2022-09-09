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
								:custom="custom"
								:refresh="{ query: index }">
								<template #buttons>
									<div>
										<el-button  type="success"   :size="formSize"  icon="el-icon-plus"  @click="add(ids)"  v-if="checkPermission('/Admin/Dept/add.html')" >添加</el-button>
										<el-button  type="primary" :disabled="single"  :size="formSize"  icon="el-icon-edit"  @click="update(ids)"  v-if="checkPermission('/Admin/Dept/update.html')" >修改</el-button>
										<el-button  type="danger" :disabled="multiple"  :size="formSize"  icon="el-icon-delete"  @click="del(ids)"  v-if="checkPermission('/Admin/Dept/delete.html')" >删除</el-button>
										<el-button  type="info" :disabled="single"  :size="formSize"  icon="el-icon-view"  @click="detail(ids)"  v-if="checkPermission('/Admin/Dept/detail.html')" >查看详情</el-button>
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
								:row-config="rowConfig" :edit-config="editConfig" :tree-config="treeConfig"
								:checkbox-config="checkboxConfig" @checkbox-all="checkboxChange"  @checkbox-change="checkboxChange">
								<template>
									<vxe-column type="checkbox" width="40"></vxe-column>
									<!-- 编号 -->
									<vxe-column field="dept_id" title= "编号" align="center" :visible="true" min-width="80" width="70" show-overflow>
										<template #default="{ row }">
											<span v-if="">{{row.dept_id}}</span> 
										</template>
									</vxe-column>
									<!-- 名称 -->
									<vxe-column field="name" title= "名称" align="left" :visible="true" min-width="80" tree-node show-overflow>
										<template #default="{ row }">
											<span>{{row.name}}</span> 
										</template>
									</vxe-column>
									<!-- 说明 -->
									<vxe-column field="note" title= "说明" align="center" :visible="true" min-width="80" show-overflow>
										<template #default="{ row }">
											<span>{{row.note}}</span> 
										</template>
									</vxe-column>
									<!-- 状态 -->
									<vxe-column field="status" title= "状态" align="center" :visible="true" min-width="80" width="70" show-overflow>
										<template #default="{ row }">
											<el-switch @change="quickEdit(row,'status')" :active-value="1" :inactive-value="0" v-model="row.status"></el-switch>
										</template>
									</vxe-column>
									<!-- 排序 -->
									<vxe-column field="sort_id" title= "排序" align="center" :visible="true" min-width="80" width="70" show-overflow>
										<template #default="{ row }">
											<el-input @blur.stop="updateExt(row,'sort_id')"  :size="formSize" placeholder="排序" v-model="row.sort_id"></el-input>
										</template>
									</vxe-column>
									<!-- 创建时间 -->
									<vxe-column field="create_time" title= "创建时间" :visible="false" min-width="80" width="200" show-overflow>									</vxe-column>
									<!-- 更新时间 -->
									<vxe-column field="update_time" title= "更新时间" :visible="false" min-width="80" width="200" show-overflow>									</vxe-column>
									<!-- 操作 -->
									<vxe-column title="操作"  align="center" width="200" :fixed="$store.getters.device !== 'mobile' ? 'right' : ''">
										<template #default="{ row }">
											<div v-if="row.dept_id">
												<el-button :size="formSize" type="primary" @click="update(row)" v-if="checkPermission('/Admin/Dept/update.html')"><i class="el-icon-edit" />修改</el-button>
												<el-button :size="formSize" type="danger" @click="del(row)" v-if="checkPermission('/Admin/Dept/delete.html')"><i class="el-icon-delete" />删除</el-button>
											</div>
										</template>
									</vxe-column>
								</template>
							</vxe-table>
						</div>
					</div>
				</el-card>
			</el-col>
		</el-row>
		<!--添加-->
		<Add  :show.sync="dialog.addDialogStatus" :size="formSize" @refesh_list="index"></Add>

		<!--修改-->
		<Update :info="updateInfo" :show.sync="dialog.updateDialogStatus" :size="formSize" @refesh_list="index"></Update>

		<!--查看详情-->
		<Detail :info="detailInfo" :show.sync="dialog.detailDialogStatus" :size="formSize" @refesh_list="index"></Detail>

	</div>
</template>
<script>
	import {
		index,
		updateExt,
		getUpdateInfo,
		del,
	} from '@/api/admin/admin/dept'
	import mixinVexTable from "@/mixin/vxeTable"
	import Search from '@/components/common/VxeSearch.vue'
	import Add from '@/views/admin/admin/dept/add.vue'
	import Update from '@/views/admin/admin/dept/update.vue'
	import Detail from '@/views/admin/admin/dept/detail.vue'
	export default {
		name:'admin_dept',
		components: { Search,Add,Update,Detail,		},
		mixins: [mixinVexTable],
		data(){
			return {
				dialog: {
					addDialogStatus : false,
					updateDialogStatus : false,
					detailDialogStatus : false,
				},
				updateInfo:{},
				detailInfo:{},
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
					keyField: 'dept_id',
					isCurrent: true,
					isHover: true,
				},
				checkboxConfig: {
					reserve: true,
					trigger: 'row',
					//highlight: true,// 高亮显示选中的行
				},
				editConfig: {
					enabled: false,
					trigger: 'dblclick',
					mode: 'cell',
				},
				treeConfig: {
					rowField: 'dept_id',
					parentField: 'pid',
					trigger: 'cell',
					expandAll: true,
					// accordion: true, // 手风琴样式，一次只有一个类别展开
				},
				listPid:[],
				listStatus:[],
			}
		},
		watch: {
			page_data: {
				deep: true, immediate: false,
				handler: function (val){
					this.keepPager('admin_dept', {limit: val.limit,page: val.page,})
				},
			}
		},
		activated(){
			this.index()
			if(this.checkPermission('/Admin/Dept/updateExt.html')){
				this.editConfig.enabled = true
			}
		},
		mounted(){
			let pageStatus = JSON.parse(localStorage.getItem('pager_status'));
			Object.assign(this.page_data, pageStatus['admin_dept'])
		},
		methods: {
			// 选中行
			checkboxChange({ row }){
				// 选中的行
				const checkedRecords = this.$refs.xTable.getCheckboxRecords()
				// 主键的数组
				this.ids = checkedRecords.map(item => item.dept_id)
				// 当前选中的对象
				this.selectRow = row
				// 是否是单选
				this.single = checkedRecords.length != 1
				// 是否是多选
				this.multiple = !checkedRecords.length
			},
			index(){
				let param = {limit:999999,page:this.page_data.page}
				Object.assign(param, this.searchData,this.$MyUtils.param2Obj(this.$route.fullPath))
				this.loading = true
				index(param).then(res => {
					this.list = res.data.data
					this.page_data.total = res.data.total
					this.loading = false
					// 下拉框，复选框的选项数据
					this.listPid = res.sql_field_data.pids,
					this.listStatus = [{"label":"开启","value":"1"},{"label":"关闭","value":"0"}]
					// 查询表单
					this.searchForm = [
						{type:'Input',label:'名称',prop:'name'},
						{type:'treeSelect',label:'所属部门',prop:'pid',data:res.sql_field_data.pids},
						{type:'Select',label:'状态',prop:'status',data: this.listStatus},
					]
					this.$nextTick(() => this.$refs.xTable.setAllTreeExpand(true));
				})
			},
			// 排序修改方法
			updateExt(row,field){
				if(row.dept_id){
					updateExt({dept_id:row.dept_id,[field]:row[field]}).then(res => {
						this.$message({message: '操作成功', type: 'success'})
						this.index()
					})
				}
			},
			add(){
				this.dialog.addDialogStatus = true
			},
			update(row){
				let id = row.dept_id ? row.dept_id : this.ids.join(',')
				getUpdateInfo({dept_id:id}).then(res => {
					if(res.status == 200){
						this.dialog.updateDialogStatus = true
						this.updateInfo = res.data
					}
				})
			},
			del(row){
				this.$MyUtils.confirm({content:'确定要操作吗'}).then(() => {
					let ids = row.dept_id ? row.dept_id : this.ids.join(',')
					del({dept_id:ids}).then(res => {
						this.$message({message: res.msg, type: 'success'})
						this.index()
					})
				}).catch(() => {})
			},
			detail(row){
				this.dialog.detailDialogStatus = true
				this.detailInfo = {dept_id:row.dept_id ? row.dept_id : this.ids.join(',')}
			},
			toggleRowExpansion(){
				this.expand = !this.expand
				this.list.forEach(item=>{
					this.$refs.multipleTable.toggleRowExpansion(item,this.expand)
				})
			},
		},
	}
</script>
<style  lang="scss">
@import '@/assets/scss/common.scss';
</style>