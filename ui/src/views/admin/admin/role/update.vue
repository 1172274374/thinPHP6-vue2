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
					<!-- 角色名称 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="角色名称" prop="name">
								<el-input  v-model="form.name" autoComplete="off" clearable  placeholder="请输入角色名称">
								</el-input>
							</el-form-item>
						</el-col>
					</el-row>
					<!-- 描述 -->
					<el-row >
						<el-col :span="24">
							<el-form-item label="描述" prop="description">
								<el-input  v-model="form.description" autoComplete="off" clearable  placeholder="请输入描述">
								</el-input>
							</el-form-item>
						</el-col>
					</el-row>
          <el-row>
            <el-col :span="24">
              <el-form-item label="菜单权限" prop="role_type">
                <el-tree class="tree-border" @check="checkStrictly" :data="options" show-checkbox ref="menu" node-key="access"  :default-checked-keys="form.access" :check-strictly="strictly" empty-text="加载中，请稍后" :props="defaultProps"></el-tree>
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
import { update  } from '@/api/admin/admin/role'
import { getRoleMenus  } from '@/api/admin/base'
export default {
	name:'admin_roleupdate',
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
  watch: {
    show(value) {
      if(value){
        getRoleMenus().then(res => {
          if(res.status == 200){
            this.options = res.menus
          }
        })
      }
    }
  },
	data(){
		return {
			form: {
				name:'',
				description:'',
				status:1,
			},
			loading:false,
      strictly:true,
			rules: {
				name:[
					{required: true, message: '角色名称不能为空', trigger: 'blur'},
				],
			},
      options:[],
      defaultProps: {
        children: "children",
        label: "title"
      },
		}
	},
	methods: {
		open(){
      this.form = this.info
      this.setDefaultVal('access')
		},
		submit(){
			this.$refs['form'].validate(valid => {
				if(valid) {
					this.loading = true
          this.form.access = this.getMenuAllCheckedKeys()
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
    getMenuAllCheckedKeys() {
      let checkedKeys = this.$refs.menu.getCheckedKeys()
      let halfCheckedKeys = this.$refs.menu.getHalfCheckedKeys()
      checkedKeys.unshift.apply(checkedKeys, halfCheckedKeys)
      return checkedKeys
    },
    setDefaultVal(key){
      if(this.form[key] == null || this.form[key] == ''){
        this.form[key] = []
      }
    },
    checkStrictly(){
      this.strictly = false
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
