import request from '@/api/request'


//数据列表
export function index(params) {
	return request({ url: '/Admin.Role/index',method: 'post',data: params})
}
//修改排序开关
export function updateExt(params) {
	return request({ url: '/Admin.Role/updateExt',method: 'post',data: params})
}
//添加
export function add(params) {
	return request({ url: '/Admin.Role/add',method: 'post',data: params})
}
//修改
export function update(params) {
	return request({ url: '/Admin.Role/update',method: 'post',data: params})
}
export function getUpdateInfo(params) {
	return request({ url: '/Admin.Role/getUpdateInfo',method: 'post',data: params})
}
//删除
export function del(params) {
	return request({ url: '/Admin.Role/delete',method: 'post',data: params})
}
