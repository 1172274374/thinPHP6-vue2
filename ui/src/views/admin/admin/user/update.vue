<template>
	<div>
		<vxe-modal
			v-model="show" id="add" width="600px" height="80vh" :size="size" :position="{top: '10%'}"
			@close="closeForm()" @show="open" show-zoom resize transfer show-footer destroy-on-close>
			<!-- storage 将窗口拖动的状态保存到本地 remember 再次打开窗口时还原窗口状态-->
			<!--标题-->
			<template #title>
				<span style="font-weight: bold;">修改</span>
			</template>
			<!--主体-->
			<template #default>
				<el-form label-position="left" :size="size" ref="form" :model="form" class="form" :rules="rules" :label-width="$store.getters.device !== 'mobile'?'16%':'90px'">
					<el-tabs v-model="activeName">
					<el-tab-pane label="基本信息" name="基本信息">
					<!-- 用户名 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="用户名" prop="user">
								<el-input  v-model="form.user" autoComplete="off" clearable  placeholder="请输入用户名">
									
									
								</el-input>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 头像 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="头像" prop="headimg">
								<ImagesUpload :size="size"  uploadType="2" fileType="image" :image.sync="form.headimg"></ImagesUpload>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 密码 -->
					<!-- 所属角色 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="所属角色" prop="type">
								<el-select style="width:100%" v-model="form.role_id" filterable clearable placeholder="请选择">
									<el-option v-for="(item,i) in role_ids" :key="i" :label="item.label" :value="item.value"></el-option>
								</el-select>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 所属部门 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="所属部门" prop="type">
								<Treeselect :class="size" :appendToBody="true" :default-expand-level="1" v-model="form.dept_id" :options="dept_ids" 
									:normalizer="normalizer" :show-count="true" zIndex="999999" placeholder="请选择所属部门"/>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 数据权限 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="数据权限" prop="type">
								<el-select style="width:100%" v-model="form.permission" filterable clearable placeholder="请选择">
									<el-option key="0" label="全部数据权限" :value="1"></el-option>
									<el-option key="1" label="本部门及以下数据权限" :value="2"></el-option>
									<el-option key="2" label="本部门数据权限" :value="3"></el-option>
									<el-option key="3" label="本人数据权限" :value="4"></el-option>
									<el-option key="4" label="无数据权限" :value="5"></el-option>
								</el-select>
							</el-form-item>
						</el-col>
					</el-row>
					</el-tab-pane>
					<el-tab-pane label="其他信息" name="其他信息">
					<!-- 用户姓名 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="用户姓名" prop="name">
								<el-input  v-model="form.name" autoComplete="off" clearable  placeholder="请输入用户姓名">
									
									
								</el-input>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 邮箱 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="邮箱" prop="email">
								<el-input  v-model="form.email" autoComplete="off" clearable  placeholder="请输入邮箱">
									
									
								</el-input>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 手机号 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="手机号" prop="mobile">
								<el-input  v-model="form.mobile" autoComplete="off" clearable  placeholder="请输入手机号">
									
									
								</el-input>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 备注 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="备注" prop="note">
								<el-input  v-model="form.note" autoComplete="off" clearable  placeholder="请输入备注">
									
									
								</el-input>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 状态 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="状态" prop="status">
								<el-switch :active-value="1" :inactive-value="0" v-model="form.status"></el-switch>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 排序 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="排序" prop="sort_id">
								<el-input-number 
									controls-position="right" 
									style="width:200px;" 
									autoComplete="off" 
									v-model="form.sort_id" 
									clearable 
									:min="0" 
									placeholder="排序"/>
							</el-form-item>
						</el-col>
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
	</div>
</template>
<script>
import { update ,getFieldList } from '@/api/admin/admin/user'
import ImagesUpload from '@/components/common/ImagesUpload.vue'
import Treeselect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
export default {
	name:'admin_userupdate',
	components: {
		ImagesUpload,
		Treeselect,
	},
	props: {
		show: {
			type: Boolean,
			default: false
		},
		size: {
			type: String,
			default: 'small'
		},
		info: {
			type: Object,
		},
	},
	data(){
		return {
			form: {
				name:'',
				user:'',
				headimg:'',
				email:'',
				mobile:'',
				role_id:1,
				dept_id:'1',
				permission:1,
				note:'',
				status:1,
				update_time:'',
			},
			role_ids:[],
			dept_ids:[],
			loading:false,
			activeName:'基本信息',
			rules: {
				email:[
					{pattern:/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/, message: '邮箱格式错误'}
				],
				mobile:[
					{pattern:/^1[3456789]\d{9}$/, message: '手机号格式错误'}
				],
			}
		}
	},
	watch:{
		show(value){
			if(value){
				getFieldList().then(res => {
					if(res.status == 200){
						this.role_ids = res.data.role_ids
						this.dept_ids = res.data.dept_ids
					}
				})
			}
		}
	},
	methods: {
		open(){
			this.form = this.info
			if(this.info.pid == '0' ){
				this.$delete(this.info,'pid')
			}
			this.form.update_time = this.$XEUtils.toDateString(this.form.update_time * 1000)
		},
		submit(){
			this.$refs['form'].validate(valid => {
				if(valid) {
					this.loading = true
					update(this.form).then(res => {
						if(res.status == 200){
							this.$message({message: '操作成功', type: 'success'})
							this.$emit('refesh_list')
							this.closeForm()
						}
					}).catch(()=>{
						this.loading = false
					})
				}
			})
		},
		normalizer(node) {
			if (node.children && !node.children.length) {
				delete node.children
			}
			return {
				id: node.value,
				label: node.label,
				children: node.children
			}
		},
		closeForm(){
			this.$emit('update:show', false)
			this.loading = false
			if (this.$refs['form']!==undefined) {
				this.$refs['form'].resetFields()
			}
		},
	}
}
</script>
<style scoped lang="scss">
@import '@/assets/scss/common.scss'
</style>
