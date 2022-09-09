<template>
	<div>
		<vxe-modal
			v-model="show" id="add" width="500px" :size="size" :position="{top: '20%'}"
			@close="closeForm()" @show="open" show-zoom resize transfer show-footer destroy-on-close>
			<!-- storage 将窗口拖动的状态保存到本地 remember 再次打开窗口时还原窗口状态-->
			<!--标题-->
			<template #title>
				<span style="font-weight: bold;">重置密码</span>
			</template>
			<!--主体-->
			<template #default>
				<el-form label-position="left" :size="size" ref="form" :model="form" :rules="rules" label-width="80px">
					<!-- 密码 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="密码" prop="pwd">
								<el-input  show-password autoComplete="off" v-model="form.pwd"  clearable placeholder="请输入密码"/>
							</el-form-item>
						</el-col>
					</el-row>
					<el-row >
						<el-col :span="24">
							<el-form-item label="确认密码" prop="repwd">
								<el-input  show-password autoComplete="off" v-model="form.repwd"  clearable placeholder="请输入确认密码"/>
							</el-form-item>
						</el-col>
					</el-row>
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
import { resetPwd  } from '@/api/admin/admin/user'
export default {
	name:'admin_userresetPwd',
	components: {
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
		var validatePass2 = (rule, value, callback) => {
			if (value === '') {
				callback(new Error('请再次输入密码'))
			} else if (value !== this.form.pwd) {
				callback(new Error('两次输入密码不一致!'))
			} else {
				callback()
			}
		}
		return {
			form: {
			},
			role_ids:[],
			dept_ids:[],
			loading:false,
			rules: {
				repwd:[
					{required: true, validator: validatePass2, trigger: 'blur'},
				],
			}
		}
	},
	methods: {
		open(){
			this.form = this.info
		},
		submit(){
			this.$refs['form'].validate(valid => {
				if(valid) {
					this.loading = true
					resetPwd(this.form).then(res => {
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
<style  lang="scss">
@import '@/assets/scss/common.scss'
</style>
