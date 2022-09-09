<template>
	<div>
		<el-row :gutter="10">
			<el-col :span="24">
				<el-card shadow="always">
					 <div v-if="!this.$route.query.dialog_url" slot="header" class="card_header">
						<span style="font-size:14px;">图表设置</span>
						<el-button size="small" @click="jump" type="primary" icon="el-icon-back" style="float:right">返回</el-button>
					 </div>
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
										<el-button  type="success"   :size="formSize"  icon="el-icon-plus"  @click="add(ids)"  v-if="checkPermission('/Admin/Chart/add.html')" >添加</el-button>
										<el-button  type="primary" :disabled="single"  :size="formSize"  icon="el-icon-edit"  @click="update(ids)"  v-if="checkPermission('/Admin/Chart/update.html')" >修改</el-button>
										<el-button  type="danger" :disabled="multiple"  :size="formSize"  icon="el-icon-delete"  @click="del(ids)"  v-if="checkPermission('/Admin/Chart/delete.html')" >删除</el-button>
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
								:checkbox-config="checkboxConfig" @checkbox-all="checkboxChange"  @checkbox-change="checkboxChange">
								<template>
									<vxe-column type="checkbox" width="40"></vxe-column>
									<!-- 编号 -->
									<vxe-column field="chart_id" title= "编号" :visible="false" min-width="80" width="70" show-overflow>
										<template #default="{ row }">
											<span v-if="">{{row.chart_id}}</span> 
										</template>
									</vxe-column>
									<!-- 名称 -->
									<vxe-column field="name" title= "名称" align="left" :visible="true" min-width="80" show-overflow>
										<template #default="{ row }">
											<span>{{row.name}}</span> 
										</template>
									</vxe-column>
									<!-- 类型 -->
									<vxe-column field="type" title= "类型" align="left" :visible="true" min-width="80" show-overflow>
										<template #default="{ row }">
											<el-tag type="primary" v-if="row.type == '1'" size="mini" effect="dark">柱状图</el-tag>
											<el-tag type="warning" v-if="row.type == '2'" size="mini" effect="dark">折线图</el-tag>
										</template>
									</vxe-column>
									<!-- 维度顺序 -->
									<vxe-column field="dimensions" title= "维度顺序" align="left" :visible="true" min-width="80" show-overflow>
										<template #default="{ row }">
											<span>{{row.dimensions}}</span> 
										</template>
									</vxe-column>
									<!-- 数据源类型 -->
									<vxe-column field="source" title= "数据源类型" align="left" :visible="true" min-width="80" show-overflow>
										<template #default="{ row }">
											<el-tag type="primary" v-if="row.source == '1'" size="mini" effect="dark">设定的数据</el-tag>
											<el-tag type="danger" v-if="row.source == '2'" size="mini" effect="dark">SQL查询</el-tag>
										</template>
									</vxe-column>
									<!-- 基础数据源 -->
									<vxe-column field="source_data" title= "基础数据源" align="left" :visible="true" min-width="80" show-overflow>
										<template #default="{ row }">
											<span>{{row.source_data}}</span> 
										</template>
									</vxe-column>
									<!-- SQL数据源 -->
									<vxe-column field="source_sql" title= "SQL数据源" align="left" :visible="true" min-width="80" show-overflow>
										<template #default="{ row }">
											<span>{{row.source_sql}}</span> 
										</template>
									</vxe-column>
									<!-- 排序 -->
									<vxe-column field="sort_id" title= "排序" :visible="false" min-width="80" width="70" show-overflow>
										<template #default="{ row }">
											<el-input @blur.stop="updateExt(row,'sort_id')"  :size="formSize" placeholder="排序" v-model="row.sort_id"></el-input>
										</template>
									</vxe-column>
									<!-- 状态 -->
									<vxe-column field="status" title= "状态" :visible="false" min-width="80" width="70" show-overflow>
										<template #default="{ row }">
											<el-switch @change="quickEdit(row,'status')" :active-value="1" :inactive-value="0" v-model="row.status"></el-switch>
										</template>
									</vxe-column>
									<!-- 创建时间 -->
									<vxe-column field="create_time" title= "创建时间" :visible="false" min-width="80" width="200" show-overflow>
										<template #default="{ row }">
											{{$XEUtils.toDateString(row.create_time * 1000)}}
										</template>
									</vxe-column>
									<!-- 更新时间 -->
									<vxe-column field="update_time" title= "更新时间" :visible="false" min-width="80" width="200" show-overflow>
										<template #default="{ row }">
											{{$XEUtils.toDateString(row.update_time * 1000)}}
										</template>
									</vxe-column>
									<!-- 操作 -->
									<vxe-column title="操作"  align="center" width="200" :fixed="$store.getters.device !== 'mobile' ? 'right' : ''">
										<template #default="{ row }">
											<div v-if="row.chart_id">
												<el-button :size="formSize" type="primary" @click="update(row)" v-if="checkPermission('/Admin/Chart/update.html')"><i class="el-icon-edit" />修改</el-button>
												<el-button :size="formSize" type="danger" @click="del(row)" v-if="checkPermission('/Admin/Chart/delete.html')"><i class="el-icon-delete" />删除</el-button>
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

	</div>
