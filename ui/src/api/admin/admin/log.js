import request from '@/api/request'


//数据列表
export function index(params) {
	return request({ url: '/Admin.Log/index',method: 'post',data: params})
}
//删除
export function del(params) {
	return request({ url: '/Admin.Log/delete',method: 'post',data: params})
}
//查看详情
export function detail(params) {
	return request({ url: '/Admin.Log/detail',method: 'post',data: params})
}
//导出
export function dumpdata(params) {
	return request({ url: '/Admin.Log/dumpdata',method: 'post',data: params})
}
//登录日志
export function loginLogs(params) {
	return request({ url: '/Admin.Log/loginLogs',method: 'post',data: params})
}
//操作日志
export function actionLogs(params) {
	return request({ url: '/Admin.Log/actionLogs',method: 'post',data: params})
}
//异常日志
export function exceptionLogs(params) {
	return request({ url: '/Admin.Log/exceptionLogs',method: 'post',data: params})
}
//简单打印
export function simplePrint(params) {
	return request({ url: '/Admin.Log/simplePrint',method: 'post',data: params})
}
