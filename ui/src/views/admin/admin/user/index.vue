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
										<el-button  type="success"   :size="formSize"  icon="el-icon-plus"  @click="add(ids)"  v-if="checkPermission('/Admin/User/add.html')" >添加</el-button>
										<el-button  type="primary" :disabled="single"  :size="formSize"  icon="el-icon-edit"  @click="update(ids)"  v-if="checkPermission('/Admin/User/update.html')" >修改</el-button>
										<el-button  type="danger" :disabled="single"  :size="formSize"  icon="el-icon-delete"  @click="del(ids)"  v-if="checkPermission('/Admin/User/delete.html')" >删除</el-button>
										<el-button  type="info" :disabled="single"  :size="formSize"  icon="el-icon-lock"  @click="resetPwd(ids)"  v-if="checkPermission('/Admin/User/resetPwd.html')" >重置密码</el-button>
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
								:row-config="rowConfig" :edit-config="editConfig"
								:radio-config="radioConfig" @radio-change="radioChange">
								<template>
									<vxe-column type="radio" width="40"></vxe-column>
									<!-- 编号 -->
									<vxe-column field="user_id" title= "编号" align="center" :visible="true" min-width="80" width="70" show-overflow>
										<template #default="{ row }">
											<span v-if="">{{row.user_id}}</span> 
										</template>
									</vxe-column>
									<!-- 用户姓名 -->
									<vxe-column field="name" title= "用户姓名" align="left" :visible="true" min-width="80" width="100" show-overflow>
										<template #default="{ row }">
											<span>{{row.name}}</span> 
										</template>
									</vxe-column>
									<!-- 用户名 -->
									<vxe-column field="user" title= "用户名" align="left" :visible="true" min-width="80" width="100" show-overflow>
										<template #default="{ row }">
											<span>{{row.user}}</span> 
										</template>
									</vxe-column>
									<!-- 头像 -->
									<vxe-column field="headimg" title= "头像" align="center" :visible="true" min-width="80" show-overflow>
										<template #default="{ row }">
											<div class="demo-image__preview">
												<el-image v-if="row.headimg" class="table_list_pic" :src="row.headimg"  :preview-src-list="[row.headimg]"></el-image>
											</div>
										</template>
									</vxe-column>
									<!-- 邮箱 -->
									<vxe-column field="email" title= "邮箱" align="left" :visible="true" min-width="80" show-overflow>
										<template #default="{ row }">
											<span>{{row.email}}</span> 
										</template>
									</vxe-column>
									<!-- 手机号 -->
									<vxe-column field="mobile" title= "手机号" align="left" :visible="true" min-width="80" width="90" show-overflow>
										<template #default="{ row }">
											<span>{{row.mobile}}</span> 
										</template>
									</vxe-column>
									<!-- 所属角色 -->
									<vxe-column field="adminrole.name" title= "所属角色" align="left" :visible="true" min-width="80" show-overflow>
										<template #default="{ row }">
											<span>{{row.adminrole.name}}</span> 
										</template>
									</vxe-column>
									<!-- 所属部门 -->
									<vxe-column field="admindept.name" title= "所属部门" align="left" :visible="true" min-width="80" show-overflow>
										<template #default="{ row }">
											<span>{{row.admindept.name}}</span> 
										</template>
									</vxe-column>
									<!-- 数据权限 -->
									<vxe-column field="permission" title= "数据权限" align="left" :visible="true" min-width="80" show-overflow>
										<template #default="{ row }">
											<el-tag type="success" v-if="row.permission == '1'" size="mini" effect="dark">全部数据权限</el-tag>
											<el-tag type="primary" v-if="row.permission == '2'" size="mini" effect="dark">本部门及以下数据权限</el-tag>
											<el-tag type="warning" v-if="row.permission == '3'" size="mini" effect="dark">本部门数据权限</el-tag>
											<el-tag type="info" v-if="row.permission == '4'" size="mini" effect="dark">本人数据权限</el-tag>
											<el-tag type="danger" v-if="row.permission == '5'" size="mini" effect="dark">无数据权限</el-tag>
										</template>
									</vxe-column>
									<!-- 备注 -->
									<vxe-column field="note" title= "备注" align="left" :visible="true" min-width="80" show-overflow>
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
									<vxe-column field="create_time" title= "创建时间" :visible="false" min-width="80" width="200" show-overflow>
										<template #default="{ row }">
											{{$XEUtils.toDateString(row.create_time * 1000)}}
										</template>
									</vxe-column>
									<!-- 更新时间 -->
									<vxe-column field="update_time" title= "更新时间" :visible="false" min-width="80" width="200" show-overflow>									</vxe-column>
									<!-- 操作 -->
									<vxe-column title="操作"  align="center" width="200" :fixed="$store.getters.device !== 'mobile' ? 'right' : ''">
										<template #default="{ row }">
											<div v-if="row.user_id">
												<el-button :size="formSize" type="primary" @click="update(row)" v-if="checkPermission('/Admin/User/update.html')"><i class="el-icon-edit" />修改</el-button>
												<el-button :size="formSize" type="danger" @click="del(row)" v-if="checkPermission('/Admin/User/delete.html')"><i class="el-icon-delete" />删除</el-button>
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
		<!--添加-->
		<Add  :show.sync="dialog.addDialogStatus" :size="formSize" @refesh_list="index"></Add>

		<!--修改-->
		<Update :info="updateInfo" :show.sync="dialog.updateDialogStatus" :size="formSize" @refesh_list="index"></Update>

		<!--重置密码-->
		<ResetPwd :info="resetPwdInfo" :show.sync="dialog.resetPwdDialogStatus" :size="formSize" @refesh_list="index"></ResetPwd>

	</div>
