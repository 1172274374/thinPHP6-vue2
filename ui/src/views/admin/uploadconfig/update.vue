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
					<!-- 配置名称 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="配置名称" prop="title">
								<el-input  v-model="form.title" autoComplete="off" clearable  placeholder="请输入配置名称">
									
									
								</el-input>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 覆盖原图 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="覆盖原图" prop="upload_replace">
								<el-switch :active-value="1" :inactive-value="0" v-model="form.upload_replace"></el-switch>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 生成缩略图 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="生成缩略图" prop="thumb_status">
								<el-switch :active-value="1" :inactive-value="0" v-model="form.thumb_status"></el-switch>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 缩略图宽 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="缩略图宽" prop="thumb_width">
								<el-input  v-model="form.thumb_width" autoComplete="off" clearable  placeholder="请输入缩略图宽">
									
									
								</el-input>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 缩略图高 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="缩略图高" prop="thumb_height">
								<el-input  v-model="form.thumb_height" autoComplete="off" clearable  placeholder="请输入缩略图高">
									
									
								</el-input>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 缩放类型 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="缩放类型" prop="type">
								<el-select style="width:100%" v-model="form.thumb_type" filterable clearable placeholder="请选择">
									<el-option key="0" label="等比例缩放" :value="1"></el-option>
									<el-option key="1" label="缩放后填充" :value="2"></el-option>
									<el-option key="2" label="居中裁剪" :value="3"></el-option>
									<el-option key="3" label="左上角裁剪" :value="4"></el-option>
									<el-option key="4" label="右下角裁剪" :value="5"></el-option>
									<el-option key="5" label="固定尺寸缩放" :value="6"></el-option>
								</el-select>
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
import { update  } from '@/api/admin/uploadconfig'
export default {
	name:'uploadconfigupdate',
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
		return {
			form: {
				title:'',
				upload_replace:1,
				thumb_status:1,
				thumb_width:'',
				thumb_height:'',
			},
			loading:false,
			rules: {
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
