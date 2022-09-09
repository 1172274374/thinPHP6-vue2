#使用说明

[项目主页](http://www.rdscode.cn/)

## 特点说明
* 基于 Thinkphp + Vue + ElementUI + Vxe-table 构建
* 前后端分离的系统架构
* 一键生成CRUD
* 复杂实体关系一键生成前后端代码，无论是一对多还是多对多  
* 内置有丰富的操作方法
* 任意定制开发自定义方法  
* 数据列表支持多表配置和原生SQL查询
* 内置丰富的表单组件
* 一键生成API接口应用
* 一键生成接口文档
* [完善的系统文档](http://rdscode.cn)  
* QQ群技术支持
* 完整的视频教程
* 友好的二次开发支持

## 环境要求
* 需要 thinkphp 运行环境
* 需要 Vue 运行环境；
* 建议安装 Laragon 软件实现（同时实现Thinkphp和Vue需要的环境）
* [配置环境参考](http://www.rdscode.cn/doc/01_install.html)

## 后端安装及编译
* 进入项目根目录
* 执行安装依赖 composer install
* 项目运行 php think run （默认监听8000不建议修改）

## 前端安装及编译
* 进入项目根目录下的ui目录
* 安装依赖：npm install 或者 yarn install
* 启动命令行 npm run serve
* 打包命令行 npm run build

## 注意事项：
1. ui目录不要改变名称和位置
2. 后台登录账号密码 super/admin123
3. 数据库配置修改 config/database.php 或者创建 .env
4. 支持配置单设备登录或者多设备登录: multiple_login (config/rds.php)
5. 支持验证码的开关: verify_status (config/rds.php)
6. 使用短信验证码：修改“短信网关”部分 (config/rds.php)
7. 使用云存储：修改“云存储”部分(config/rds.php)
8. 开发微信小程序：mini_program (config/rds.php)
9. 开发微信公众号应用：official_accounts (config/rds.php)
10. 开发微信支付应用：wechat_pay (config/rds.php)

## 使用教程
[点击这里查看使用教程](http://www.rdscode.cn/)