</template>
<script>
	import {
		index,
		updateExt,
		getUpdateInfo,
		del,
	} from '@/api/admin/admin/user'
	import mixinVexTable from "@/mixin/vxeTable"
	import Search from '@/components/common/VxeSearch.vue'
	import Add from '@/views/admin/admin/user/add.vue'
	import Update from '@/views/admin/admin/user/update.vue'
	import ResetPwd from '@/views/admin/admin/user/resetPwd.vue'
	export default {
		name:'admin_user',
		components: { Search,Add,Update,ResetPwd,		},
		mixins: [mixinVexTable],
		data(){
			return {
				dialog: {
					addDialogStatus : false,
					updateDialogStatus : false,
					resetPwdDialogStatus : false,
				},
				updateInfo:{},
				resetPwdInfo:{},
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
					keyField: 'user_id',
					isCurrent: true,
					isHover: true,
				},
				radioConfig: {
					trigger: 'row'
				},
				editConfig: {
					enabled: false,
					trigger: 'dblclick',
					mode: 'cell',
				},
				listRoleId:[],
				listDeptId:[],
				listPermission:[],
				listStatus:[],
			}
		},
		watch: {
			page_data: {
				deep: true, immediate: false,
				handler: function (val){
					this.keepPager('admin_user', {limit: val.limit,page: val.page,})
				},
			}
		},
		activated(){
			this.index()
			if(this.checkPermission('/Admin/User/updateExt.html')){
				this.editConfig.enabled = true
			}
		},
		mounted(){
			let pageStatus = JSON.parse(localStorage.getItem('pager_status'));
			Object.assign(this.page_data, pageStatus['admin_user'])
		},
		methods: {
			// 选中行
			radioChange({ row }){
				// 主键的数组
				this.ids = [row.user_id]
				// 当前选中的对象
				this.selectRow = row
				// 是否是单选
				this.single = this.ids.length != 1
				// 是否是多选
				this.multiple = false
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
					this.listRoleId = res.sql_field_data.role_ids,
					this.listDeptId = res.sql_field_data.dept_ids,
					this.listPermission = [{"label":"全部数据权限","value":"1"},{"label":"本部门及以下数据权限","value":"2"},{"label":"本部门数据权限","value":"3"},{"label":"本人数据权限","value":"4"},{"label":"无数据权限","value":"5"}]
					this.listStatus = [{"label":"正常","value":"1"},{"label":"禁用","value":"0"}]
					// 查询表单
					this.searchForm = [
						{type:'Input',label:'用户姓名',prop:'name'},
						{type:'Input',label:'用户名',prop:'user'},
						{type:'Input',label:'邮箱',prop:'email'},
						{type:'Input',label:'手机号',prop:'mobile'},
						{type:'Select',label:'所属角色',prop:'role_id',data:res.sql_field_data.role_ids},
						{type:'treeSelect',label:'所属部门',prop:'dept_id',data:res.sql_field_data.dept_ids},
						{type:'Select',label:'数据权限',prop:'permission',data: this.listPermission},
						{type:'Select',label:'状态',prop:'status',data: this.listStatus},
					]
				})
			},
			// 排序修改方法
			updateExt(row,field){
				if(row.user_id){
					updateExt({user_id:row.user_id,[field]:row[field]}).then(res => {
						this.$message({message: '操作成功', type: 'success'})
						this.index()
					})
				}
			},
			add(){
				this.dialog.addDialogStatus = true
			},
			update(row){
				let id = row.user_id ? row.user_id : this.ids.join(',')
				getUpdateInfo({user_id:id}).then(res => {
					if(res.status == 200){
						this.dialog.updateDialogStatus = true
						this.updateInfo = res.data
					}
				})
			},
			del(row){
				this.$MyUtils.confirm({content:'确定要操作吗'}).then(() => {
					let ids = row.user_id ? row.user_id : this.ids.join(',')
					del({user_id:ids}).then(res => {
						this.$message({message: res.msg, type: 'success'})
						this.index()
					})
				}).catch(() => {})
			},
			resetPwd(row){
				this.dialog.resetPwdDialogStatus = true
				this.resetPwdInfo = {user_id:row.user_id ? row.user_id : this.ids.join(',')}
			},
		},
	}
</script>
<style  lang="scss">
@import '@/assets/scss/common.scss';
</style>