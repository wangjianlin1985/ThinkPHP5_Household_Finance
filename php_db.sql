/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50051
Source Host           : localhost:3306
Source Database       : php_db

Target Server Type    : MYSQL
Target Server Version : 50051
File Encoding         : 65001

Date: 2018-10-12 18:13:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `t_admin`
-- ----------------------------
DROP TABLE IF EXISTS `t_admin`;
CREATE TABLE `t_admin` (
  `username` varchar(20) NOT NULL default '',
  `password` varchar(32) default NULL,
  PRIMARY KEY  (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_admin
-- ----------------------------
INSERT INTO `t_admin` VALUES ('a', 'a');

-- ----------------------------
-- Table structure for `t_expend`
-- ----------------------------
DROP TABLE IF EXISTS `t_expend`;
CREATE TABLE `t_expend` (
  `expendId` int(11) NOT NULL auto_increment COMMENT '支出id',
  `exprendTypeObj` int(11) NOT NULL COMMENT '支出类型',
  `expendPurpose` varchar(60) NOT NULL COMMENT '支出用途',
  `payWayObj` int(11) NOT NULL COMMENT '支付方式',
  `payAccount` varchar(20) NOT NULL COMMENT '支付账号',
  `expendMoney` float NOT NULL COMMENT '支付金额',
  `expendDate` varchar(20) default NULL COMMENT '支付日期',
  `userObj` varchar(30) NOT NULL COMMENT '支出用户',
  `expendMemo` varchar(20) default NULL COMMENT '支出备注',
  PRIMARY KEY  (`expendId`),
  KEY `exprendTypeObj` (`exprendTypeObj`),
  KEY `payWayObj` (`payWayObj`),
  KEY `userObj` (`userObj`),
  CONSTRAINT `t_expend_ibfk_3` FOREIGN KEY (`userObj`) REFERENCES `t_userinfo` (`user_name`),
  CONSTRAINT `t_expend_ibfk_1` FOREIGN KEY (`exprendTypeObj`) REFERENCES `t_expendtype` (`expendTypeId`),
  CONSTRAINT `t_expend_ibfk_2` FOREIGN KEY (`payWayObj`) REFERENCES `t_payway` (`payWayId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_expend
-- ----------------------------
INSERT INTO `t_expend` VALUES ('1', '1', '9月逛街买衣服', '3', 'dashen@163.com', '820', '2018-09-19', 'user1', '测试支出');
INSERT INTO `t_expend` VALUES ('2', '3', '移动手机话费', '2', 'xiaowei1998', '88', '2018-09-28', 'user2', 'test');

-- ----------------------------
-- Table structure for `t_expendtype`
-- ----------------------------
DROP TABLE IF EXISTS `t_expendtype`;
CREATE TABLE `t_expendtype` (
  `expendTypeId` int(11) NOT NULL auto_increment COMMENT '支出类型id',
  `expendTypeName` varchar(20) NOT NULL COMMENT '支出类型名称',
  PRIMARY KEY  (`expendTypeId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_expendtype
-- ----------------------------
INSERT INTO `t_expendtype` VALUES ('1', '衣服');
INSERT INTO `t_expendtype` VALUES ('2', '餐饮');
INSERT INTO `t_expendtype` VALUES ('3', '电话费');

-- ----------------------------
-- Table structure for `t_income`
-- ----------------------------
DROP TABLE IF EXISTS `t_income`;
CREATE TABLE `t_income` (
  `incomeId` int(11) NOT NULL auto_increment COMMENT '收入id',
  `incomeTypeObj` int(11) NOT NULL COMMENT '收入类型',
  `incomeFrom` varchar(50) NOT NULL COMMENT '收入来源',
  `payWayObj` int(11) NOT NULL COMMENT '支付方式',
  `payAccount` varchar(20) NOT NULL COMMENT '支付账号',
  `incomeMoney` float NOT NULL COMMENT '收入金额',
  `incomeDate` varchar(20) default NULL COMMENT '收入日期',
  `userObj` varchar(30) NOT NULL COMMENT '收入用户',
  `incomeMemo` varchar(800) default NULL COMMENT '收入备注',
  PRIMARY KEY  (`incomeId`),
  KEY `incomeTypeObj` (`incomeTypeObj`),
  KEY `payWayObj` (`payWayObj`),
  KEY `userObj` (`userObj`),
  CONSTRAINT `t_income_ibfk_3` FOREIGN KEY (`userObj`) REFERENCES `t_userinfo` (`user_name`),
  CONSTRAINT `t_income_ibfk_1` FOREIGN KEY (`incomeTypeObj`) REFERENCES `t_incometype` (`typeId`),
  CONSTRAINT `t_income_ibfk_2` FOREIGN KEY (`payWayObj`) REFERENCES `t_payway` (`payWayId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_income
-- ----------------------------
INSERT INTO `t_income` VALUES ('1', '1', '2018年9月工资', '3', 'dashen@163.com', '6200', '2018-09-30', 'user1', '测试');
INSERT INTO `t_income` VALUES ('2', '2', '9月全勤奖', '2', 'xiaowei1998', '500', '2018-10-03', 'user2', '测试收入');

-- ----------------------------
-- Table structure for `t_incometype`
-- ----------------------------
DROP TABLE IF EXISTS `t_incometype`;
CREATE TABLE `t_incometype` (
  `typeId` int(11) NOT NULL auto_increment COMMENT '分类id',
  `typeName` varchar(20) NOT NULL COMMENT '分类名称',
  PRIMARY KEY  (`typeId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_incometype
-- ----------------------------
INSERT INTO `t_incometype` VALUES ('1', '工资');
INSERT INTO `t_incometype` VALUES ('2', '奖金');

-- ----------------------------
-- Table structure for `t_notice`
-- ----------------------------
DROP TABLE IF EXISTS `t_notice`;
CREATE TABLE `t_notice` (
  `noticeId` int(11) NOT NULL auto_increment COMMENT '公告id',
  `title` varchar(80) NOT NULL COMMENT '标题',
  `content` varchar(5000) NOT NULL COMMENT '公告内容',
  `publishDate` varchar(20) default NULL COMMENT '发布时间',
  PRIMARY KEY  (`noticeId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_notice
-- ----------------------------
INSERT INTO `t_notice` VALUES ('1', '家庭财务理财网站成立了！', '<p>你的家庭多了一个管家婆，每个月收支清清楚楚，再也不用担心不知道钱去哪里了！<br/><img src=\"/phpsystem/public/upload/1539328936941578.jpg\" title=\"1539328936941578.jpg\" alt=\"07091702006242.jpg\" width=\"361\" height=\"477\" style=\"width: 361px; height: 477px;\"/></p>', '2018-10-12 15:22:24');
INSERT INTO `t_notice` VALUES ('2', '一款好用的财务软件', '<p>谁用谁知道，好用不告诉你！</p>', '2018-10-12 15:23:00');

-- ----------------------------
-- Table structure for `t_payway`
-- ----------------------------
DROP TABLE IF EXISTS `t_payway`;
CREATE TABLE `t_payway` (
  `payWayId` int(11) NOT NULL auto_increment COMMENT '支付方式id',
  `payWayName` varchar(20) NOT NULL COMMENT '支付方式名称',
  PRIMARY KEY  (`payWayId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_payway
-- ----------------------------
INSERT INTO `t_payway` VALUES ('1', '银行卡');
INSERT INTO `t_payway` VALUES ('2', '微信');
INSERT INTO `t_payway` VALUES ('3', '支付宝');

-- ----------------------------
-- Table structure for `t_userinfo`
-- ----------------------------
DROP TABLE IF EXISTS `t_userinfo`;
CREATE TABLE `t_userinfo` (
  `user_name` varchar(30) NOT NULL COMMENT 'user_name',
  `password` varchar(30) NOT NULL COMMENT '登录密码',
  `name` varchar(20) NOT NULL COMMENT '姓名',
  `gender` varchar(4) NOT NULL COMMENT '性别',
  `birthDate` varchar(20) default NULL COMMENT '出生日期',
  `userPhoto` varchar(60) NOT NULL COMMENT '用户照片',
  `telephone` varchar(20) NOT NULL COMMENT '联系电话',
  `email` varchar(50) NOT NULL COMMENT '邮箱',
  `address` varchar(80) default NULL COMMENT '家庭地址',
  `regTime` varchar(20) default NULL COMMENT '注册时间',
  PRIMARY KEY  (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_userinfo
-- ----------------------------
INSERT INTO `t_userinfo` VALUES ('user1', '123', '爸爸大神', '男', '2018-10-03', 'upload/85c299748ff93de89d3b3157c2838e56.jpg', '13980830853', 'dashen@163.com', '四川省成都市红星路', '2018-10-12 15:08:06');
INSERT INTO `t_userinfo` VALUES ('user2', '123', '妈妈小薇', '女', '2018-10-08', 'upload/391c7336db59b8cf0e0e6892e5f03ac5.jpg', '18839850834', 'xiaowei@163.com', '四川南充冰江路', '2018-10-12 15:11:22');
