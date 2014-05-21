/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50524
Source Host           : localhost:3306
Source Database       : wechat

Target Server Type    : MYSQL
Target Server Version : 50524
File Encoding         : 65001

Date: 2014-05-21 12:04:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for wp_auth_qq
-- ----------------------------
DROP TABLE IF EXISTS `wp_auth_qq`;
CREATE TABLE `wp_auth_qq` (
  `uin` bigint(20) unsigned NOT NULL,
  `manager_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for wp_manager
-- ----------------------------
DROP TABLE IF EXISTS `wp_manager`;
CREATE TABLE `wp_manager` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `is_banded` int(1) unsigned NOT NULL,
  `create_by` int(10) unsigned NOT NULL,
  `create_at` int(10) unsigned NOT NULL,
  `update_at` int(10) unsigned NOT NULL,
  `last_login` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
