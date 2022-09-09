import Vue from 'vue'
import store from '../store'
const vm = new Vue()

const MyUtils = {
	//element confirm重新封装
	confirm: (param) => {
		// 默认参数
		let config = {
			tip: '提示',
			content: '你确定要执行此操作么吗？',
			btn: { confirm: '确定', cancel: '取消', },
			type: 'warning'
		}
		// 如果有参数传入并且长度大于0 则替换原来的指定默认配置
		if (param && Object.keys(param).length) {
			for (let item in param) {
				config[item] = param[item]
			}
		}
		return new Promise((resolve) => {
			vm.$confirm(config.content, config.tip, {
				confirmButtonText: config.btn.confirm,
				cancelButtonText: config.btn.cancel,
				type: config.type,
				dangerouslyUseHTMLString: true
			}).then(() => {
				resolve()
			}).catch(() => {
				//vm.$message({ type: 'info', message: '已取消' })
			})
		})
	},

	//根据值获取复选框 下拉多选的键名
	formatStr: (val,data) => {
		let value = [];
		if(typeof(val)=='string'){
			value = val.split(",")
		} else {
			value = val;
		}
		if(value){
			const fieldConfig = typeof(arrayData)=='string' ? JSON.parse(data) : data
			let str = ''
			value.forEach(item => {
				fieldConfig.forEach(vo=>{
					if(item == vo.value){
						str += ', ' +vo.label
					}
				})
			})
			return str.substr(1)
		}
	},

	//json对象转为url参数
	obj2Param: (json) => {
		if (!json) return ''
		return cleanArray(
			Object.keys(json).map(key => {
				if (json[key] === undefined) return ''
				return encodeURIComponent(key) + '=' + encodeURIComponent(json[key])
			})
		).join('&')
		function cleanArray (actual){
			const newArray = []
			for (let i = 0; i < actual.length; i++) {
				if (actual[i]) {
					newArray.push(actual[i])
				}
			}
			return newArray
		}
	},

	param2Obj: (url) => {
		const search = decodeURIComponent(url.split('?')[1]).replace(/\+/g, ' ')
		if (!search) {
			return {}
		}
		const obj = {}
		const searchArr = search.split('&')
		searchArr.forEach(v => {
			const index = v.indexOf('=')
			if (index !== -1) {
				const name = v.substring(0, index)
				const val = v.substring(index + 1, v.length)
				obj[name] = val
			}
		})
		return obj
	},
	checkPermission: (url) => {
		if(store.getters.role_id == 1 || store.getters.actions.includes(url)){
			return true
		}
	}
}


export default MyUtils;


// export function array2Tree(array) {
// 	let data = deepCopy(array)
// 	let result = []
// 	let map = {}
// 	if (!Array.isArray(data)) {//验证data是不是数组类型
// 		return []
// 	}
// 	data.forEach(item => {//建立每个数组元素id和该对象的关系
// 		map[item.id] = item //这里可以理解为浅拷贝，共享引用
// 	})
// 	data.forEach(item => {
// 		let parent = map[item.pid] //找到data中每一项item的爸爸
// 		if (parent) {//说明元素有爸爸，把元素放在爸爸的children下面
// 			(parent.children || (parent.children = [])).push(item)
// 		} else {//说明元素没有爸爸，是根节点，把节点push到最终结果中
// 			result.push(item) //item是对象的引用
// 		}
// 	})
// 	return result //数组里的对象和data是共享的
// }

// export function tree2Array(node) {
// 	let queue= deepCopy(node)
// 	let data = [] //返回的数组结构
// 	while (queue.length !== 0) { //当队列为空时就跳出循环
// 		let item = queue.shift() //取出队列中第一个元素
// 		if(item){
// 			data.push({
// 				val: item.val,
// 				key: item.key
// 			})
// 		}
// 		let children = item.children // 取出该节点的孩子
// 		if (children) {
// 			for (let i = 0; i < children.length; i++) {
// 				queue.push(children[i]) //将子节点加入到队列尾部
// 			}
// 		}
// 	}
// 	return data
// }

// function deepCopy(obj){
// 	// 深度复制数组
// 	if(Object.prototype.toString.call(obj) === "[object Array]"){
// 		const object=[];
// 		for(let i=0;i<obj.length;i++){
// 			object.push(deepCopy(obj[i]))
// 		}
// 		return object
// 	}
// 	// 深度复制对象
// 	if(Object.prototype.toString.call(obj) === "[object Object]"){
// 		const object={};
// 		for(let p in obj){
// 			object[p]=obj[p]
// 		}
// 		return object
// 	}
// }

// // 身份证手机号脱敏处理
// export function desensitization(str,beginLen = 4,endLen = -4){
// 	if(str){
// 		var len = str.length;
// 		var firstStr = str.substr(0,beginLen);
// 		var lastStr = str.substr(endLen);
// 		var middleStr = str.substring(beginLen, len-Math.abs(endLen)).replace(/[\s\S]/ig, '*');
// 		var tempStr = firstStr + middleStr + lastStr;
// 		return tempStr;
// 	} else {
// 		return '';
// 	}
// }

// 日期格式化
// export function parseTime(time, pattern) {
// 	if (arguments.length === 0 || !time) {
// 		return null
// 	}
// 	const format = pattern || '{y}-{m}-{d} {h}:{i}:{s}'
// 	let date
// 	if (typeof time === 'object') {
// 		date = time
// 	} else {
// 		if ((typeof time === 'string') && (/^[0-9]+$/.test(time))) {
// 			time = parseInt(time)
// 		} else if (typeof time === 'string') {
// 			time = time.replace(new RegExp(/-/gm), '/');
// 		}
// 		if ((typeof time === 'number') && (time.toString().length === 10)) {
// 			time = time * 1000
// 		}
// 		date = new Date(time)
// 	}
// 	const formatObj = {
// 		y: date.getFullYear(),
// 		m: date.getMonth() + 1,
// 		d: date.getDate(),
// 		h: date.getHours(),
// 		i: date.getMinutes(),
// 		s: date.getSeconds(),
// 		a: date.getDay()
// 	}
// 	const time_str = format.replace(/{(y|m|d|h|i|s|a)+}/g, (result, key) => {
// 		let value = formatObj[key]
// 		// Note: getDay() returns 0 on Sunday
// 		if (key === 'a') { return ['日', '一', '二', '三', '四', '五', '六'][value] }
// 		if (result.length > 0 && value < 10) {
// 			value = '0' + value
// 		}
// 		return value || 0
// 	})
// 	return time_str
// }



