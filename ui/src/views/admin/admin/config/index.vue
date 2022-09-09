<template>
	<div>
		<el-card shadow="never" class="card">
			<div class="card_content">
				<el-form label-position="left" :size="formSize" ref="form" :model="form" class="config_form" :rules="rules" label-width="90px" >
					<el-tabs v-model="activeName">
						<el-tab-pane label="基本信息" name="基本信息">
							<div class="col_wrap">
								<div  class="col_wrap_item">
									<el-form-item label="站点logo" prop="site_logo">
										<ImagesUpload size="small"  uploadType="2" fileType="image" :image.sync="form.site_logo"></ImagesUpload>
										<span class="form-desc">设置系统侧栏顶部显示的图片</span>
									</el-form-item>
								</div>
								<div  class="col_wrap_item">
									<el-form-item label="站点名称" prop="site_title">
										<el-input  v-model="form.site_title" autoComplete="off" clearable  placeholder="请输入站点名称"></el-input>
										<span class="form-desc">设置本管理系统的名称</span>
									</el-form-item>
								</div>
								<div  class="col_wrap_item">
									<el-form-item label="站点关键词" prop="keyword">
										<Tag :tagList.sync="form.keyword"></Tag>
										<span class="form-desc">设置本系统的站点的关键词</span>
									</el-form-item>
								</div>
								<div  class="col_wrap_item">
									<el-form-item label="站点描述" prop="site_desc">
										<el-input  type="textarea" autoComplete="off" v-model="form.site_desc"  :autosize="{ minRows: 2, maxRows: 4}" clearable placeholder="请输入站点描述"/>
										<span class="form-desc">设置本系统的站点的描述信息</span>
									</el-form-item>
								</div>
								<div  class="col_wrap_item">
									<el-form-item label="站点版权" prop="copyright">
										<el-input  v-model="form.copyright" autoComplete="off" clearable  placeholder="请输入站点版权"></el-input>
										<span class="form-desc">设置登录页面中的授权声明信息</span>
									</el-form-item>
								</div>
								<div  class="col_wrap_item">
									<el-form-item label="内置菜单" prop="show_default_menu">
										<el-radio-group v-model="form.show_default_menu">
											<el-radio :label="'1'">显示</el-radio>
											<el-radio :label="'0'">隐藏</el-radio>
										</el-radio-group>
										<span class="form-desc">是否在菜单管理表格中显示内置菜单项</span>
									</el-form-item>
								</div>
							</div>
						</el-tab-pane>
						<el-tab-pane label="上传配置" name="上传配置">
							<div class="col_wrap">
								<div  class="col_wrap_item">
									<el-form-item label="上传配置" prop="filesize">
										<el-input  v-model="form.filesize" autoComplete="off" clearable  placeholder="请输入上传配置"></el-input>
										<span class="form-desc">设置上传文件大小的最大值</span>
									</el-form-item>
								</div>
								<div  class="col_wrap_item">
									<el-form-item label="文件类型" prop="filetype">
										<el-input  v-model="form.filetype" autoComplete="off" clearable  placeholder="请输入文件类型"></el-input>
										<span class="form-desc">设置可以上传的文件类型</span>
									</el-form-item>
								</div>
								<div  class="col_wrap_item">
									<el-form-item label="水印状态" prop="water_status">
										<el-radio-group v-model="form.water_status">
											<el-radio :label="'1'">正常</el-radio>
											<el-radio :label="'0'">禁用</el-radio>
										</el-radio-group>
										<span class="form-desc">是否设置水印（OSS无效）</span>
									</el-form-item>
								</div>
								<div  class="col_wrap_item">
									<el-form-item label="水印位置" prop="type">
										<el-select style="width:100%" v-model="form.water_position" filterable clearable placeholder="请选择">
											<el-option key="0" label="左上角水印" :value="'1'"></el-option>
											<el-option key="1" label="上居中水印" :value="'2'"></el-option>
											<el-option key="2" label="右上角水印" :value="'3'"></el-option>
											<el-option key="3" label="左居中水印" :value="'4'"></el-option>
											<el-option key="4" label="居中水印" :value="'5'"></el-option>
											<el-option key="5" label="右居中水印" :value="'6'"></el-option>
											<el-option key="6" label="左下角水印" :value="'7'"></el-option>
											<el-option key="7" label="下居中水印" :value="'8'"></el-option>
											<el-option key="8" label="右下角水印" :value="'9'"></el-option>
										</el-select>
										<span class="form-desc">设置水印位置（OSS无效）</span>
									</el-form-item>
								</div>
								<div  class="col_wrap_item">
									<el-form-item label="水印透明度" prop="water_alpha">
										<el-slider v-model="form.water_alpha"></el-slider>
										<span class="form-desc">设置水印图标的透明度</span>
									</el-form-item>
								</div>
								<div  class="col_wrap_item">
									<el-form-item label="绑定域名" prop="domain">
										<el-input  v-model="form.domain" autoComplete="off" clearable  placeholder="请输入绑定域名"></el-input>
										<span class="form-desc">绑定域名是指通过此域名可以访问到Thinkphp项目的地址</span>
									</el-form-item>
								</div>
							</div>
						</el-tab-pane>
						<el-tab-pane label="首页配置" name="首页配置">
							<div class="col_wrap">
								<div  class="col_wrap_item">
									<el-form-item label="显示统计" prop="show_statisic">
										<el-radio-group v-model="form.show_statisic">
											<el-radio :label="'1'">显示</el-radio>
											<el-radio :label="'2'">隐藏</el-radio>
										</el-radio-group>
										<span class="form-desc">首页是否显示统计数据</span>
									</el-form-item>
								</div>
								<div  class="col_wrap_item">
									<el-form-item label="首页启动" prop="show_menu">
										<el-radio-group v-model="form.show_menu">
											<el-radio :label="'1'">显示</el-radio>
											<el-radio :label="'2'">隐藏</el-radio>
										</el-radio-group>
										<span class="form-desc">设置首页是否显示启动图标</span>
									</el-form-item>
								</div>
								<div  class="col_wrap_item">
									<el-form-item label="显示图表" prop="show_chart">
										<el-radio-group v-model="form.show_chart">
											<el-radio :label="'1'">显示</el-radio>
											<el-radio :label="'2'">隐藏</el-radio>
										</el-radio-group>
										<span class="form-desc">是否在首页显示统计图表</span>
									</el-form-item>
								</div>
							</div>
						</el-tab-pane>
						<el-tab-pane label="数据字典" name="数据字典">
							<div class="col_wrap">
								<div  class="col_wrap_item">
									<el-form-item  label="爱好" prop="hobby">
										<draggable v-model="form.hobby" v-bind="{group:'item'}" handle=".hobby-handle">
											<el-row v-for="(item,i) in form.hobby" :key="i">
												<el-col :span="10">
													<el-form-item class="activeItem">
														<el-input  v-model="item.label" placeholder="选项名称"/>
													</el-form-item>
												</el-col>
												<el-col :span="8">
													<el-form-item class="activeItem">
														<el-input style="position:relative;left:5px;" v-model="item.value" placeholder="选项值"/>
													</el-form-item>
												</el-col>
												<el-col :span="4">
													<el-button type="danger" :size="formSize" style="position:relative;left:15px"  icon="el-icon-close" @click="deleteItem('hobby',i)"></el-button>
													<el-button class="hobby-handle" type="success" :size="formSize" style="position:relative;left:15px" icon="el-icon-rank"></el-button>
												</el-col>
											</el-row>
										</draggable>
										<div>
											<el-button type="info" icon="el-icon-plus" style="padding:5px 7px" :size="formSize" @click="addItem('hobby')">追加</el-button>
											<el-button v-if="form.hobby.length > 0" type="warning" icon="el-icon-delete" style="padding:5px 7px" :size="formSize" @click="clearItem('hobby')">清空</el-button>
										</div>
									</el-form-item>
								</div>
							</div>
						</el-tab-pane>
					</el-tabs>
				</el-form>
			</div>
			<div style="display:flex;justify-content: center;width: 100%;">
				<el-button size="small" type="primary" @click="submit">保存设置</el-button>
			</div>
		</el-card>
	</div>
