<template>
	<div>
		<vxe-modal
			v-model="show" id="add" width="600px" height="80vh" :size="size" :position="{top: '10%'}"
			@close="closeForm()" @show="open" show-zoom resize transfer show-footer destroy-on-close>
			<!-- storage 将窗口拖动的状态保存到本地 remember 再次打开窗口时还原窗口状态-->
			<!--标题-->
			<template #title>
				<span style="font-weight: bold;">添加</span>
			</template>
			<!--主体-->
			<template #default>
				<el-form label-position="left" :size="size" ref="form" :model="form" class="form" :rules="rules" :label-width="$store.getters.device !== 'mobile'?'16%':'90px'">
					<!-- 名称 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="名称" prop="name">
								<el-input  v-model="form.name" autoComplete="off" clearable  placeholder="请输入名称">
									
									
								</el-input>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 所属部门 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="所属部门" prop="type">
								<Treeselect :class="size" :appendToBody="true" :default-expand-level="1" v-model="form.pid" :options="pids" 
									:normalizer="normalizer" :show-count="true" zIndex="999999" placeholder="请选择所属部门"/>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 说明 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="说明" prop="note">
								<el-input type="textarea" autoComplete="off" v-model="form.note"  :autosize="{ minRows: 2, maxRows: 4}" clearable placeholder="请输入说明"/>
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
					<!-- 创建时间 -->
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
import { add ,getFieldList } from '@/api/admin/admin/dept'
import Treeselect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
export default {
	name:'admin_deptadd',
	components: {
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
	},
	data(){
		return {
			form: {
				name:'',
				note:'',
				status:1,
				create_time:'',
			},
			pids:[],
			loading:false,
			rules: {
			}
		}
	},
	watch:{
		show(value){
			if(value){
				getFieldList().then(res => {
					if(res.status == 200){
						this.pids = res.data.pids
					}
				})
			}
		}
	},
	methods: {
		open(){
		},
		submit(){
			this.$refs['form'].validate(valid => {
				if(valid) {
					this.loading = true
					add(this.form).then(res => {
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
