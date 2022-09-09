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
							<template slot="label">配置名称</template>
							{{form.title}}
						</el-descriptions-item>
						<el-descriptions-item>
							<template slot="label">覆盖原图</template>
								<span v-if="form.upload_replace == '1'">开启</span>
								<span v-if="form.upload_replace == '0'">关闭</span>
						</el-descriptions-item>
						<el-descriptions-item>
							<template slot="label">生成缩略图</template>
								<span v-if="form.thumb_status == '1'">开启</span>
								<span v-if="form.thumb_status == '0'">关闭</span>
						</el-descriptions-item>
						<el-descriptions-item>
							<template slot="label">缩略图宽</template>
							{{form.thumb_width}}
						</el-descriptions-item>
						<el-descriptions-item>
							<template slot="label">缩略图高</template>
							{{form.thumb_height}}
						</el-descriptions-item>
						<el-descriptions-item>
							<template slot="label">缩放类型</template>
								<span v-if="form.thumb_type == '1'">等比例缩放</span>
								<span v-if="form.thumb_type == '2'">缩放后填充</span>
								<span v-if="form.thumb_type == '3'">居中裁剪</span>
								<span v-if="form.thumb_type == '4'">左上角裁剪</span>
								<span v-if="form.thumb_type == '5'">右下角裁剪</span>
								<span v-if="form.thumb_type == '6'">固定尺寸缩放</span>
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
import { detail } from '@/api/admin/uploadconfig'
export default {
	name:'Uploadconfigdetail',
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
