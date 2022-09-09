import request from '@/api/request'


//数据列表
export function index(params) {
	return request({ url: '/Admin.User/index',method: 'post',data: params})
}
//修改排序开关
export function updateExt(params) {
	return request({ url: '/Admin.User/updateExt',method: 'post',data: params})
}
//添加
export function add(params) {
	return request({ url: '/Admin.User/add',method: 'post',data: params})
}
//修改
export function update(params) {
	return request({ url: '/Admin.User/update',method: 'post',data: params})
}
export function getUpdateInfo(params) {
	return request({ url: '/Admin.User/getUpdateInfo',method: 'post',data: params})
}
//删除
export function del(params) {
	return request({ url: '/Admin.User/delete',method: 'post',data: params})
}
//重置密码
export function resetPwd(params) {
	return request({ url: '/Admin.User/resetPwd',method: 'post',data: params})
}
//获取sql语句定义的字段信息
export function getFieldList(params) {
	return request({ url: '/Admin.User/getFieldList',method: 'post',data: params})
}
