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
							<template slot="label">应用名</template>
							{{form.application_name}}
						</el-descriptions-item>
						<el-descriptions-item>
							<template slot="label">用户名</template>
							{{form.username}}
						</el-descriptions-item>
						<el-descriptions-item>
							<template slot="label">请求url</template>
							{{form.url}}
						</el-descriptions-item>
						<el-descriptions-item>
							<template slot="label">客户端ip</template>
							{{form.ip}}
						</el-descriptions-item>
						<el-descriptions-item>
							<template slot="label">浏览器信息</template>
							{{form.useragent}}
						</el-descriptions-item>
						<el-descriptions-item>
							<template slot="label">请求内容</template>
							{{form.content}}
						</el-descriptions-item>
						<el-descriptions-item>
							<template slot="label">异常信息</template>
							{{form.errmsg}}
						</el-descriptions-item>
						<el-descriptions-item>
							<template slot="label">类型</template>
								<span v-if="form.type == '1'">登录日志</span>
								<span v-if="form.type == '2'">操作日志</span>
								<span v-if="form.type == '3'">异常日志</span>
						</el-descriptions-item>
						<el-descriptions-item>
							<template slot="label">创建时间</template>
							{{$XEUtils.toDateString(form.create_time * 1000)}}
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
import { detail } from '@/api/admin/admin/log'
export default {
	name:'Admin_Logdetail',
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
