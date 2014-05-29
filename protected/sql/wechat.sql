/*
Navicat MySQL Data Transfer

Source Server         : 腾讯云
Source Server Version : 50171
Source Host           : 203.195.189.190:3306
Source Database       : wechat_platform

Target Server Type    : MYSQL
Target Server Version : 50171
File Encoding         : 65001

Date: 2014-05-29 19:10:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for wp_account
-- ----------------------------
DROP TABLE IF EXISTS `wp_account`;
CREATE TABLE `wp_account` (
  `id` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(20) NOT NULL,
  `type` int(1) unsigned NOT NULL,
  `appid` varchar(18) NOT NULL,
  `appsecret` varchar(32) NOT NULL,
  `root_manager_id` int(10) unsigned NOT NULL,
  `token` varchar(40) NOT NULL,
  `suffix` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for wp_manager_account
-- ----------------------------
DROP TABLE IF EXISTS `wp_manager_account`;
CREATE TABLE `wp_manager_account` (
  `manager_id` int(11) NOT NULL,
  `account_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for wp_response_text
-- ----------------------------
DROP TABLE IF EXISTS `wp_response_text`;
CREATE TABLE `wp_response_text` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` varchar(20) NOT NULL,
  `content` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
