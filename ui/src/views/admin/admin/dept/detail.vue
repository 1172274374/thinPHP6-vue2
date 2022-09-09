<template>
	<div>
		<vxe-modal
			v-model="show" id="add" width="600px" height="60%" :size="size" :position="{top: '20%'}"
			@close="closeForm()" @show="open" show-zoom resize transfer show-footer destroy-on-close>
			<!-- storage 将窗口拖动的状态保存到本地 remember 再次打开窗口时还原窗口状态-->
			<!--标题-->
			<template #title>
				<span style="font-weight: bold;">查看详情</span>
			</template>
			<!--主体-->
			<template #default>
				<el-descriptions class="margin-top" :column="1" :size="size" border labelClassName="descLabel">
						<el-descriptions-item>
							<template slot="label">名称</template>
							{{form.name}}
						</el-descriptions-item>
						<el-descriptions-item>
							<template slot="label">说明</template>
							{{form.note}}
						</el-descriptions-item>
						<el-descriptions-item>
							<template slot="label">状态</template>
								<span v-if="form.status == '1'">开启</span>
								<span v-if="form.status == '0'">关闭</span>
						</el-descriptions-item>
						<el-descriptions-item>
							<template slot="label">排序</template>
							{{form.sort_id}}
						</el-descriptions-item>
						<el-descriptions-item>
							<template slot="label">创建时间</template>
							{{$XEUtils.toDateString(form.create_time * 1000)}}
						</el-descriptions-item>
						<el-descriptions-item>
							<template slot="label">更新时间</template>
							{{$XEUtils.toDateString(form.update_time * 1000)}}
						</el-descriptions-item>
				</el-descriptions>
			</template>
			<!--底部-->
			<template #footer>
				<el-button type="primary" :size="size" @click="closeForm">确 定</el-button>
			</template>
		</vxe-modal>
	</div>
</template>
<script>
import { detail } from '@/api/admin/admin/dept'
export default {
	name:'Admin_Deptdetail',
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
			form:{
			},
		}
	},
	methods: {
		open(){
			detail(this.info).then(res => {
				this.form = res.data
			})
		},
		closeForm(){
			this.$emit('update:show', false)
		}
	}
}
</script>
<style  lang="scss">
@import '@/assets/scss/common.scss';
	.descLabel {
		width: 90px;
	}
</style>
