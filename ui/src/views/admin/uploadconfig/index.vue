<template>
	<div>
		<el-row :gutter="10">
			<el-col :span="24">
				<el-card shadow="always">
					<div class="main_area">
						<div class="search_area" v-if="searchVisible">
						</div>
						<div class="action_area">
							<vxe-toolbar 
								ref="xToolbar"
								:size="formSize"
								:custom="custom"
								:refresh="{ query: index }">
								<template #buttons>
									<div>
										<el-button  type="success"   :size="formSize"  icon="el-icon-plus"  @click="add(ids)"  v-if="checkPermission('/admin/Uploadconfig/add.html')" >添加</el-button>
										<el-button  type="primary" :disabled="single"  :size="formSize"  icon="el-icon-edit"  @click="update(ids)"  v-if="checkPermission('/admin/Uploadconfig/update.html')" >修改</el-button>
										<el-button  type="danger" :disabled="multiple"  :size="formSize"  icon="el-icon-delete"  @click="del(ids)"  v-if="checkPermission('/admin/Uploadconfig/delete.html')" >删除</el-button>
										<el-button  type="info" :disabled="single"  :size="formSize"  icon="el-icon-view"  @click="detail(ids)"  v-if="checkPermission('/admin/Uploadconfig/detail.html')" >查看详情</el-button>
									</div>
								</template>
								<template #tools>
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
									<vxe-column field="id" title= "编号" align="center" :visible="true" min-width="80" width="70" show-overflow>
										<template #default="{ row }">
											<span v-if="">{{row.id}}</span> 
										</template>
									</vxe-column>
									<!-- 配置名称 -->
									<vxe-column field="title" title= "配置名称" align="center" :visible="true" min-width="80" show-overflow>
										<template #default="{ row }">
											<span>{{row.title}}</span> 
										</template>
									</vxe-column>
									<!-- 覆盖原图 -->
									<vxe-column field="upload_replace" title= "覆盖原图" align="center" :visible="true" min-width="80" show-overflow>
										<template #default="{ row }">
											<el-switch @change="quickEdit(row,'upload_replace')" :active-value="1" :inactive-value="0" v-model="row.upload_replace"></el-switch>
										</template>
									</vxe-column>
									<!-- 生成缩略图 -->
									<vxe-column field="thumb_status" title= "生成缩略图" align="center" :visible="true" min-width="80" show-overflow>
										<template #default="{ row }">
											<el-switch @change="quickEdit(row,'thumb_status')" :active-value="1" :inactive-value="0" v-model="row.thumb_status"></el-switch>
										</template>
									</vxe-column>
									<!-- 缩略图宽 -->
									<vxe-column field="thumb_width" title= "缩略图宽" align="center" :visible="true" min-width="80" show-overflow>
										<template #default="{ row }">
											<span>{{row.thumb_width}}</span> 
										</template>
									</vxe-column>
									<!-- 缩略图高 -->
									<vxe-column field="thumb_height" title= "缩略图高" align="center" :visible="true" min-width="80" show-overflow>
										<template #default="{ row }">
											<span>{{row.thumb_height}}</span> 
										</template>
									</vxe-column>
									<!-- 缩放类型 -->
									<vxe-column field="thumb_type" title= "缩放类型" align="center" :visible="true" min-width="80" show-overflow>
										<template #default="{ row }">
											<span v-if="row.thumb_type == '1'" >等比例缩放</span>
											<span v-if="row.thumb_type == '2'" >缩放后填充</span>
											<span v-if="row.thumb_type == '3'" >居中裁剪</span>
											<span v-if="row.thumb_type == '4'" >左上角裁剪</span>
											<span v-if="row.thumb_type == '5'" >右下角裁剪</span>
											<span v-if="row.thumb_type == '6'" >固定尺寸缩放</span>
										</template>
									</vxe-column>
									<!-- 操作 -->
									<vxe-column title="操作"  align="center" width="200" :fixed="$store.getters.device !== 'mobile' ? 'right' : ''">
										<template #default="{ row }">
											<div v-if="row.id">
												<el-button :size="formSize" type="primary" @click="update(row)" v-if="checkPermission('/admin/Uploadconfig/update.html')"><i class="el-icon-edit" />修改</el-button>
												<el-button :size="formSize" type="danger" @click="del(row)" v-if="checkPermission('/admin/Uploadconfig/delete.html')"><i class="el-icon-delete" />删除</el-button>
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
	} from '@/api/admin/uploadconfig'
	import mixinVexTable from "@/mixin/vxeTable"
	import Search from '@/components/common/VxeSearch.vue'
	import Add from '@/views/admin/uploadconfig/add.vue'
	import Update from '@/views/admin/uploadconfig/update.vue'
	import Detail from '@/views/admin/uploadconfig/detail.vue'
	export default {
		name:'uploadconfig',
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
				listUploadReplace:[],
				listThumbStatus:[],
				listThumbType:[],
			}
		},
		watch: {
			page_data: {
				deep: true, immediate: false,
				handler: function (val){
					this.keepPager('uploadconfig', {limit: val.limit,page: val.page,})
				},
			}
		},
		activated(){
			this.index()
			if(this.checkPermission('/admin/Uploadconfig/updateExt.html')){
				this.editConfig.enabled = true
			}
		},
		mounted(){
			let pageStatus = JSON.parse(localStorage.getItem('pager_status'));
			Object.assign(this.page_data, pageStatus['uploadconfig'])
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
					this.listUploadReplace = [{"label":"开启","value":"1"},{"label":"关闭","value":"0"}]
					this.listThumbStatus = [{"label":"开启","value":"1"},{"label":"关闭","value":"0"}]
					this.listThumbType = [{"label":"等比例缩放","value":"1"},{"label":"缩放后填充","value":"2"},{"label":"居中裁剪","value":"3"},{"label":"左上角裁剪","value":"4"},{"label":"右下角裁剪","value":"5"},{"label":"固定尺寸缩放","value":"6"}]
				})
			},
			// 排序修改方法
			updateExt(row,field){
				if(row.id){
					updateExt({id:row.id,[field]:row[field]}).then(res => {
						this.$message({message: '操作成功', type: 'success'})
						this.index()
					})
				}
			},
			add(){
				this.dialog.addDialogStatus = true
			},
			update(row){
				let id = row.id ? row.id : this.ids.join(',')
				getUpdateInfo({id:id}).then(res => {
					if(res.status == 200){
						this.dialog.updateDialogStatus = true
						this.updateInfo = res.data
					}
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
		},
	}
</script>
<style  lang="scss">
@import '@/assets/scss/common.scss';
</style>