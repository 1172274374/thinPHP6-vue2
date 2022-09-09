<template>
	<div>
		<vxe-modal
			v-model="show" id="add" width="60%"  :size="size" height="80vh" :position="{top: '10%'}"
			@close="closeForm()" @show="open" show-zoom resize transfer show-footer destroy-on-close>
			<!-- storage 将窗口拖动的状态保存到本地 remember 再次打开窗口时还原窗口状态-->
			<!--标题-->
			<template #title>
				<span style="font-weight: bold;">设置图表</span>
			</template>
			<!--主体-->
			<template #default>
				<iframe ref="iframe" :src="jump" frameborder="0" width="100%" height="99%"></iframe>
			</template>
			<!--底部-->
			<template #footer>
				<el-button type="primary" :size="size" @click="closeForm">确 定</el-button>
			</template>
		</vxe-modal>
	</div>
</template>
<script>
export default {
	name:'Demo_LearnersetChart',
	props: {
		show: {
			type: Boolean,
			default: true
		},
		size: {
			type: String,
			default: 'mini'
		},
		info: {
			type: Object,
		},
	},
	data() {
		return {
			jump:''
		}
	},
	methods: {
		open(){
			let query = {}
			let offsetHeight = this.$refs.iframe.offsetHeight
			Object.assign(query, {dialog_url:1,is_iframe:1,offset_height:offsetHeight})
			Object.assign(query, {learner_id:this.info.learner_id})
			let url = window.location.protocol + '//' + window.location.host
			if(process.env.NODE_ENV == 'development'){
			this.jump = url + '/#/admin/admin.chart/index.html?' + this.$MyUtils.obj2Param(query)
			}
			if(process.env.NODE_ENV == 'production'){
			this.jump = url + '/dist/#/admin/admin.chart/index.html?' + this.$MyUtils.obj2Param(query)
			}
			console.log('JumpURL: ',this.jump)
		},
		closeForm(){
			this.$emit('update:show', false)
			this.$emit('refesh_list')
		}
	}
}
</script>
<style  lang="scss">
@import '@/assets/scss/common.scss'
</style>
