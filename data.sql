-- MySQL dump 10.13  Distrib 5.7.33, for Win64 (x86_64)
--
-- Host: localhost    Database: up
-- ------------------------------------------------------
-- Server version	5.7.33

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cd_admin_chart`
--

DROP TABLE IF EXISTS `cd_admin_chart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cd_admin_chart` (
  `chart_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL COMMENT '名称',
  `type` smallint(6) DEFAULT NULL COMMENT '类型',
  `dimensions` varchar(250) DEFAULT NULL COMMENT '维度顺序',
  `source_data` text COMMENT '基础数据源',
  `source_sql` text COMMENT 'SQL数据源',
  `source` smallint(6) DEFAULT NULL COMMENT '数据源类型',
  `sort_id` int(11) DEFAULT NULL COMMENT '排序',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态',
  `creater_id` int(11) DEFAULT NULL COMMENT '所有者',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`chart_id`),
  KEY `sort_id` (`sort_id`),
  KEY `status` (`status`),
  KEY `creater_id` (`creater_id`),
  KEY `create_time` (`create_time`),
  KEY `update_time` (`update_time`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cd_admin_chart`
--

LOCK TABLES `cd_admin_chart` WRITE;
/*!40000 ALTER TABLE `cd_admin_chart` DISABLE KEYS */;
INSERT INTO `cd_admin_chart` VALUES (1,'产品销售数据',2,'product,2015,2016,2017','\"Matcha Latte\",43.3,85.8,93.7\n\"Milk Tea\",83.1,73.4,55.1\n\"Cheese Cocoa\",86.4,65.2,82.5\n\"Walnut Brownie\",72.4,53.9,39.1','SELECT `production`, `2015`, `2016`, `2017` FROM `cd_data` ',2,1,1,1,1654422731,1654429167),(2,'部门销售数据',1,'department,2020,2021,2022','华北区,100,112,130\n华东区,90,89,120,100\n华南区,101,91,120,99','',1,2,1,1,1654428982,1654429170);
/*!40000 ALTER TABLE `cd_admin_chart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cd_admin_config`
--

DROP TABLE IF EXISTS `cd_admin_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cd_admin_config` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(50) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`config_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cd_admin_config`
--

LOCK TABLES `cd_admin_config` WRITE;
/*!40000 ALTER TABLE `cd_admin_config` DISABLE KEYS */;
INSERT INTO `cd_admin_config` VALUES (1,'site_title','RDS'),(2,'site_logo','http://rds-share.oss-cn-hangzhou.aliyuncs.com/admin/202206/202206211621270184272.png'),(3,'keyword','快速开发,vue,element-ui,thinkphp'),(4,'site_desc','基于 Thinkphp + Vue + ElementUI + VxeTable 的前后端分离的快速开发脚手架 ( Rapid Development System )'),(5,'copyright','此处填写版权信息'),(6,'filesize','100'),(7,'filetype','gif,png,jpg,jpeg,doc,docx,xls,xlsx,csv,pdf,rar,zip,txt,mp4,flv'),(8,'water_status','1'),(9,'water_position','5'),(10,'domain','http://127.0.0.1:8000'),(11,'water_alpha','45'),(12,'apidoc_desc','# 如何生成API文档\n\n## 前提条件\n- 安装了apidoc\n- 正确的创建了前端应用，并且apidoc.json配置文件存在且配置正确。\n## 文档生成\n- 默认文档生成在Thinkphp项目的发布目录，即public目录\n- 访问地址会在生成成功的时候提示用户。\n## 生成文档\n```\napidoc -i ./ -o ../../public/doc\n```'),(13,'show_default_menu','0'),(14,'show_statisic','2'),(15,'show_menu','2'),(16,'show_chart','1'),(17,'hobby','[{\"label\":\"打球\",\"value\":\"1\"},{\"label\":\"游泳\",\"value\":\"2\"},{\"label\":\"摄影\",\"value\":\"3\"},{\"label\":\"音乐\",\"value\":\"4\"}]');
/*!40000 ALTER TABLE `cd_admin_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cd_admin_dept`
--

DROP TABLE IF EXISTS `cd_admin_dept`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cd_admin_dept` (
  `dept_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `name` varchar(250) DEFAULT NULL COMMENT '名称',
  `pid` smallint(6) DEFAULT NULL COMMENT '所属部门',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态',
  `sort_id` int(11) DEFAULT NULL COMMENT '排序',
  `note` text COMMENT '说明',
  PRIMARY KEY (`dept_id`),
  KEY `create_time` (`create_time`),
  KEY `update_time` (`update_time`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cd_admin_dept`
--

LOCK TABLES `cd_admin_dept` WRITE;
/*!40000 ALTER TABLE `cd_admin_dept` DISABLE KEYS */;
INSERT INTO `cd_admin_dept` VALUES (1,1641802992,0,'根部门',0,1,1,'所有部门的起点，不建议删除');
/*!40000 ALTER TABLE `cd_admin_dept` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cd_admin_file`
--

DROP TABLE IF EXISTS `cd_admin_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cd_admin_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filepath` varchar(255) DEFAULT NULL COMMENT '图片路径',
  `hash` varchar(32) DEFAULT NULL COMMENT '文件hash值',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `hash` (`hash`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cd_admin_file`
--

LOCK TABLES `cd_admin_file` WRITE;
/*!40000 ALTER TABLE `cd_admin_file` DISABLE KEYS */;
INSERT INTO `cd_admin_file` VALUES (9,'http://rds-share.oss-cn-hangzhou.aliyuncs.com/admin/202206/202206121438120181404.jpg','2e332855ada0d9833a529e9fd809bf4f',1655015893),(10,'http://rds-share.oss-cn-hangzhou.aliyuncs.com/admin/202206/202206211620590111990.png','c6146a508680f2a38f9544e5994ee5bc',1655799659),(11,'http://rds-share.oss-cn-hangzhou.aliyuncs.com/admin/202206/202206211621270184272.png','1bd109695d92f72be5fb9f29a1676978',1655799688),(12,'http://rds-share.oss-cn-hangzhou.aliyuncs.com/admin/202206/202206211633500118510.png','6ea1cc67156f057aaa2ba5a7db1e489c',1655800430),(13,'http://rds-share.oss-cn-hangzhou.aliyuncs.com/admin/202206/202206211643150157962.png','a0581975e8f6bc904a5c33b51f799200',1655800996),(14,'http://rds-share.oss-cn-hangzhou.aliyuncs.com/admin/202206/202206211643200112056.png','c7afb54dbcefa7e22de6366100f03e22',1655801000),(15,'http://rds-share.oss-cn-hangzhou.aliyuncs.com/admin/202206/202206211702210153075.png','6370cd68f84710a363332664264ce5af',1655802141),(16,'http://rds-share.oss-cn-hangzhou.aliyuncs.com/admin/202206/202206211702240167270.png','a836c9ce6d079365ff2d1f673f4c0222',1655802145),(17,'http://rds-share.oss-cn-hangzhou.aliyuncs.com/admin/202206/202206211724150105848.png','fa70e02a01512794f60b1d32ef9780c8',1655803455),(18,'http://rds-share.oss-cn-hangzhou.aliyuncs.com/admin/202206/202206211724300159324.png','4eba124dc0e10d9201d528375cc5c9da',1655803471);
/*!40000 ALTER TABLE `cd_admin_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cd_admin_log`
--

DROP TABLE IF EXISTS `cd_admin_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cd_admin_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_name` varchar(50) DEFAULT NULL COMMENT '应用名称',
  `username` varchar(250) DEFAULT NULL COMMENT '操作用户',
  `url` varchar(250) DEFAULT NULL COMMENT '请求url',
  `ip` varchar(250) DEFAULT NULL COMMENT 'ip',
  `useragent` varchar(250) DEFAULT NULL COMMENT 'useragent',
  `content` text COMMENT '请求内容',
  `errmsg` varchar(250) DEFAULT NULL COMMENT '异常信息',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `type` smallint(6) DEFAULT NULL COMMENT '类型',
  `times` int(11) DEFAULT NULL COMMENT '日期',
  `sort_id` int(11) DEFAULT NULL COMMENT '排序',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `update_time` (`update_time`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cd_admin_log`
--

LOCK TABLES `cd_admin_log` WRITE;
/*!40000 ALTER TABLE `cd_admin_log` DISABLE KEYS */;
INSERT INTO `cd_admin_log` VALUES (1,'admin',NULL,'http://127.0.0.1:8000/admin/Login/getUserInfo','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36','[]','登录状态已过期，请重新登录',1660990596,3,NULL,NULL,NULL,NULL),(2,'admin','super','http://127.0.0.1:8000/admin/Login/index','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',NULL,NULL,1660990608,1,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `cd_admin_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cd_admin_role`
--

DROP TABLE IF EXISTS `cd_admin_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cd_admin_role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(36) DEFAULT NULL COMMENT '分组名称',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态',
  `pid` smallint(6) DEFAULT NULL COMMENT '所属父类',
  `description` text COMMENT '描述',
  `access` longtext COMMENT '权限节点',
  PRIMARY KEY (`role_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cd_admin_role`
--

LOCK TABLES `cd_admin_role` WRITE;
/*!40000 ALTER TABLE `cd_admin_role` DISABLE KEYS */;
INSERT INTO `cd_admin_role` VALUES (1,'超级管理员',1,NULL,'系统最高权限用户，不建议删除','Home,System,Admin/User,/Admin/User/index.html,/Admin/User/updateExt.html,/Admin/User/add.html,/Admin/User/update.html,/Admin/User/delete.html,/Admin/User/resetPwd.html,Admin/Role,/Admin/Role/index.html,/Admin/Role/updateExt.html,/Admin/Role/add.html,/Admin/Role/update.html,/Admin/Role/delete.html,Admin/Dept,/Admin/Dept/index.html,/Admin/Dept/updateExt.html,/Admin/Dept/add.html,/Admin/Dept/update.html,/Admin/Dept/delete.html,/Admin/Dept/detail.html,Admin/Log,/Admin/Log/index.html,/Admin/Log/delete.html,/Admin/Log/detail.html,/Admin/Log/dumpdata.html,Admin/Config,/Admin/Config/index.html,Uploadconfig,/admin/Uploadconfig/index.html,/admin/Uploadconfig/updateExt.html,/admin/Uploadconfig/add.html,/admin/Uploadconfig/update.html,/admin/Uploadconfig/delete.html,/admin/Uploadconfig/detail.html,Admin/Dictionary,/Admin/Dictionary/index.html,Admin/Member,/Admin/Member/index.html,/Admin/Member/updateExt.html,/Admin/Member/add.html,/Admin/Member/update.html,/Admin/Member/delete.html,/Admin/Member/detail.html,Tool,Application,Menu,Field,Action,Sys/Secrect'),(2,'系统管理员',1,NULL,'系统管理员','Home,System,Admin/User,/Admin/User/index.html,/Admin/User/updateExt.html,/Admin/User/add.html,/Admin/User/update.html,/Admin/User/delete.html,/Admin/User/resetPwd.html,Admin/Role,/Admin/Role/index.html,/Admin/Role/updateExt.html,/Admin/Role/add.html,/Admin/Role/update.html,/Admin/Role/delete.html,Admin/Dept,/Admin/Dept/index.html,/Admin/Dept/updateExt.html,/Admin/Dept/add.html,/Admin/Dept/update.html,/Admin/Dept/delete.html,/Admin/Dept/detail.html,Admin/Member,/Admin/Member/index.html,/Admin/Member/updateExt.html,/Admin/Member/add.html,/Admin/Member/update.html,/Admin/Member/delete.html,/Admin/Member/detail.html,Admin/Dictionary,/Admin/Dictionary/index.html,Admin/Config,/Admin/Config/index.html,Uploadconfig,/admin/Uploadconfig/index.html,/admin/Uploadconfig/updateExt.html,/admin/Uploadconfig/add.html,/admin/Uploadconfig/update.html,/admin/Uploadconfig/delete.html,/admin/Uploadconfig/detail.html,Admin/Log,/Admin/Log/index.html,/Admin/Log/delete.html,/Admin/Log/detail.html,/Admin/Log/dumpdata.html,Tool,Menu,Field,Action,Application,Sys/Secrect'),(3,'测试角色',1,NULL,'测试角色','Home');
/*!40000 ALTER TABLE `cd_admin_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cd_admin_statisic`
--

DROP TABLE IF EXISTS `cd_admin_statisic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cd_admin_statisic` (
  `statisic_id` int(11) NOT NULL AUTO_INCREMENT,
  `current_name` varchar(250) DEFAULT NULL COMMENT '当前标题',
  `unit` varchar(250) DEFAULT NULL COMMENT '单位名称',
  `unit_color` varchar(250) DEFAULT '#4AB7BD' COMMENT '名称背景色',
  `current_value` varchar(250) DEFAULT NULL COMMENT '当前值',
  `total_name` varchar(250) DEFAULT NULL COMMENT '累计标题',
  `total_value` varchar(250) DEFAULT NULL COMMENT '累计值',
  `total_sql` text COMMENT '累计SQL',
  `total_type` smallint(6) DEFAULT NULL COMMENT '累计值类型',
  `current_type` smallint(6) DEFAULT NULL COMMENT '当前值类型',
  `current_sql` text COMMENT '当前SQL',
  `sort_id` int(11) DEFAULT NULL COMMENT '排序',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态',
  `creater_id` int(11) DEFAULT NULL COMMENT '所有者',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`statisic_id`),
  KEY `sort_id` (`sort_id`),
  KEY `status` (`status`),
  KEY `creater_id` (`creater_id`),
  KEY `create_time` (`create_time`),
  KEY `update_time` (`update_time`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cd_admin_statisic`
--

LOCK TABLES `cd_admin_statisic` WRITE;
/*!40000 ALTER TABLE `cd_admin_statisic` DISABLE KEYS */;
INSERT INTO `cd_admin_statisic` VALUES (1,'当日订单数','日','rgba(164, 8, 221, 1)','45','累计订单数','4500','',1,1,'',1,1,1,1654411253,1654412202),(2,'当日销售额','日','rgba(242, 59, 13, 1)','5600','累计销售额','67000','',1,1,'',2,1,1,1654411307,1654412191),(3,'本周新客','周','#4AB7BD','409','累计客户数','8900','',1,1,'',3,1,1,1654411371,NULL),(4,'本月退款数','月','rgba(8, 196, 11, 1)','0','累计退款数','56','SELECT COUNT(id) AS VALUE FROM cd_admin_log where type = 1',2,2,'SELECT COUNT(id) AS VALUE FROM cd_admin_log where type = 2',4,1,1,1654411426,1654412626);
/*!40000 ALTER TABLE `cd_admin_statisic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cd_admin_token`
--

DROP TABLE IF EXISTS `cd_admin_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cd_admin_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL COMMENT '用户名',
  `token` mediumtext NOT NULL,
  `expire_time` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `dev_status` tinyint(4) DEFAULT '1' COMMENT '1正常 0下线',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cd_admin_token`
--

LOCK TABLES `cd_admin_token` WRITE;
/*!40000 ALTER TABLE `cd_admin_token` DISABLE KEYS */;
INSERT INTO `cd_admin_token` VALUES (1,'super','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJwYXlsb2FkIjoie1widXNlcl9pZFwiOjEsXCJuYW1lXCI6XCJcXHU2ODM5XFx1N2JhMVxcdTc0MDZcXHU1NDU4XCIsXCJ1c2VybmFtZVwiOlwic3VwZXJcIixcInN0YXR1c1wiOjEsXCJ1c2VyX3JvbGVfaWRzXCI6MSxcInBlcm1pc3Npb25cIjoxLFwicm9sZV9pZFwiOjEsXCJyb2xlX25hbWVcIjpcIlxcdThkODVcXHU3ZWE3XFx1N2JhMVxcdTc0MDZcXHU1NDU4XCIsXCJyb2xlX3N0YXR1c1wiOjEsXCJkZXB0X2lkXCI6MSxcImRlcHRfbmFtZVwiOlwiXFx1NjgzOVxcdTkwZThcXHU5NWU4XCIsXCJkZXB0X3N0YXR1c1wiOjF9IiwiaXNzIjoicmRzLnNlcnZlciIsImF1ZCI6InJkcy5jbGllbnQiLCJpYXQiOiIxNjYwOTkwNjA4LjI0Njg3NSIsIm5iZiI6IjE2NjA5OTA2MDkuMjQ2ODc1IiwiZXhwIjoiMTY2MTA3NzAwOC4yNDY4NzUifQ.2PPyHUvzezUcNQzwo46nnrX62gclTp1m_tW0qYiV-Cg',NULL,1,1);
/*!40000 ALTER TABLE `cd_admin_token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cd_admin_upload_config`
--

DROP TABLE IF EXISTS `cd_admin_upload_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cd_admin_upload_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) DEFAULT NULL COMMENT '配置名称',
  `upload_replace` tinyint(4) DEFAULT NULL COMMENT '覆盖同名文件',
  `thumb_status` tinyint(4) DEFAULT NULL COMMENT '缩图开关',
  `thumb_width` varchar(250) DEFAULT NULL COMMENT '缩放宽度',
  `thumb_height` varchar(250) DEFAULT NULL COMMENT '缩放高度',
  `thumb_type` smallint(6) DEFAULT NULL COMMENT '缩图方式',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cd_admin_upload_config`
--

LOCK TABLES `cd_admin_upload_config` WRITE;
/*!40000 ALTER TABLE `cd_admin_upload_config` DISABLE KEYS */;
INSERT INTO `cd_admin_upload_config` VALUES (1,'默认配置',1,1,'600','400',6);
/*!40000 ALTER TABLE `cd_admin_upload_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cd_admin_user`
--

DROP TABLE IF EXISTS `cd_admin_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cd_admin_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `user` varchar(250) DEFAULT NULL COMMENT '用户名',
  `name` varchar(250) DEFAULT NULL COMMENT '真实姓名',
  `headimg` varchar(250) DEFAULT NULL COMMENT '头像',
  `email` varchar(250) DEFAULT NULL COMMENT '邮箱',
  `mobile` varchar(250) DEFAULT NULL COMMENT '手机号',
  `pwd` varchar(250) DEFAULT NULL COMMENT '密码',
  `note` varchar(250) DEFAULT NULL COMMENT '备注',
  `role_id` smallint(6) DEFAULT '1' COMMENT '所属角色',
  `dept_id` varchar(250) DEFAULT '1' COMMENT '所属部门',
  `permission` smallint(6) DEFAULT '1' COMMENT '数据权限',
  `expiration` int(11) DEFAULT NULL COMMENT '过期时间',
  `sort_id` int(11) DEFAULT NULL COMMENT '排序',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`user_id`) USING BTREE,
  KEY `update_time` (`update_time`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cd_admin_user`
--

LOCK TABLES `cd_admin_user` WRITE;
/*!40000 ALTER TABLE `cd_admin_user` DISABLE KEYS */;
INSERT INTO `cd_admin_user` VALUES (1,'super','根管理员','http://rds-share.oss-cn-hangzhou.aliyuncs.com/admin/202206/202206211621270184272.png','super@qq.com','13788889999','e7b2ee5f61147940c27cd9aa5b97cf73','超级用户，不可删除',1,'1',1,NULL,1,1,1641803247,0),(2,'admin','业务管理员','http://rds-share.oss-cn-hangzhou.aliyuncs.com/admin/202206/202206211621270184272.png','admin@test.com','13989891189','e7b2ee5f61147940c27cd9aa5b97cf73','业务管理员',1,'1',1,NULL,2,1,1641814961,0),(3,'test','测试用户','http://rds-share.oss-cn-hangzhou.aliyuncs.com/admin/202206/202206211621270184272.png','test@163.com','13866667777','2e9c8c22380433d3e0a317e013ad144b','测试数据隔离',3,'1',4,NULL,3,1,1641821080,0);
/*!40000 ALTER TABLE `cd_admin_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cd_data`
--

DROP TABLE IF EXISTS `cd_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cd_data` (
  `chart_id` int(11) NOT NULL AUTO_INCREMENT,
  `production` varchar(250) DEFAULT NULL,
  `2015` decimal(10,2) DEFAULT NULL,
  `2016` decimal(10,2) DEFAULT NULL,
  `2017` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`chart_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cd_data`
--

LOCK TABLES `cd_data` WRITE;
/*!40000 ALTER TABLE `cd_data` DISABLE KEYS */;
INSERT INTO `cd_data` VALUES (1,'Matcha Latte',43.30,85.80,93.70),(2,'Milk Tea',83.10,73.40,55.10),(3,'Cheese Cocoa',86.40,65.20,82.50),(4,'Walnut Brownie',72.40,53.90,39.10);
/*!40000 ALTER TABLE `cd_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cd_sys_action`
--

DROP TABLE IF EXISTS `cd_sys_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cd_sys_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(9) NOT NULL COMMENT '模块ID',
  `name` varchar(255) DEFAULT NULL COMMENT '动作名称',
  `action_name` varchar(128) NOT NULL COMMENT '动作名称',
  `type` tinyint(4) NOT NULL,
  `icon` varchar(32) DEFAULT NULL COMMENT 'icon图标',
  `pagesize` varchar(5) DEFAULT '' COMMENT '每页显示数据条数',
  `group_button_status` tinyint(4) DEFAULT NULL COMMENT '按钮组显示状态',
  `list_button_status` tinyint(4) DEFAULT NULL COMMENT '按钮是否显示列表',
  `button_color` varchar(20) DEFAULT NULL,
  `fields` text COMMENT '操作的字段',
  `sortid` mediumint(9) DEFAULT '0' COMMENT '排序',
  `orderby` varchar(250) DEFAULT NULL COMMENT '配置排序',
  `tree_config` varchar(50) DEFAULT NULL,
  `jump` varchar(120) DEFAULT NULL COMMENT '按钮跳转地址',
  `server_create_status` tinyint(4) DEFAULT '1',
  `vue_create_status` tinyint(4) DEFAULT '1' COMMENT '视图生成',
  `cache_time` mediumint(9) DEFAULT NULL COMMENT '缓存时间',
  `api_auth` tinyint(4) DEFAULT NULL COMMENT '接口是否鉴权',
  `img_auth` tinyint(4) DEFAULT NULL COMMENT '图片验证码鉴权',
  `sms_auth` tinyint(4) DEFAULT NULL COMMENT '短信验证',
  `list_filter` varchar(255) DEFAULT NULL COMMENT '过滤',
  `tab_config` text,
  `sql` text,
  `dialog_size` varchar(20) DEFAULT NULL COMMENT '对话框大小',
  `status_val` varchar(255) DEFAULT NULL,
  `validate` varchar(50) DEFAULT NULL,
  `action_width` int(11) DEFAULT NULL COMMENT '操作列宽度',
  `select_type` tinyint(4) DEFAULT '1' COMMENT '选中方式 1多选 2单选',
  `table_height` varchar(20) DEFAULT NULL COMMENT '表格高度',
  `left_tree_sql` varchar(250) DEFAULT NULL COMMENT '侧栏生成的sql',
  `with_join` text COMMENT '关联模型',
  `other_config` mediumtext COMMENT '登录字段配置',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `menu_id` (`menu_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cd_sys_action`
--

LOCK TABLES `cd_sys_action` WRITE;
/*!40000 ALTER TABLE `cd_sys_action` DISABLE KEYS */;
INSERT INTO `cd_sys_action` VALUES (6,8,'删除','delete',4,'el-icon-delete','',1,1,'danger',NULL,5,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(5,8,'重置密码','resetPwd',6,'el-icon-lock','20',1,NULL,'info','pwd',3290,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,'','','','',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(4,8,'修改','update',3,'el-icon-edit','',1,1,'primary','',4,'','','',1,1,0,0,0,0,'','[{\"tab_fields\":[\"user\",\"pwd\",\"role_id\",\"dept_id\",\"permission\",\"headimg\"],\"tab_name\":\"基本信息\"},{\"tab_fields\":[\"name\",\"email\",\"mobile\",\"note\",\"status\",\"sort_id\"],\"tab_name\":\"其他信息\"}]','','600px','','',NULL,1,'','','null','\"\"'),(3,8,'添加','add',2,'el-icon-plus','',1,0,'success','',3,'','','',1,1,0,0,0,0,'','[{\"tab_fields\":[\"user\",\"pwd\",\"role_id\",\"dept_id\",\"permission\",\"headimg\"],\"tab_name\":\"基本信息\"},{\"tab_fields\":[\"name\",\"email\",\"mobile\",\"note\",\"status\",\"sort_id\"],\"tab_name\":\"其他信息\"}]','','600px','','',NULL,1,'','','null','\"\\\"\\\\\\\"\\\\\\\"\\\"\"'),(2,8,'修改排序开关','updateExt',12,NULL,'',0,NULL,NULL,NULL,2,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(1,8,'数据列表','index',1,'','20',0,0,'','',1,'','','',1,1,0,0,0,0,'','','','','','',NULL,2,'','','[{\"fields\":[\"name\"],\"fk\":\"role_id\",\"relative_table\":\"Admin\\/Role\",\"pk\":\"role_id\",\"table_name\":\"admin_role\",\"connect\":\"mysql\"},{\"fields\":[\"name\"],\"fk\":\"dept_id\",\"relative_table\":\"Admin\\/Dept\",\"pk\":\"dept_id\",\"table_name\":\"admin_dept\",\"connect\":\"mysql\"}]','\"null\"'),(11,9,'数据列表','index',1,NULL,'20',0,NULL,NULL,NULL,1,NULL,NULL,NULL,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(10,9,'修改排序开关','updateExt',12,NULL,'',0,NULL,NULL,NULL,2,NULL,NULL,NULL,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(9,9,'添加','add',2,'el-icon-plus','',1,NULL,'success',NULL,3,NULL,NULL,NULL,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'600px',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(8,9,'修改','update',3,'el-icon-edit','',1,1,'primary',NULL,4,NULL,NULL,NULL,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'600px',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(7,9,'删除','delete',4,'el-icon-delete','',1,1,'danger',NULL,5,NULL,NULL,NULL,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(32,13,'数据列表','index',1,NULL,'20',0,NULL,NULL,'',1,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,1,'',NULL,'null','\"null\"'),(31,13,'删除','delete',4,'el-icon-delete','',1,1,'danger',NULL,5,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(30,13,'查看详情','detail',5,'el-icon-view','',1,NULL,'info','application_name,username,url,ip,useragent,content,type,create_time,errmsg',6,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,'','',NULL,'600px',NULL,NULL,NULL,1,NULL,NULL,'',NULL),(24,12,'查看详情','detail',5,'el-icon-view','',1,NULL,'info',NULL,6,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'600px',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(23,12,'删除','delete',4,'el-icon-delete','',1,1,'danger',NULL,5,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(22,12,'数据列表','index',1,'','20',0,0,'','',1,'id','','',1,1,0,0,0,0,'','','','','','',NULL,1,'','','null','\"\"'),(21,12,'修改排序开关','updateExt',12,NULL,'',0,NULL,NULL,NULL,2,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(20,12,'添加','add',2,'el-icon-plus','',1,NULL,'success',NULL,3,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'600px',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(19,12,'修改','update',3,'el-icon-edit','',1,1,'primary',NULL,4,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'600px',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(18,11,'基本配置','index',14,'','',0,0,'','',-1,'','','',1,1,0,0,0,0,'','[{\"tab_fields\":[\"site_title\",\"logo\",\"keyword\",\"deion\",\"copyright\",\"ip_white\",\"kg\",\"site_logo\",\"deion\",\"show_default_menu\",\"site_desc\"],\"tab_name\":\"基本信息\"},{\"tab_fields\":[\"filsesize\",\"filetype\",\"water_status\",\"water_position\",\"water_logo\",\"domain\",\"filesize\",\"water_alpha\"],\"tab_name\":\"上传配置\"},{\"tab_fields\":[\"show_menu\",\"show_statisical\",\"show_chart\",\"show_statisic\"],\"tab_name\":\"首页配置\"},{\"tab_fields\":[\"hobby\"],\"tab_name\":\"数据字典\"}]','','','','',0,1,'','','null',''),(29,13,'导出','dumpdata',11,'el-icon-download','',1,NULL,'warning',NULL,12,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(17,10,'修改','update',3,'el-icon-edit',NULL,1,1,'primary',NULL,4,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,'',NULL,'600px',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(16,10,'添加','add',2,'el-icon-plus','',1,0,'success','',3,'','','',1,1,0,0,0,0,'','','','600px','','',NULL,1,'','','null',NULL),(15,10,'数据列表','index',1,'','20',0,0,'','',1,'','pid,name','',1,1,0,0,0,0,'','','','','','',NULL,1,'','','null','\"\\\"\\\"\"'),(14,10,'修改排序开关','updateExt',12,NULL,NULL,0,0,NULL,NULL,2,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(13,10,'删除','delete',4,'el-icon-delete',NULL,1,1,'danger',NULL,5,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(12,10,'查看详情','detail',5,'el-icon-view',NULL,1,0,'info',NULL,6,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'600px',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(37,14,'删除','delete',4,'el-icon-delete',NULL,1,1,'danger',NULL,5,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(36,14,'数据列表','index',1,NULL,'20',0,0,NULL,NULL,1,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(35,14,'修改排序开关','updateExt',12,NULL,NULL,0,0,NULL,NULL,2,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(34,14,'添加','add',2,'el-icon-plus','',1,0,'success','',3,'','','',1,1,0,0,0,0,'','[{\"tab_fields\":[\"unit\",\"unit_color\",\"current_name\",\"current_type\",\"current_value\",\"current_sql\"],\"tab_name\":\"当前值\"},{\"tab_fields\":[\"total_name\",\"total_value\",\"total_sql\",\"total_type\"],\"tab_name\":\"累计值\"},{\"tab_fields\":[\"sort_id\",\"status\"],\"tab_name\":\"其他\"}]','','60%','','',0,1,'','','null',''),(33,14,'修改','update',3,'el-icon-edit',NULL,1,1,'primary',NULL,4,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,'[{\"tab_fields\":[\"unit\",\"unit_color\",\"current_name\",\"current_type\",\"current_value\",\"current_sql\"],\"tab_name\":\"当前值\"},{\"tab_fields\":[\"total_name\",\"total_value\",\"total_sql\",\"total_type\"],\"tab_name\":\"累计值\"},{\"tab_fields\":[\"sort_id\",\"status\"],\"tab_name\":\"其他\"}]',NULL,'60%',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(42,15,'数据列表','index',1,NULL,'20',0,0,NULL,NULL,1,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(41,15,'修改排序开关','updateExt',12,NULL,NULL,0,0,NULL,NULL,2,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(40,15,'添加','add',2,'el-icon-plus',NULL,1,0,'success',NULL,3,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'60%',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(39,15,'修改','update',3,'el-icon-edit',NULL,1,1,'primary',NULL,4,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'60%',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(38,15,'删除','delete',4,'el-icon-delete',NULL,1,1,'danger',NULL,5,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),(28,13,'登录日志','loginLogs',1,'','20',1,0,'success','',134,'','','',1,1,0,0,0,0,'[{\"searchField\":\"type\",\"serachVal\":\"1\"}]','','','','','',140,1,'','','null',''),(27,13,'操作日志','actionLogs',1,'','20',1,0,'primary','',135,'','','',1,1,0,0,0,0,'[{\"searchField\":\"type\",\"serachVal\":\"2\"}]','','','','','',140,1,'','','null',''),(26,13,'异常日志','exceptionLogs',1,'','20',1,0,'danger','',136,'','','',1,1,0,0,0,0,'[{\"searchField\":\"type\",\"serachVal\":\"3\"}]','','','','','',140,1,'','','null',''),(25,13,'简单打印','simplePrint',61,'','20',NULL,NULL,'','',137,'','',NULL,1,1,NULL,NULL,NULL,NULL,'','','','','',NULL,80,1,'','','',NULL);
/*!40000 ALTER TABLE `cd_sys_action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cd_sys_action_type`
--

DROP TABLE IF EXISTS `cd_sys_action_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cd_sys_action_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `dialog` tinyint(4) DEFAULT NULL,
  `button` tinyint(4) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `button_color` varchar(255) DEFAULT NULL,
  `view` tinyint(4) DEFAULT NULL,
  `action_name` varchar(255) DEFAULT NULL,
  `pagesize` int(11) DEFAULT NULL,
  `group_button_status` tinyint(4) DEFAULT NULL,
  `list_button_status` tinyint(4) DEFAULT NULL,
  `sortid` int(11) DEFAULT NULL,
  `is_editable_table` int(11) NOT NULL COMMENT '快捷表格需要的方法',
  `default_create` tinyint(4) DEFAULT NULL COMMENT '默认创建的方法',
  `dialog_size` varchar(255) DEFAULT NULL COMMENT '对话框大小',
  `show_admin` tinyint(4) DEFAULT NULL,
  `show_api` tinyint(4) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`,`is_editable_table`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cd_sys_action_type`
--

LOCK TABLES `cd_sys_action_type` WRITE;
/*!40000 ALTER TABLE `cd_sys_action_type` DISABLE KEYS */;
INSERT INTO `cd_sys_action_type` VALUES (1,'数据列表',1,0,0,NULL,NULL,1,'index',20,0,0,1,1,1,NULL,1,1,1),(2,'修改排序开关',12,0,0,NULL,NULL,0,'updateExt',NULL,0,0,2,0,1,NULL,1,0,1),(3,'添加',2,1,1,'el-icon-plus','success',1,'add',NULL,1,0,3,1,1,'60%',1,1,1),(4,'修改',3,1,1,'el-icon-edit','primary',1,'update',NULL,1,1,4,1,1,'60%',1,1,1),(5,'删除',4,0,1,'el-icon-delete','danger',0,'delete',NULL,1,1,5,1,1,NULL,1,1,1),(6,'查看详情',5,1,1,'el-icon-view','info',1,'detail',NULL,1,0,6,0,1,'60%',1,1,1),(7,'重置密码',6,1,1,'el-icon-lock','primary',1,'resetPwd',NULL,1,0,7,0,0,'500px',1,1,1),(8,'设置指定值',7,1,1,'el-icon-edit-outline','primary',0,NULL,NULL,1,0,8,0,0,NULL,1,1,1),(9,'数值加',8,1,1,'el-icon-plus','primary',1,'jia',NULL,1,0,9,0,0,'500px',1,1,1),(10,'数值减',9,1,1,'el-icon-plus','primary',1,'jian',NULL,1,0,10,0,0,'500px',1,1,1),(11,'导入',10,0,1,'el-icon-upload','warning',1,'importData',NULL,1,0,11,0,1,'500px',1,0,1),(12,'导出',11,0,1,'el-icon-download','warning',0,'exportData',NULL,1,0,12,0,1,NULL,1,0,1),(13,'配置表单',14,0,0,NULL,NULL,0,'index',NULL,0,0,14,0,0,NULL,1,1,1),(14,'跳转链接',15,1,1,'el-icon-plus','warning',0,'jumpUrl',NULL,1,0,15,0,0,NULL,1,0,1),(15,'弹窗连接',16,1,1,'el-icon-plus','warning',0,'dialogUrl',NULL,1,0,16,0,0,NULL,1,0,1),(16,'批量添加',17,0,1,'el-icon-plus','success',0,'batchAdd',NULL,1,0,18,0,0,NULL,1,0,0),(17,'批量修改',18,0,1,'el-icon-edit','primary',0,'batchUpdate',NULL,1,0,17,0,0,NULL,1,0,0),(18,'用户登录',50,1,0,NULL,NULL,0,'login',NULL,0,0,50,0,0,NULL,0,1,1),(19,'发送验证码',51,0,0,NULL,NULL,0,'sendSms',NULL,0,0,51,0,0,NULL,0,1,1),(20,'无关联按钮',60,0,1,NULL,'primary',NULL,'singleButton',NULL,1,0,60,0,0,NULL,1,0,1),(21,'简单打印',61,0,0,NULL,NULL,NULL,'simplePrint',NULL,0,0,61,0,1,NULL,1,0,1);
/*!40000 ALTER TABLE `cd_sys_action_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cd_sys_application`
--

DROP TABLE IF EXISTS `cd_sys_application`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cd_sys_application` (
  `app_id` int(10) NOT NULL AUTO_INCREMENT,
  `application_name` varchar(250) DEFAULT NULL,
  `app_dir` varchar(250) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `app_type` tinyint(4) DEFAULT NULL,
  `login_table` varchar(250) DEFAULT NULL,
  `login_fields` varchar(250) DEFAULT NULL,
  `domain` varchar(250) DEFAULT NULL,
  `pk` varchar(50) DEFAULT NULL COMMENT '登录表主键',
  PRIMARY KEY (`app_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cd_sys_application`
--

LOCK TABLES `cd_sys_application` WRITE;
/*!40000 ALTER TABLE `cd_sys_application` DISABLE KEYS */;
INSERT INTO `cd_sys_application` VALUES (1,'后台管理','admin',1,1,'','','',''),(2,'前端应用','api',1,2,NULL,NULL,'http://127.0.0.1:8000/api',NULL);
/*!40000 ALTER TABLE `cd_sys_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cd_sys_field`
--

DROP TABLE IF EXISTS `cd_sys_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cd_sys_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(9) NOT NULL COMMENT '模块ID',
  `title` varchar(64) NOT NULL COMMENT '字段名称',
  `field` varchar(32) NOT NULL,
  `type` smallint(4) NOT NULL COMMENT '表单类型1输入框 2下拉框 3单选框 4多选框 5上传图片 6编辑器 7时间',
  `list_show` tinyint(4) DEFAULT NULL COMMENT '列表显示',
  `search_type` tinyint(4) DEFAULT NULL COMMENT '1精确匹配 2模糊搜索',
  `post_status` tinyint(4) DEFAULT NULL COMMENT '是否前台录入',
  `create_table_field` tinyint(4) DEFAULT NULL,
  `validate` varchar(32) DEFAULT NULL COMMENT '验证方式',
  `rule` mediumtext COMMENT '验证规则',
  `sortid` mediumint(9) DEFAULT '0' COMMENT '排序号',
  `sql` varchar(255) DEFAULT NULL COMMENT '字段配置数据源sql',
  `default_value` varchar(255) DEFAULT NULL,
  `datatype` varchar(32) DEFAULT NULL COMMENT '字段数据类型',
  `length` varchar(5) DEFAULT NULL COMMENT '字段长度',
  `indexdata` varchar(20) DEFAULT NULL COMMENT '索引',
  `show_condition` varchar(250) DEFAULT NULL,
  `item_config` text,
  `width` varchar(255) DEFAULT NULL COMMENT '单元格宽度',
  `datetime_config` varchar(250) DEFAULT NULL COMMENT '其他配置',
  `other_config` text,
  `belong_table` varchar(255) DEFAULT NULL COMMENT '虚拟字段所属表 用户多表关联',
  `note` text COMMENT '字段说明',
  `cascade` varchar(255) DEFAULT NULL COMMENT '需要级联更新的字段（废弃）',
  `sortable` tinyint(4) DEFAULT NULL COMMENT '是否前端排序',
  `is_partial` tinyint(4) DEFAULT NULL COMMENT '是否脱敏展示（废弃）',
  `is_filter` tinyint(4) DEFAULT NULL COMMENT '是否前端数据过滤',
  `filter_condition` text COMMENT '筛选条件',
  `filter_method` text COMMENT '筛选方法的代码',
  `editable` tinyint(4) DEFAULT NULL COMMENT '可编辑的',
  `desc` varchar(255) DEFAULT NULL COMMENT '表单说明',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `menu_id` (`menu_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=95 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cd_sys_field`
--

LOCK TABLES `cd_sys_field` WRITE;
/*!40000 ALTER TABLE `cd_sys_field` DISABLE KEYS */;
INSERT INTO `cd_sys_field` VALUES (33,11,'站点名称','site_title',1,2,1,1,1,'','',3610,'','','varchar','250','','',NULL,'','','{\"address_type\":\"1\"}','','','',0,0,0,NULL,'',0,'设置本管理系统的名称'),(32,11,'编号','config_id',1,2,NULL,NULL,1,NULL,NULL,1,NULL,NULL,'int','11',NULL,NULL,NULL,'70',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(39,11,'文件类型','filetype',1,2,1,1,1,'','',3615,'','','varchar','250','','',NULL,'','','[]','','','',0,0,0,NULL,'',0,'设置可以上传的文件类型'),(38,11,'上传配置','filesize',1,2,1,1,1,'','',3614,'','0','varchar','250','','',NULL,'','','[]','','','',0,0,0,NULL,'',0,'设置上传文件大小的最大值'),(37,11,'站点版权','copyright',1,2,1,1,1,'','',3613,'','','varchar','250','','',NULL,'','','[]','','','',0,0,0,NULL,'',0,'设置登录页面中的授权声明信息'),(40,11,'水印状态','water_status',4,2,1,1,1,'','',3616,'','','smallint','6','','','[{\"label\":\"正常\",\"value\":\"1\",\"label_color\":\"primary\"},{\"label\":\"禁用\",\"value\":\"0\",\"label_color\":\"danger\"}]','','','[]','','','',0,0,0,NULL,'',0,'是否设置水印（OSS无效）'),(10,9,'角色名称','name',1,3,1,1,0,',notempty',NULL,2,NULL,NULL,'varchar','36',NULL,NULL,'',NULL,NULL,'[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(11,9,'状态','status',6,2,1,1,0,'',NULL,5,NULL,NULL,'tinyint','4',NULL,NULL,'[{\"label\":\"正常\",\"value\":\"1\",\"label_color\":\"primary\"},{\"label\":\"禁用\",\"value\":\"0\",\"label_color\":\"danger\"}]','70',NULL,'[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(12,9,'描述','description',1,3,0,1,0,NULL,NULL,4,NULL,NULL,'text',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(13,13,'编号','id',1,2,0,0,0,NULL,NULL,1,NULL,NULL,'int','11',NULL,NULL,NULL,'70',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(14,13,'应用名','application_name',1,2,0,1,0,NULL,NULL,2,NULL,NULL,'varchar','50',NULL,NULL,NULL,'100',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(15,13,'用户名','username',1,2,1,1,0,NULL,NULL,3,NULL,NULL,'varchar','250',NULL,NULL,NULL,'100',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(16,13,'请求url','url',1,3,0,1,0,NULL,NULL,4,NULL,NULL,'varchar','250',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(17,13,'客户端ip','ip',1,2,0,1,0,NULL,NULL,5,NULL,NULL,'varchar','250',NULL,NULL,NULL,'100',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(18,13,'浏览器信息','useragent',8,0,0,1,0,NULL,NULL,6,NULL,NULL,'varchar','250',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(19,13,'请求内容','content',8,0,0,1,0,NULL,NULL,7,NULL,NULL,'text',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(20,13,'异常信息','errmsg',8,0,0,1,0,NULL,NULL,8,NULL,NULL,'varchar','250',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(21,13,'创建时间','create_time',11,2,0,1,0,'','',9997,'','','int','10','','','','200','datetime','[]','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(22,13,'类型','type',2,2,1,1,0,'',NULL,9,NULL,NULL,'smallint','6',NULL,NULL,'[{\"label\":\"登录日志\",\"value\":\"1\",\"label_color\":\"info\"},{\"label\":\"操作日志\",\"value\":\"2\",\"label_color\":\"warning\"},{\"label\":\"异常日志\",\"value\":\"3\",\"label_color\":\"danger\"}]','100',NULL,'[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(24,12,'编号','id',1,2,0,0,0,NULL,NULL,1,NULL,NULL,'int','11',NULL,NULL,NULL,'70',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(25,12,'配置名称','title',1,2,0,1,0,NULL,NULL,2,NULL,NULL,'varchar','250',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(26,12,'覆盖原图','upload_replace',6,2,0,1,0,'',NULL,3,NULL,NULL,'tinyint','4',NULL,NULL,'[{\"label\":\"开启\",\"value\":\"1\",\"label_color\":\"primary\"},{\"label\":\"关闭\",\"value\":\"0\",\"label_color\":\"danger\"}]',NULL,NULL,'[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(27,12,'生成缩略图','thumb_status',6,2,0,1,0,'',NULL,4,NULL,NULL,'tinyint','4',NULL,NULL,'[{\"label\":\"开启\",\"value\":\"1\",\"label_color\":\"primary\"},{\"label\":\"关闭\",\"value\":\"0\",\"label_color\":\"danger\"}]',NULL,NULL,'[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(28,12,'缩略图宽','thumb_width',1,2,0,1,0,NULL,NULL,5,NULL,NULL,'varchar','250',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(29,12,'缩略图高','thumb_height',1,2,0,1,0,NULL,NULL,6,NULL,NULL,'varchar','250',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(30,12,'缩放类型','thumb_type',2,2,0,1,0,'',NULL,7,NULL,NULL,'smallint','6',NULL,NULL,'[{\"label\":\"等比例缩放\",\"value\":\"1\"},{\"label\":\"缩放后填充\",\"value\":\"2\"},{\"label\":\"居中裁剪\",\"value\":\"3\"},{\"label\":\"左上角裁剪\",\"value\":\"4\"},{\"label\":\"右下角裁剪\",\"value\":\"5\"},{\"label\":\"固定尺寸缩放\",\"value\":\"6\"}]',NULL,NULL,'[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(41,11,'水印位置','water_position',2,2,1,1,1,'','',3617,'','','smallint','6','','','[{\"label\":\"左上角水印\",\"value\":\"1\"},{\"label\":\"上居中水印\",\"value\":\"2\"},{\"label\":\"右上角水印\",\"value\":\"3\"},{\"label\":\"左居中水印\",\"value\":\"4\"},{\"label\":\"居中水印\",\"value\":\"5\"},{\"label\":\"右居中水印\",\"value\":\"6\"},{\"label\":\"左下角水印\",\"value\":\"7\"},{\"label\":\"下居中水印\",\"value\":\"8\"},{\"label\":\"右下角水印\",\"value\":\"9\"}]','','','[]','','','',0,0,0,NULL,'',0,'设置水印位置（OSS无效）'),(9,9,'编号','role_id',1,2,0,0,0,NULL,NULL,1,NULL,NULL,'int','11',NULL,NULL,NULL,'70',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(6,8,'备注','note',1,3,0,1,1,'','',13,'','','varchar','250','','','','','','[]','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(7,8,'状态','status',6,2,1,1,1,'','',14,'','','tinyint','4','','','[{\"label\":\"正常\",\"value\":\"1\",\"label_color\":\"primary\"},{\"label\":\"禁用\",\"value\":\"0\",\"label_color\":\"danger\"}]','70','','[]','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(8,8,'创建时间','create_time',11,1,0,1,1,'','',16,'','','int','10','','','','200','datetime','[]','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(5,8,'所属角色','role_id',2,0,1,1,1,'','',9,'select role_id,name from pre_admin_role where status = 1','1','smallint','6','','','','','','[]','','','',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,8,'密码','pwd',7,0,0,1,0,NULL,NULL,7,NULL,NULL,'varchar','250',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,8,'用户名','user',1,3,2,1,0,NULL,NULL,3,NULL,NULL,'varchar','250',NULL,NULL,NULL,'100',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(1,8,'编号','user_id',1,2,0,0,0,NULL,NULL,1,NULL,NULL,'int','11',NULL,NULL,NULL,'70',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,8,'用户姓名','name',1,3,2,1,0,NULL,NULL,2,NULL,NULL,'varchar','250',NULL,NULL,NULL,'100',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(69,8,'所属角色','name',1,3,NULL,0,0,'','',8,'','','varchar','250','','','','','','{\"address_type\":\"1\"}','admin_role',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(75,10,'编号','dept_id',1,2,NULL,NULL,1,NULL,NULL,1,NULL,NULL,'int','11',NULL,NULL,NULL,'70',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(76,10,'创建时间','create_time',11,1,0,1,1,NULL,NULL,9998,NULL,NULL,'int','11','index',NULL,NULL,'200',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(77,10,'更新时间','update_time',11,1,0,1,1,NULL,NULL,9999,NULL,NULL,'int','11','index',NULL,NULL,'200',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(78,10,'名称','name',1,3,1,1,1,'',NULL,4558,NULL,'','varchar','250',NULL,NULL,'',NULL,NULL,'{\"address_type\":\"1\"}','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(79,10,'所属部门','pid',2,0,1,1,1,'',NULL,4559,'select dept_id,name,pid from cd_admin_dept where status = 1','','smallint','6',NULL,NULL,'',NULL,NULL,'[]','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(80,10,'状态','status',6,2,1,1,1,'',NULL,4561,NULL,'','tinyint','4',NULL,NULL,'[{\"label\":\"开启\",\"value\":\"1\",\"label_color\":\"primary\"},{\"label\":\"关闭\",\"value\":\"0\",\"label_color\":\"danger\"}]','70',NULL,'[]','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(81,10,'排序','sort_id',29,2,0,1,1,'',NULL,4562,NULL,'','int','11',NULL,NULL,'','70',NULL,'[]','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(82,8,'所属部门','dept_id',2,0,1,1,1,'','',11,'select dept_id,name,pid from cd_admin_dept where status = 1','1','varchar','250','','','','','','{\"address_type\":\"1\"}','','','',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(83,8,'所属部门','name',1,3,0,0,0,'','',10,'','','varchar','250','','','','','','{\"address_type\":\"1\"}','admin_dept',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(87,8,'排序','sort_id',29,2,0,1,1,NULL,NULL,15,NULL,NULL,'int','11',NULL,NULL,NULL,'70',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(88,8,'更新时间','update_time',11,1,0,1,1,NULL,NULL,9999,NULL,NULL,'int','11','index',NULL,NULL,'200',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(89,8,'邮箱','email',1,3,2,1,1,'','/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/',5,NULL,'','varchar','250',NULL,NULL,'','',NULL,'{\"address_type\":\"1\"}','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(90,8,'手机号','mobile',1,3,2,1,1,'','/^1[3456789]\\d{9}$/',6,NULL,'','varchar','250',NULL,NULL,'','90',NULL,'[]','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(91,8,'数据权限','permission',2,3,1,1,1,'','',12,'','1','smallint','6','','','[{\"label\":\"全部数据权限\",\"value\":\"1\",\"label_color\":\"success\"},{\"label\":\"本部门及以下数据权限\",\"value\":\"2\",\"label_color\":\"primary\"},{\"label\":\"本部门数据权限\",\"value\":\"3\",\"label_color\":\"warning\"},{\"label\":\"本人数据权限\",\"value\":\"4\",\"label_color\":\"info\"},{\"label\":\"无数据权限\",\"value\":\"5\",\"label_color\":\"danger\"}]','','','[]','','','',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(92,10,'说明','note',8,2,0,1,1,'',NULL,4560,NULL,'','text','0',NULL,NULL,'',NULL,NULL,'{\"address_type\":\"1\"}','',NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL),(93,13,'排序','sort_id',29,0,0,1,1,NULL,NULL,9995,NULL,NULL,'int','11',NULL,NULL,NULL,'70',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(94,13,'状态','status',6,0,1,1,1,'','',9996,'','','tinyint','4','','','[{\"label\":\"开启\",\"value\":\"1\",\"label_color\":\"primary\"},{\"label\":\"关闭\",\"value\":\"0\",\"label_color\":\"danger\"}]','70','','[]','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(43,13,'更新时间','update_time',11,1,0,1,1,NULL,NULL,9999,NULL,NULL,'int','11','index',NULL,NULL,'200',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(70,8,'头像','headimg',13,2,0,1,1,'',NULL,4,NULL,'','varchar','250','','','',NULL,'','{\"pre_icon\":\"\",\"afterfix\":\"\",\"prefix\":\"\",\"label_color\":\"\",\"input_length\":\"\",\"rand_length\":\"\",\"upload_type\":\"2\",\"address_type\":\"1\"}','',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL),(23,11,'水印透明度','water_alpha',19,2,1,1,1,'','',3619,'','','smallint','6','','',NULL,'','','{\"address_type\":\"1\"}','','','',0,0,0,NULL,'',0,'设置水印图标的透明度'),(42,11,'绑定域名','domain',1,2,1,1,1,'','',3620,'','','varchar','250','','',NULL,'','','[]','','','',0,0,0,NULL,'',0,'绑定域名是指通过此域名可以访问到Thinkphp项目的地址'),(47,14,'编号','statisic_id',1,1,NULL,NULL,1,NULL,NULL,1,NULL,NULL,'int','11',NULL,NULL,NULL,'70',NULL,NULL,NULL,'不建议修改其属性',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(48,14,'排序','sort_id',29,1,0,1,1,NULL,NULL,99995,NULL,NULL,'int','11','index',NULL,NULL,'70',NULL,NULL,NULL,'不建议修改其属性',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(49,14,'状态','status',6,1,0,1,1,NULL,NULL,99996,NULL,NULL,'tinyint','4','index',NULL,'[{\"label\":\"开启\",\"value\":\"1\",\"label_color\":\"primary\"},{\"label\":\"关闭\",\"value\":\"0\",\"label_color\":\"danger\"}]','70',NULL,NULL,NULL,'不建议修改其属性',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(50,14,'所有者','creater_id',1,0,0,1,1,NULL,NULL,99997,NULL,NULL,'int','11','index',NULL,NULL,'70',NULL,NULL,NULL,'自动生成无录入表单',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(51,14,'创建时间','create_time',11,1,0,1,1,NULL,NULL,99998,NULL,NULL,'int','11','index',NULL,NULL,'200','datetime',NULL,NULL,'不建议修改其属性',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(52,14,'更新时间','update_time',12,1,0,1,1,NULL,NULL,99999,NULL,NULL,'int','11','index',NULL,NULL,'200','datetime',NULL,NULL,'不建议修改其属性',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(53,14,'当前标题','current_name',1,3,0,1,1,'','',208,'','','varchar','250','','',NULL,'','','{\"pre_icon\":\"\",\"afterfix\":\"\",\"prefix\":\"\",\"label_color\":\"\",\"input_length\":\"\",\"rand_length\":\"\",\"upload_type\":\"\",\"address_type\":\"1\"}','','','',0,0,0,NULL,'',0,'单位统计的名称，比如：当日订单数，当月销售额'),(54,14,'单位名称','unit',1,3,0,1,1,'','',206,'','','varchar','250','','','',NULL,'','[]','','',NULL,0,NULL,0,'','',0,'当前统计单位，比如：日，月，周'),(55,14,'名称背景色','unit_color',20,3,0,1,1,'','',207,'','#4AB7BD','varchar','250','','','',NULL,'','[]','','',NULL,0,NULL,0,'','',0,'统计单位名称的背景色'),(56,14,'当前值','current_value',1,3,0,1,1,'','',210,'','','varchar','250','','form.current_type == 1',NULL,'','','[]','','','',0,0,0,NULL,'',0,'输入需要显示的当前值的数值，如果设置SQL语句，此值无效；'),(57,14,'累计标题','total_name',1,3,0,1,1,'','',212,'','','varchar','250','','','',NULL,'','[]','','',NULL,0,NULL,0,'','',0,''),(58,14,'累计值','total_value',1,3,0,1,1,'','',214,'','','varchar','250','','form.total_type == 1',NULL,'','','[]','','','',0,0,0,NULL,'',0,'输入需要展示的累计值的数值，如果设置SQL语句，此值无效；'),(59,14,'当前SQL','current_sql',8,3,0,1,1,'','',211,'','','text','0','','form.current_type == 2',NULL,'','','[]','','','',0,0,0,NULL,'',0,'输入查询累计值的SQL语句，SQL语句优先；'),(60,14,'累计SQL','total_sql',8,3,0,1,1,'','',215,'','','text','0','','form.total_type == 2',NULL,'','','[]','','','',0,0,0,NULL,'',0,'输入查询累计值的SQL语句，SQL语句优先；'),(61,14,'当前值类型','current_type',4,3,0,1,1,'','',209,'','','smallint','6','','','[{\"label\":\"固定值\",\"value\":\"1\",\"label_color\":\"primary\"},{\"label\":\"SQL语句\",\"value\":\"2\",\"label_color\":\"warning\"}]',NULL,'','[]','','',NULL,0,NULL,0,'','',0,''),(62,14,'累计值类型','total_type',4,3,0,1,1,'','',213,'','','smallint','6','','','[{\"label\":\"固定值\",\"value\":\"1\",\"label_color\":\"primary\"},{\"label\":\"SQL语句\",\"value\":\"2\",\"label_color\":\"warning\"}]',NULL,'','[]','','',NULL,0,NULL,0,'','',0,''),(63,15,'编号','chart_id',1,1,NULL,NULL,1,NULL,NULL,1,NULL,NULL,'int','11',NULL,NULL,NULL,'70',NULL,NULL,NULL,'不建议修改其属性',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(64,15,'排序','sort_id',29,1,0,1,1,NULL,NULL,99995,NULL,NULL,'int','11','index',NULL,NULL,'70',NULL,NULL,NULL,'不建议修改其属性',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(65,15,'状态','status',6,1,1,1,1,NULL,NULL,99996,NULL,NULL,'tinyint','4','index',NULL,'[{\"label\":\"开启\",\"value\":\"1\",\"label_color\":\"primary\"},{\"label\":\"关闭\",\"value\":\"0\",\"label_color\":\"danger\"}]','70',NULL,NULL,NULL,'不建议修改其属性',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(66,15,'所有者','creater_id',1,0,0,1,1,NULL,NULL,99997,NULL,NULL,'int','11','index',NULL,NULL,'70',NULL,NULL,NULL,'自动生成无录入表单',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(67,15,'创建时间','create_time',11,1,0,1,1,NULL,NULL,99998,NULL,NULL,'int','11','index',NULL,NULL,'200','datetime',NULL,NULL,'不建议修改其属性',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(68,15,'更新时间','update_time',12,1,0,1,1,NULL,NULL,99999,NULL,NULL,'int','11','index',NULL,NULL,'200','datetime',NULL,NULL,'不建议修改其属性',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(71,15,'名称','name',1,3,1,1,1,'','',222,'','','varchar','250','','','',NULL,'','{\"pre_icon\":\"\",\"afterfix\":\"\",\"prefix\":\"\",\"label_color\":\"\",\"input_length\":\"\",\"rand_length\":\"\",\"upload_type\":\"\",\"address_type\":\"1\"}','','',NULL,0,NULL,0,'','',0,'显示在图表顶部的标题'),(72,15,'类型','type',4,3,1,1,1,'','',223,'','','smallint','6','','','[{\"label\":\"柱状图\",\"value\":\"1\",\"label_color\":\"primary\"},{\"label\":\"折线图\",\"value\":\"2\",\"label_color\":\"warning\"}]',NULL,'','[]','','',NULL,0,NULL,0,'','',0,'系统支持柱状图，折线图'),(73,15,'维度顺序','dimensions',1,3,1,1,1,'','',224,'','','varchar','250','','','',NULL,'','[]','','',NULL,0,NULL,0,'','',0,'维度的顺序；默认把第一个维度映射到 X 轴上，后面维度映射到 Y 轴上。格式为逗号隔开的字符串；'),(74,15,'基础数据源','source_data',8,3,1,1,1,'','',226,'','','text','0','','form.source == 1',NULL,'','','[]','','','',0,0,0,NULL,'',0,'cvs格式数据,字段值包含空格用双引号包括，逗号分割字段，每行结尾无逗号'),(84,15,'SQL数据源','source_sql',8,3,1,1,1,'','',227,'','','text','0','','form.source == 2',NULL,'','','[]','','','',0,0,0,NULL,'',0,'查询产生数据的SQL语句，注意查询列别名必须与维度顺序一致；'),(85,15,'数据源类型','source',4,3,1,1,1,'','',225,'','','smallint','6','','','[{\"label\":\"设定的数据\",\"value\":\"1\",\"label_color\":\"primary\"},{\"label\":\"SQL查询\",\"value\":\"2\",\"label_color\":\"danger\"}]',NULL,'','[]','','',NULL,0,NULL,0,'','',0,'选择采用什么方式设置图表数据'),(34,11,'站点logo','site_logo',13,2,1,1,1,'','',3609,'','','varchar','250','','',NULL,'','','{\"upload_type\":\"2\"}','','','',0,0,0,NULL,'',0,'设置系统侧栏顶部显示的图片'),(35,11,'站点关键词','keyword',18,2,1,1,1,'','',3611,'','','varchar','250','','',NULL,'','','[]','','','',0,0,0,NULL,'',0,'设置本系统的站点的关键词'),(36,11,'站点描述','site_desc',8,2,1,1,1,'','',3612,'','','text','0','','',NULL,'','','[]','','','',0,0,0,NULL,'',0,'设置本系统的站点的描述信息'),(31,11,'内置菜单','show_default_menu',4,3,1,1,1,'','',5504,'','1','smallint','6','','','[{\"label\":\"显示\",\"value\":\"1\"},{\"label\":\"隐藏\",\"value\":\"0\"}]','','','{\"pre_icon\":\"\",\"afterfix\":\"\",\"prefix\":\"\",\"label_color\":\"\",\"input_length\":\"\",\"rand_length\":\"\",\"upload_type\":\"\",\"address_type\":\"1\"}','','','',0,0,0,NULL,'',0,'是否在菜单管理表格中显示内置菜单项'),(44,11,'显示图表','show_chart',4,3,1,1,1,'','',5507,'','','smallint','6','','','[{\"label\":\"显示\",\"value\":\"1\",\"label_color\":\"primary\"},{\"label\":\"隐藏\",\"value\":\"2\",\"label_color\":\"danger\"}]','','','{\"pre_icon\":\"\",\"afterfix\":\"\",\"prefix\":\"\",\"label_color\":\"\",\"input_length\":\"\",\"rand_length\":\"\",\"upload_type\":\"\",\"address_type\":\"1\"}','','','',0,0,0,NULL,'',0,'是否在首页显示统计图表'),(46,11,'首页启动','show_menu',4,3,1,1,1,'','',5506,'','','smallint','6','','','[{\"label\":\"显示\",\"value\":\"1\",\"label_color\":\"primary\"},{\"label\":\"隐藏\",\"value\":\"2\",\"label_color\":\"danger\"}]',NULL,'','[]','','',NULL,0,NULL,0,'','',0,'设置首页是否显示启动图标'),(45,11,'显示统计','show_statisic',4,3,1,1,1,'','',5505,'','','smallint','6','','','[{\"label\":\"显示\",\"value\":\"1\",\"label_color\":\"primary\"},{\"label\":\"隐藏\",\"value\":\"2\",\"label_color\":\"warning\"}]','','','[]','','','',0,0,0,NULL,'',0,'首页是否显示统计数据'),(86,11,'爱好','hobby',21,3,0,1,1,'','',100,'','','text','0','','','',NULL,'','{\"pre_icon\":\"\",\"afterfix\":\"\",\"prefix\":\"\",\"label_color\":\"\",\"input_length\":\"\",\"rand_length\":\"\",\"upload_type\":\"\",\"address_type\":\"1\"}','','',NULL,0,NULL,0,'','',0,'');
/*!40000 ALTER TABLE `cd_sys_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cd_sys_field_property`
--

DROP TABLE IF EXISTS `cd_sys_field_property`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cd_sys_field_property` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `maxlen` int(11) DEFAULT NULL,
  `decimal` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cd_sys_field_property`
--

LOCK TABLES `cd_sys_field_property` WRITE;
/*!40000 ALTER TABLE `cd_sys_field_property` DISABLE KEYS */;
INSERT INTO `cd_sys_field_property` VALUES (1,1,'varchar',250,0),(2,2,'int',11,0),(3,3,'smallint',6,0),(4,4,'text',0,0),(5,8,'longtext',0,0),(6,5,'decimal',10,2),(7,6,'tinyint',4,0),(8,7,'datetime',0,0);
/*!40000 ALTER TABLE `cd_sys_field_property` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cd_sys_field_type`
--

DROP TABLE IF EXISTS `cd_sys_field_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cd_sys_field_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `property` tinyint(4) DEFAULT NULL,
  `item` tinyint(4) DEFAULT NULL,
  `search` tinyint(4) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cd_sys_field_type`
--

LOCK TABLES `cd_sys_field_type` WRITE;
/*!40000 ALTER TABLE `cd_sys_field_type` DISABLE KEYS */;
INSERT INTO `cd_sys_field_type` VALUES (1,'文本框',1,1,0,1,1),(2,'下拉框',2,3,1,1,1),(3,'下拉框(多选)',3,1,1,1,1),(4,'单选框',4,3,1,1,1),(5,'多选框',5,1,1,1,1),(6,'开关按钮',6,6,1,1,1),(7,'密码框',7,1,0,0,1),(8,'文本域',8,4,0,1,1),(9,'日期框',9,2,0,0,1),(10,'日期范围',10,1,0,0,1),(11,'创建时间(后端自动)',11,2,0,0,1),(12,'修改时间(后端自动)',12,2,0,0,1),(13,'单图上传',13,1,0,0,1),(14,'多图上传',14,4,0,0,1),(15,'单文件上传',15,1,0,0,1),(16,'多文件上传',16,4,0,0,1),(17,'计数器',17,5,0,0,1),(18,'标签',18,1,0,1,1),(19,'滑块',19,3,0,0,1),(20,'颜色选择器',20,1,0,0,1),(21,'键值对',21,4,0,0,1),(22,'省市区联动',22,1,0,0,1),(25,'坐标选择器',28,4,0,0,1),(26,'编辑器(Wangeditor)',25,4,0,0,1),(27,'编辑器(Tinymce)',26,8,0,0,1),(28,'markdown编辑器',27,4,0,0,1),(29,'排序号',29,2,0,0,1),(30,'token解码值',30,2,0,0,1),(31,'随机数',31,1,0,0,1),(32,'订单号',32,1,0,0,1),(33,'隐藏域',33,1,0,0,1),(34,'请求ip',34,1,0,0,1);
/*!40000 ALTER TABLE `cd_sys_field_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cd_sys_menu`
--

DROP TABLE IF EXISTS `cd_sys_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cd_sys_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` mediumint(9) DEFAULT '0' COMMENT '父级id',
  `controller_name` varchar(32) DEFAULT NULL COMMENT '模块名称',
  `title` varchar(64) DEFAULT NULL COMMENT '模块标题',
  `pk` varchar(36) DEFAULT NULL COMMENT '主键名',
  `table_name` varchar(32) DEFAULT NULL COMMENT '模块数据库表',
  `create_code` tinyint(4) DEFAULT NULL COMMENT '是否允许生成模块',
  `status` tinyint(4) DEFAULT '1' COMMENT '0隐藏 1显示',
  `sortid` mediumint(9) DEFAULT '0' COMMENT '排序号',
  `create_table` tinyint(4) DEFAULT NULL COMMENT '是否生成数据库表',
  `component_path` varchar(64) DEFAULT NULL COMMENT '组件路径',
  `icon` varchar(32) DEFAULT NULL COMMENT 'icon字体图标',
  `tab_config` varchar(250) DEFAULT NULL COMMENT 'tab选项卡菜单配置',
  `app_id` int(11) DEFAULT NULL COMMENT '所属模块',
  `is_post` tinyint(4) DEFAULT NULL COMMENT '是否允许投稿',
  `upload_config_id` smallint(5) DEFAULT NULL COMMENT '上传配置id',
  `connect` varchar(20) DEFAULT NULL COMMENT '数据库连接',
  `page_type` tinyint(4) DEFAULT NULL COMMENT '页面类型',
  `home_show` tinyint(4) DEFAULT '0' COMMENT '首页快捷导航显示状态',
  `menu_pic` varchar(250) DEFAULT NULL COMMENT '快捷导航的图片',
  `home_sort` int(11) DEFAULT NULL COMMENT '快捷导航排序',
  `url` text COMMENT '外链网址',
  `copy_from` int(11) DEFAULT NULL COMMENT '从何表拷贝来',
  PRIMARY KEY (`menu_id`) USING BTREE,
  KEY `controller_name` (`controller_name`) USING BTREE,
  KEY `module_id` (`app_id`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cd_sys_menu`
--

LOCK TABLES `cd_sys_menu` WRITE;
/*!40000 ALTER TABLE `cd_sys_menu` DISABLE KEYS */;
INSERT INTO `cd_sys_menu` VALUES (1,0,'System','系统管理','','',0,1,99998,0,'','el-icon-setting','',1,0,0,'',NULL,NULL,NULL,NULL,NULL,NULL),(3,0,'Home','首页','','',0,1,1,0,'admin/base/home.vue','el-icon-s-home','',1,0,0,'',NULL,NULL,NULL,NULL,NULL,NULL),(2,4,'Menu','菜单管理','','',0,1,9,0,'admin/sys/menu/Index.vue','','',1,0,0,'',NULL,NULL,NULL,NULL,NULL,NULL),(5,4,'Application','应用管理','app_id','sys_application',0,1,17,0,'admin/sys/application/Index.vue','el-icon-s-tools','',1,0,0,'mysql',0,0,NULL,NULL,NULL,NULL),(6,2,'Field','字段管理','','',0,0,0,0,'','el-icon-menu','',1,0,0,'mysql',NULL,NULL,NULL,NULL,NULL,NULL),(7,2,'Action','方法管理','','',0,0,0,0,'','el-icon-menu','',1,1,0,'mysql',NULL,NULL,NULL,NULL,NULL,NULL),(8,1,'Admin/User','用户管理','user_id','admin_user',1,1,29,1,'admin/admin/user/index.vue','el-icon-user','',1,0,0,'mysql',1,1,'https://ks-oss.raiseinfo.cn/admin/202203/202203021723210227814.png',1,'',0),(9,1,'Admin/Role','角色管理','role_id','admin_role',1,1,33,1,'admin/admin/role/index.vue','el-icon-s-check','',1,0,0,'mysql',1,0,'',0,NULL,NULL),(4,0,'Tool','工具管理','','',0,1,99999,0,'','el-icon-help',NULL,1,1,NULL,'',1,NULL,NULL,NULL,NULL,NULL),(13,1,'Admin/Log','日志管理','log_id','admin_log',1,1,45,0,'admin/admin/log/index.vue','el-icon-s-promotion','',1,0,0,'mysql',1,1,'https://ks-oss.raiseinfo.cn/admin/202203/202203021723210227814.png',7,'',0),(12,1,'Uploadconfig','缩略图配置','id','admin_upload_config',1,0,43,0,'admin/uploadconfig/index.vue','el-icon-upload2','',1,0,0,'mysql',1,0,'',NULL,NULL,NULL),(11,1,'Admin/Config','基本配置','config_id','admin_config',1,1,41,1,'admin/admin/config/index.vue','','',1,1,0,'mysql',2,1,'https://ks-oss.raiseinfo.cn/admin/202203/202203021723210227814.png',5,'',NULL),(10,1,'Admin/Dept','部门管理','dept_id','admin_dept',1,1,35,1,'admin/admin/dept/index.vue','','',1,0,0,'mysql',1,0,'',NULL,NULL,NULL),(16,4,'Sys/Doc','API文档','','',0,1,21,0,'admin/sys/doc/Index.vue','','',1,0,0,'',0,0,'https://ks-oss.raiseinfo.cn/admin/202112/202112282358030277507.png',11,'',NULL),(14,1,'Admin/Statisic','统计设置','statisic_id','admin_statisic',1,0,23,1,'admin/admin/statisic/index.vue','',NULL,1,0,0,'mysql',1,0,'',0,NULL,NULL),(15,1,'Admin/Chart','图表设置','chart_id','admin_chart',1,0,25,1,'admin/admin/chart/index.vue','',NULL,1,0,0,'mysql',1,0,'',0,NULL,NULL);
/*!40000 ALTER TABLE `cd_sys_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cd_sys_setting`
--

DROP TABLE IF EXISTS `cd_sys_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cd_sys_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `data` text COMMENT '设置JSON文本',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cd_sys_setting`
--

LOCK TABLES `cd_sys_setting` WRITE;
/*!40000 ALTER TABLE `cd_sys_setting` DISABLE KEYS */;
/*!40000 ALTER TABLE `cd_sys_setting` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-08-20 18:22:33