</template>
<script>
import { index ,getFieldList ,getInfo} from '@/api/admin/admin/config'
import draggable from "vuedraggable"
import ImagesUpload from '@/components/common/ImagesUpload.vue'
import Tag from '@/components/common/Tag.vue'
import mixinVexTable from "@/mixin/vxeTable"
export default {
	name:'admin_configindex',
	mixins: [mixinVexTable],
	components: {
		ImagesUpload,
		draggable,
		Tag,
	},
	data(){
		return {
			form: {
				hobby:[{}],
				site_logo:'',
				site_title:'',
				keyword:[],
				site_desc:'',
				copyright:'',
				filesize:'0',
				filetype:'',
				water_status:1,
				domain:'',
				show_default_menu:1,
				show_statisic:1,
				show_menu:1,
				show_chart:1,
			},
			loading:false,
			show:false,
			activeName:'基本信息',
			rules: {
			}
		}
	},
	mounted(){
		getInfo().then(res => {
			if(res.status == 200){
				this.form = JSON.stringify(res.data) == '[]' ? {} : res.data
				this.show = true
				this.setDefaultVal('hobby')
				this.setDefaultVal('keyword')
			}
		})
	},
	methods: {
		submit(){
			this.$refs['form'].validate(valid => {
				if(valid) {
					this.loading = true
					index(this.form).then(res => {
						if(res.status == 200){
							this.$message({message: '操作成功', type: 'success'})
						}
					}).catch(()=>{
						this.loading = false
					})
				}
			})
		},
		addItem(key){
			this.form[key].push({})
		},
		deleteItem(key,index){
			this.form[key].splice(index,1)
		},
		clearItem(key){
			this.form[key] = []
		},
		setDefaultVal(key){
			if(this.form[key] == null || this.form[key] == ''){
				this.form[key] = []
			}
		},
	}
}
</script>
<style scoped lang="scss">
@import '@/assets/scss/common.scss';
</style>
