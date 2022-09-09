<template>
	<div>
		<vxe-modal
			v-model="show" id="add" width="60%" height="80vh" :size="size" :position="{top: '10%'}"
			@close="closeForm()" @show="open" show-zoom resize transfer show-footer destroy-on-close>
			<!-- storage 将窗口拖动的状态保存到本地 remember 再次打开窗口时还原窗口状态-->
			<!--标题-->
			<template #title>
				<span style="font-weight: bold;">添加</span>
			</template>
			<!--主体-->
			<template #default>
				<el-form label-position="left" :size="size" ref="form" :model="form" class="form" :rules="rules" :label-width="$store.getters.device !== 'mobile'?'16%':'90px'">
				<el-tabs v-model="activeName">
					<el-tab-pane label="当前值" name="当前值">
					<!-- 单位名称 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="单位名称" prop="unit">
								<el-input  v-model="form.unit" autoComplete="off" clearable  placeholder="请输入单位名称">
									
									
								</el-input>
								<span class="form-desc">当前统计单位，比如：日，月，周</span>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 名称背景色 -->
					<el-row >
						<el-col :span="23">
							<el-form-item label="名称背景色" prop="unit_color">
								<el-input v-model="form.unit_color" autoComplete="off" clearable placeholder="请输入名称背景色"/>
								<span class="form-desc">统计单位名称的背景色</span>
							</el-form-item>
						</el-col>
						<el-col :span="1">
							<el-color-picker :size="size" v-model="form.unit_color" show-alpha></el-color-picker>
						</el-col>
					</el-row>
					<!-- 当前标题 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="当前标题" prop="current_name">
								<el-input  v-model="form.current_name" autoComplete="off" clearable  placeholder="请输入当前标题">
									
									
								</el-input>
								<span class="form-desc">单位统计的名称，比如：当日订单数，当月销售额</span>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 当前值类型 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="当前值类型" prop="current_type">
								<el-radio-group v-model="form.current_type">
									<el-radio :label="parseInt(1)">固定值</el-radio>
									<el-radio :label="parseInt(2)">SQL语句</el-radio>
								</el-radio-group>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 当前值 -->
					<el-row v-if="form.current_type == 1">
						<el-col :span="24">
							<el-form-item label="当前值" prop="current_value">
								<el-input  v-model="form.current_value" autoComplete="off" clearable  placeholder="请输入当前值">
									
									
								</el-input>
								<span class="form-desc">输入需要显示的当前值的数值，如果设置SQL语句，此值无效；</span>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 当前SQL -->
					<el-row v-if="form.current_type == 2">
						<el-col :span="24">
							<el-form-item label="当前SQL" prop="current_sql">
								<el-input type="textarea" autoComplete="off" v-model="form.current_sql"  :autosize="{ minRows: 2, maxRows: 4}" clearable placeholder="请输入当前SQL"/>
								<span class="form-desc">输入查询累计值的SQL语句，SQL语句优先；</span>
							</el-form-item>
						</el-col>
					</el-row>
					</el-tab-pane>
					<el-tab-pane label="累计值" name="累计值">
					<!-- 累计标题 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="累计标题" prop="total_name">
								<el-input  v-model="form.total_name" autoComplete="off" clearable  placeholder="请输入累计标题">
									
									
								</el-input>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 累计值类型 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="累计值类型" prop="total_type">
								<el-radio-group v-model="form.total_type">
									<el-radio :label="parseInt(1)">固定值</el-radio>
									<el-radio :label="parseInt(2)">SQL语句</el-radio>
								</el-radio-group>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 累计值 -->
					<el-row v-if="form.total_type == 1">
						<el-col :span="24">
							<el-form-item label="累计值" prop="total_value">
								<el-input  v-model="form.total_value" autoComplete="off" clearable  placeholder="请输入累计值">
									
									
								</el-input>
								<span class="form-desc">输入需要展示的累计值的数值，如果设置SQL语句，此值无效；</span>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 累计SQL -->
					<el-row v-if="form.total_type == 2">
						<el-col :span="24">
							<el-form-item label="累计SQL" prop="total_sql">
								<el-input type="textarea" autoComplete="off" v-model="form.total_sql"  :autosize="{ minRows: 2, maxRows: 4}" clearable placeholder="请输入累计SQL"/>
								<span class="form-desc">输入查询累计值的SQL语句，SQL语句优先；</span>
							</el-form-item>
						</el-col>
					</el-row>
					</el-tab-pane>
					<el-tab-pane label="其他" name="其他">
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
import { add  } from '@/api/admin/admin/statisic'
export default {
	name:'admin_statisicadd',
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
	},
	data(){
		return {
			form: {
				unit:'',
				unit_color:'#4AB7BD',
				current_name:'',
				current_type:1,
				current_value:'',
				current_sql:'',
				total_name:'',
				total_type:1,
				total_value:'',
				total_sql:'',
				status:1,
				create_time:'',
			},
			loading:false,
			activeName:'当前值',
			rules: {
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
