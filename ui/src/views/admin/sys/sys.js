import request from '@/api/request'


export function getUrl(params) {
    return request({url: '/Sys.Base/getUrl', method: 'post', data: params})
}

//获取应用  列表
export function applicationList(params) {
    return request({ url: '/Sys.Base/applicationList',method: 'post',data: params})
}

//创建、修改应用
export function createApplication(params) {
    return request({ url: '/Sys.Base/createApplication',method: 'post',data: params})
}

//创建、修改应用
export function updateApplication(params) {
    return request({ url: '/Sys.Base/updateApplication',method: 'post',data: params})
}

//获取应用详情
export function getApplicationInfo(params) {
    return request({ url: '/Sys.Base/getApplicationInfo',method: 'post',data: params})
}

//创建、修改应用
export function buildApplication(params) {
    return request({ url: '/Sys.Base/buildApplication',method: 'post',data: params})
}

//删除应用
export function deleteApplication(params) {
    return request({ url: '/Sys.Base/deleteApplication',method: 'post',data: params})
}

//菜单列表
export function getMenuList(params) {
    return request({ url: '/Sys.Base/getMenuList',method: 'post',data: params})
}

//创建菜单
export function createMenu(params) {
    return request({ url: '/Sys.Base/createMenu',method: 'post',data: params})
}

//更新菜单
export function updateMenu(params) {
    return request({ url: '/Sys.Base/updateMenu',method: 'post',data: params})
}

//删除菜单
export function deleteMenu(params) {
    return request({ url: '/Sys.Base/deleteMenu',method: 'post',data: params})
}

//获取菜单信息
export function getMenuInfo(params) {
    return request({ url: '/Sys.Base/getMenuInfo',method: 'post',data: params})
}


//获取当前菜单的字段列表
export function fieldList(params) {
    return request({ url: '/Sys.Base/fieldList',method: 'post',data: params})
}

//创建字段
export function createField(params) {
    return request({ url: '/Sys.Base/createField',method: 'post',data: params})
}

//获取字段信息
export function getFieldInfo(params) {
    return request({ url: '/Sys.Base/getFieldInfo',method: 'post',data: params})
}

//修改字段信息
export function updateField(params) {
    return request({ url: '/Sys.Base/updateField',method: 'post',data: params})
}

//删除字段
export function deleteField(params) {
    return request({ url: '/Sys.Base/deleteField',method: 'post',data: params})
}

//更新字段排序
export function updateFieldSort(params) {
    return request({ url: '/Sys.Base/updateFieldSort',method: 'post',data: params})
}

//导出当前菜单的字段定义数据
export function exportExcel(params) {
    return request({ url: '/Sys.Base/exportExcel',method: 'post',data: params})
}

//获取方法列表
export function actionList(params) {
    return request({ url: '/Sys.Base/actionList',method: 'post',data: params})
}

//获取提交字段
export function getPostField(params) {
    return request({ url: '/Sys.Base/getPostField',method: 'post',data: params})
}

//获取方法详情
export function getActionInfo(params) {
    return request({ url: '/Sys.Base/getActionInfo',method: 'post',data: params})
}

//创建方法
export function createAction(params) {
    return request({ url: '/Sys.Base/createAction',method: 'post',data: params})
}

//快速创建方法
export function quckCreateAction(params) {
    return request({ url: '/Sys.Base/quckCreateAction',method: 'post',data: params})
}

//修改信息
export function updateAction(params) {
    return request({ url: '/Sys.Base/updateAction',method: 'post',data: params})
}

//删除方法
export function deleteAction(params) {
    return request({ url: '/Sys.Base/deleteAction',method: 'post',data: params})
}

//更新方法排序
export function updateActionSort(params) {
    return request({ url: '/Sys.Base/updateActionSort',method: 'post',data: params})
}

//获取链接库的table信息
export function getTableList(params) {
    return request({ url: '/Sys.Base/getTables',method: 'post',data: params})
}

//获取默认的选项 信息 如单选框 多选框等选项
export function getConfigList(params) {
    return request({ url: '/Sys.Base/configList',method: 'post',data: params})
}

//生成
export function create(params) {
    return request({ url: '/Sys.Base/create',method: 'post',data: params})
}

export function updateFieldExt(params) {
    return request({ url: '/Sys.Base/updateFieldExt',method: 'post',data: params})
}

export function updateMenuExt(params) {
    return request({ url: '/Sys.Base/updateMenuExt',method: 'post',data: params})
}

export function updateActionExt(params) {
    return request({ url: '/Sys.Base/updateActionExt',method: 'post',data: params})
}

export function getAppInfo(params) {
    return request({ url: '/Sys.Base/getAppInfo',method: 'post',data: params})
}


export function getAppType(params) {
    return request({ url: '/Sys.Base/getAppType',method: 'post',data: params})
}

export function createByTable(params) {
    return request({ url: '/Sys.Base/createByTable',method: 'post',data: params})
}

//获取表字段表信息
export function getTableFields(params) {
    return request({ url: '/Sys.Base/getTableFields',method: 'post',data: params})
}

//获取表字段表信息
export function getTablesByMenuId(params) {
    return request({ url: '/Sys.Base/getTablesByMenuId',method: 'post',data: params})
}

//获取上传配置列表
export function getUploadList(params) {
    return request({ url: '/Sys.Base/getUploadList',method: 'post',data: params})
}

//获取上传配置列表
export function copyMenu(params) {
    return request({ url: '/Sys.Base/copyMenu',method: 'post',data: params})
}

//检测cms字段设置
export function checkCmsField(params) {
    return request({ url: '/Sys.Base/checkCmsField',method: 'post',data: params})
}

//应用秘钥
export function index(params) {
	return request({ url: '/Sys.Secrect/index',method: 'post',data: params})
}
export function getInfo(params) {
	return request({ url: '/Sys.Secrect/getInfo',method: 'post',data: params})
}

// 生成API文档
export function getDoc(params){
    return request({ url: '/Sys.Base/getDoc',method: 'post',data: params})
}

// 获取API文档地址
export function getDocAdress(params){
    return request({ url: '/Sys.Base/getDocAdress',method: 'post',data: params})
}

// 获取API文档地址
export function getAppDocList(params){
    return request({ url: '/Sys.Base/getAppDocList',method: 'post',data: params})
}

// 获取API文档地址
export function getAppDocHelp(params){
    return request({ url: '/Sys.Base/getAppDocHelp',method: 'post',data: params})
}

// 菜单迁移
export function exportMenuSQL(params) {
    return request({ url: '/Sys.Base/exportMenuSQL',method: 'post',data: params})
}

// 同步复制
export function synCopy(params) {
    return request({ url: '/Sys.Base/synCopy',method: 'post',data: params})
}