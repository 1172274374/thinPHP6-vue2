import request from '@/api/request'


//数据列表
export function index(params) {
	return request({ url: '/Admin.Chart/index',method: 'post',data: params})
}
//修改排序开关
export function updateExt(params) {
	return request({ url: '/Admin.Chart/updateExt',method: 'post',data: params})
}
//添加
export function add(params) {
	return request({ url: '/Admin.Chart/add',method: 'post',data: params})
}
//修改
export function update(params) {
	return request({ url: '/Admin.Chart/update',method: 'post',data: params})
}
export function getUpdateInfo(params) {
	return request({ url: '/Admin.Chart/getUpdateInfo',method: 'post',data: params})
}
//删除
export function del(params) {
	return request({ url: '/Admin.Chart/delete',method: 'post',data: params})
}
