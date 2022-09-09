import request from '../request'

//用户登录
export function login(params) {
  return request({ url: '/Login/index',method: 'post',data: params})
}

//用户退出
export function logout(params) {
  return request({ url: '/Login/logout',method: 'post',data: params})
}

//获取登录信息 菜单 路由组建
export function getUserInfo(params) {
  return request({ url: '/Login/getUserInfo',method: 'post',data: params})
}

//获取验证码
export function captch(params) {
  return request({ url: '/Login/verify',method: 'post',data: params})
}

//获取上传路径以及上传类型
export function upload(params) {
  return request({ url: '/Upload/uploadImages',method: 'post',data: params})
}

//文件列表
export function fileList(params) {
	return request({ url: '/Base/fileList',method: 'post',data: params})
}

//删除文件
export function deleteFile(params) {
	return request({ url: '/Base/deleteFile',method: 'post',data: params})
}

//删除文件
export function getRoleMenus(params) {
	return request({ url: '/Base/getRoleMenus',method: 'post',data: params})
}

//重置密码
export function resetPwd(params) {
	return request({ url: '/Base/resetPwd',method: 'post',data: params})
}

//首页数据
export function homeData(params) {
	return request({ url: '/Home/index',method: 'post',data: params})
}

//清除缓存
export function clearCache(params) {
	return request({ url: '/Base/clearCache',method: 'post',data: params})
}

//oss直传返回的文件写入库
export function createFile(params) {
	return request({ url: '/Upload/createFile',method: 'post',data: params})
}

//回去头部的消息通知
export function getNotice(params) {
	return request({ url: '/Home/getNotice',method: 'post',data: params})
}

// 查询Index页面表格默认配置
export function getMenuStatus(params) {
	return request({ url: '/Base/getMenuStatus',method: 'post',data: params})
}

// 保存Index页面表格的默认配置
export function saveMenuStatus(params) {
	return request({ url: '/Base/saveMenuStatus',method: 'post',data: params})
}

// 删除index页面的默认设置
export function deleteMenuStatus(params) {
	return request({ url: '/Base/deleteMenuStatus',method: 'post',data: params})
}

// 查询系统设置
export function getSetting(params) {
	return request({ url: '/Base/getSetting',method: 'post',data: params})
}

// 查询系统设置
export function saveSetting(params) {
	return request({ url: '/Base/saveSetting',method: 'post',data: params})
}

// 查询系统设置
export function deleteSetting(params) {
	return request({ url: '/Base/deleteSetting',method: 'post',data: params})
}
