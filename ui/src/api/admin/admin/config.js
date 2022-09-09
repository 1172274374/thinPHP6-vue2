import request from '@/api/request'


//基本配置
export function index(params) {
	return request({ url: '/Admin.Config/index',method: 'post',data: params})
}
export function getInfo(params) {
	return request({ url: '/Admin.Config/getInfo',method: 'post',data: params})
}
