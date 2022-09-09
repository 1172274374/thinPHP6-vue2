<template>
	<div>
		<vxe-modal
			v-model="show" id="add" width="60%" height="80vh" :size="size" :position="{top: '10%'}"
			@close="closeForm()" @show="open" show-zoom resize transfer show-footer destroy-on-close>
			<!-- storage 将窗口拖动的状态保存到本地 remember 再次打开窗口时还原窗口状态-->
			<!--标题-->
			<template #title>
				<span style="font-weight: bold;">修改</span>
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
								<span class="form-desc">显示在图表顶部的标题</span>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 类型 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="类型" prop="type">
								<el-radio-group v-model="form.type">
									<el-radio :label="parseInt(1)">柱状图</el-radio>
									<el-radio :label="parseInt(2)">折线图</el-radio>
								</el-radio-group>
								<span class="form-desc">系统支持柱状图，折线图</span>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 维度顺序 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="维度顺序" prop="dimensions">
								<el-input  v-model="form.dimensions" autoComplete="off" clearable  placeholder="请输入维度顺序">
									
									
								</el-input>
								<span class="form-desc">维度的顺序；默认把第一个维度映射到 X 轴上，后面维度映射到 Y 轴上。格式为逗号隔开的字符串；</span>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 数据源类型 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="数据源类型" prop="source">
								<el-radio-group v-model="form.source">
									<el-radio :label="parseInt(1)">设定的数据</el-radio>
									<el-radio :label="parseInt(2)">SQL查询</el-radio>
								</el-radio-group>
								<span class="form-desc">选择采用什么方式设置图表数据</span>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 基础数据源 -->
					<el-row v-if="form.source == 1">
						<el-col :span="24">
							<el-form-item label="基础数据源" prop="source_data">
								<el-input type="textarea" autoComplete="off" v-model="form.source_data"  :autosize="{ minRows: 2, maxRows: 4}" clearable placeholder="请输入基础数据源"/>
								<span class="form-desc">cvs格式数据,字段值包含空格用双引号包括，逗号分割字段，每行结尾无逗号</span>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- SQL数据源 -->
					<el-row v-if="form.source == 2">
						<el-col :span="24">
							<el-form-item label="SQL数据源" prop="source_sql">
								<el-input type="textarea" autoComplete="off" v-model="form.source_sql"  :autosize="{ minRows: 2, maxRows: 4}" clearable placeholder="请输入SQL数据源"/>
								<span class="form-desc">查询产生数据的SQL语句，注意查询列别名必须与维度顺序一致；</span>
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
					<!-- 状态 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="状态" prop="status">
								<el-switch :active-value="1" :inactive-value="0" v-model="form.status"></el-switch>
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
import { update  } from '@/api/admin/admin/chart'
export default {
	name:'admin_chartupdate',
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
				name:'',
				type:1,
				dimensions:'',
				source:1,
				source_data:'',
				source_sql:'',
				status:1,
				update_time:'',
			},
			loading:false,
			rules: {
			}
		}
	},
	methods: {
		open(){
			this.form = this.info
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