</template>
<script>
	import {
		index,
		updateExt,
		getUpdateInfo,
		del,
	} from '@/api/admin/admin/chart'
	import mixinVexTable from "@/mixin/vxeTable"
	import Search from '@/components/common/VxeSearch.vue'
	import Add from '@/views/admin/admin/chart/add.vue'
	import Update from '@/views/admin/admin/chart/update.vue'
	export default {
		name:'admin_chart',
		components: { Search,Add,Update,		},
		mixins: [mixinVexTable],
		data(){
			return {
				dialog: {
					addDialogStatus : false,
					updateDialogStatus : false,
				},
				updateInfo:{},
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
					keyField: 'chart_id',
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
				listType:[],
				listSource:[],
				listStatus:[],
			}
		},
		watch: {
			page_data: {
				deep: true, immediate: false,
				handler: function (val){
					this.keepPager('admin_chart', {limit: val.limit,page: val.page,})
				},
			}
		},
		activated(){
			this.index()
			if(this.checkPermission('/Admin/Chart/updateExt.html')){
				this.editConfig.enabled = true
			}
		},
		mounted(){
			let pageStatus = JSON.parse(localStorage.getItem('pager_status'));
			Object.assign(this.page_data, pageStatus['admin_chart'])
		},
		methods: {
			// 选中行
			checkboxChange({ row }){
				// 选中的行
				const checkedRecords = this.$refs.xTable.getCheckboxRecords()
				// 主键的数组
				this.ids = checkedRecords.map(item => item.chart_id)
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
					this.listType = [{"label":"柱状图","value":"1"},{"label":"折线图","value":"2"}]
					this.listSource = [{"label":"设定的数据","value":"1"},{"label":"SQL查询","value":"2"}]
					this.listStatus = [{"label":"开启","value":"1"},{"label":"关闭","value":"0"}]
					// 查询表单
					this.searchForm = [
						{type:'Input',label:'名称',prop:'name'},
						{type:'Select',label:'类型',prop:'type',data: this.listType},
						{type:'Input',label:'维度顺序',prop:'dimensions'},
						{type:'Select',label:'数据源类型',prop:'source',data: this.listSource},
						{type:'Input',label:'基础数据源',prop:'source_data'},
						{type:'Input',label:'SQL数据源',prop:'source_sql'},
						{type:'Select',label:'状态',prop:'status',data: this.listStatus},
					]
				})
			},
			// 排序修改方法
			updateExt(row,field){
				if(row.chart_id){
					updateExt({chart_id:row.chart_id,[field]:row[field]}).then(res => {
						this.$message({message: '操作成功', type: 'success'})
						this.index()
					})
				}
			},
			add(){
				this.dialog.addDialogStatus = true
			},
			update(row){
				let id = row.chart_id ? row.chart_id : this.ids.join(',')
				getUpdateInfo({chart_id:id}).then(res => {
					if(res.status == 200){
						this.dialog.updateDialogStatus = true
						this.updateInfo = res.data
					}
				})
			},
			del(row){
				this.$MyUtils.confirm({content:'确定要操作吗'}).then(() => {
					let ids = row.chart_id ? row.chart_id : this.ids.join(',')
					del({chart_id:ids}).then(res => {
						this.$message({message: res.msg, type: 'success'})
						this.index()
					})
				}).catch(() => {})
			},
			jump(){
				Object.assign(this.tagsLocal,this.$route)
				this.$store.dispatch('deletetag',this.tagsLocal)
				this.$router.go(-1)
			},
		},
	}
</script>
<style  lang="scss">
@import '@/assets/scss/common.scss';
</style>