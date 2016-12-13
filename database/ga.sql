/*
Navicat MySQL Data Transfer

Source Server         : homstead
Source Server Version : 50712
Source Host           : localhost:33060
Source Database       : ga

Target Server Type    : MYSQL
Target Server Version : 50712
File Encoding         : 65001

Date: 2016-12-13 08:01:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for accounts
-- ----------------------------
DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ga_account_name` varchar(255) DEFAULT NULL,
  `ga_account_pass` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  `modified` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `deleted` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of accounts
-- ----------------------------
INSERT INTO `accounts` VALUES ('1', '1', 'Only Test', '12345678', 'wevnal@gmail.com', '12345', '1462942655', '1458636182', null);
INSERT INTO `accounts` VALUES ('9', '3', '沖縄ツーリスト', null, 'ict@otsinfo.co.jp', 'b75c25f041ea2032d6ae4df24e4d811b17de62514dc6acdcfac57ce333018ca4', '1463387510', '1462958570', '1463387510');
INSERT INTO `accounts` VALUES ('10', '4', 'FAJ', null, 'fa-j@a-manage.jp', '61b6b92c23863d6ce8c73730a83fa92bbe47a4ef78fe9b500b2434ebd0ed7ac9', '1463991876', '1462958722', null);
INSERT INTO `accounts` VALUES ('11', '5', 'Fajob', null, 'fajfactory@gmail.com', 'a311563499fc059d4a09a712f6000fb3473092072684ada18af9d7c7b35e2386', '1463991903', '1462958763', null);
INSERT INTO `accounts` VALUES ('12', '6', '横井パッケージ', null, 'info@yokoi-package.co.jp', 'a918549a78636bac63367483bebb06b7ac5336f232126585b87c9cca7fa1b12e', '1463122037', '1462958813', null);
INSERT INTO `accounts` VALUES ('13', '2', 'vu.thi.my.linh@miyatsu.vn', null, 'vu.thi.my.linh@miyatsu.vn', '9a3566684640aeeadb2aa1ec3e12f4d16e9dd91eaaf875670b8918c11688608c', '1462960061', '1462958955', null);
INSERT INTO `accounts` VALUES ('14', '7', 'kisocci@gmail.com', null, 'kisocci@gmail.com', 'e9702269fbd85b5e9b187a8566780f73df4eff6ed3ca7ab55dc08333cbc73801', '1462960659', '1462960373', null);
INSERT INTO `accounts` VALUES ('15', '2', 'Cleverads', null, 'chau.du@cleverads.vn', '97b2145659d8afbee5e06f8c394927497a3154290a448dd6a8bab226699d00a0', '1463018865', '1463018815', null);
INSERT INTO `accounts` VALUES ('16', '1', 'linh', null, 'vu.thi.my.linh@miyatsu.vn', '60c45712a087f589b09d91b4d8027504a71b73fe87301bfb9b28840de341e1e1', '1463123304', '1463123213', '1463123304');
INSERT INTO `accounts` VALUES ('17', '1', 'weblio株式会社', null, 'siro.linhvu@gmail.com', '3092d499f63100f6db07fe8e810af4cbc027cb6fcd1e455425e146a34c231264', '1463131802', '1463123359', null);
INSERT INTO `accounts` VALUES ('18', '1', '沖縄ツーリスト', null, 'ict@otsinfo.co.jp', 'a1d2282208205a6832a37601df840de2d2199d788b82e8ae14ea614bf701ba28', '1466750234', '1463387789', null);
INSERT INTO `accounts` VALUES ('19', '9', 'HUBLOG', null, 'huv.optimize@gmail.com', '016813ffb2795997a6e67b99d3bded51188edec9e940976407aa1035c4445205', '1463537652', '1463391435', '1463537652');
INSERT INTO `accounts` VALUES ('20', '12', 'HUVRID', null, 'maeda2266@gmail.com', '0bb1967f8bcb488fb9354b97613cfc04b92b22422fd16f78a789bff2290537ca', '1463452288', '1463451255', null);
INSERT INTO `accounts` VALUES ('21', '15', 'HUVRID', null, 'huv.optimize@gmail.com', '713fd5916c115f5d9cc846627423abf23de613ef1c602c11f962f9715a06c0ee', '1463537760', '1463537760', null);
INSERT INTO `accounts` VALUES ('22', '24', 'HUVRID', null, 'huv.optimize@gmail.com', '66e864210fef40dc450d699fc8243dc4e664f04933be73b93ca60a555a9d4dcf', '1466739762', '1466739762', null);
INSERT INTO `accounts` VALUES ('23', '24', 'HUVRID', null, 'huv.optimize@gmail.com', 'f11fd219061cb2af662ab565c48e1626a979563050bf91655f59798d53596739', '1466739857', '1466739857', null);
INSERT INTO `accounts` VALUES ('24', '24', 'HUVRID', null, 'huv.optimize@gmail.com', '904d12024c28c3cad17ecafb3a5e3fcb771b79c763856dd701cec2aa182c102b', '1466740291', '1466740045', null);
INSERT INTO `accounts` VALUES ('25', '1', 'テストHUVRID', null, 'huv.optimize@gmail.com', 'fcb294ae6614e850f9ecea9dad9ac50428fa81cad994ec3be0563e12be1acf37', '1468468060', '1468468060', null);
INSERT INTO `accounts` VALUES ('26', '1', '那覇情報システム専門学校', null, 'huv.optimize@gmail.com', '5ca1bd3356b4e274f9e05820177e74294139bcc0e144cc65d6838c1a23d46bbf', '1468573786', '1468573350', null);
INSERT INTO `accounts` VALUES ('27', '1', '沖縄電力', null, 'huv.optimize@gmail.com', '0dff6052f88f02d770cc32133f701fc7f37fdc636b111e652622e1145cc812cd', '1469524753', '1469524671', null);

-- ----------------------------
-- Table structure for acos
-- ----------------------------
DROP TABLE IF EXISTS `acos`;
CREATE TABLE `acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_acos_lft_rght` (`lft`,`rght`),
  KEY `idx_acos_alias` (`alias`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of acos
-- ----------------------------
INSERT INTO `acos` VALUES ('1', null, null, null, 'controllers', '1', '78');
INSERT INTO `acos` VALUES ('2', '1', null, null, 'Accounts', '2', '19');
INSERT INTO `acos` VALUES ('3', '2', null, null, 'index', '3', '4');
INSERT INTO `acos` VALUES ('4', '2', null, null, 'show', '5', '6');
INSERT INTO `acos` VALUES ('5', '2', null, null, 'add', '7', '8');
INSERT INTO `acos` VALUES ('6', '2', null, null, 'edit', '9', '10');
INSERT INTO `acos` VALUES ('7', '2', null, null, 'cedit', '11', '12');
INSERT INTO `acos` VALUES ('8', '2', null, null, 'delete', '13', '14');
INSERT INTO `acos` VALUES ('9', '2', null, null, 'changeLanguage', '15', '16');
INSERT INTO `acos` VALUES ('10', '2', null, null, 'getUser', '17', '18');
INSERT INTO `acos` VALUES ('11', '1', null, null, 'Pages', '20', '27');
INSERT INTO `acos` VALUES ('12', '11', null, null, 'display', '21', '22');
INSERT INTO `acos` VALUES ('13', '11', null, null, 'changeLanguage', '23', '24');
INSERT INTO `acos` VALUES ('14', '11', null, null, 'getUser', '25', '26');
INSERT INTO `acos` VALUES ('15', '1', null, null, 'Reports', '28', '41');
INSERT INTO `acos` VALUES ('16', '15', null, null, 'index', '29', '30');
INSERT INTO `acos` VALUES ('17', '15', null, null, 'make', '31', '32');
INSERT INTO `acos` VALUES ('18', '15', null, null, 'cancel', '33', '34');
INSERT INTO `acos` VALUES ('19', '15', null, null, 'download', '35', '36');
INSERT INTO `acos` VALUES ('20', '15', null, null, 'changeLanguage', '37', '38');
INSERT INTO `acos` VALUES ('21', '15', null, null, 'getUser', '39', '40');
INSERT INTO `acos` VALUES ('22', '1', null, null, 'Sites', '42', '49');
INSERT INTO `acos` VALUES ('23', '22', null, null, 'delete', '43', '44');
INSERT INTO `acos` VALUES ('24', '22', null, null, 'changeLanguage', '45', '46');
INSERT INTO `acos` VALUES ('25', '22', null, null, 'getUser', '47', '48');
INSERT INTO `acos` VALUES ('26', '1', null, null, 'Users', '50', '73');
INSERT INTO `acos` VALUES ('27', '26', null, null, 'index', '51', '52');
INSERT INTO `acos` VALUES ('28', '26', null, null, 'add', '53', '54');
INSERT INTO `acos` VALUES ('29', '26', null, null, 'edit', '55', '56');
INSERT INTO `acos` VALUES ('30', '26', null, null, 'delete', '57', '58');
INSERT INTO `acos` VALUES ('31', '26', null, null, 'login', '59', '60');
INSERT INTO `acos` VALUES ('32', '26', null, null, 'logout', '61', '62');
INSERT INTO `acos` VALUES ('33', '26', null, null, 'getGroup', '63', '64');
INSERT INTO `acos` VALUES ('34', '26', null, null, 'initDB', '65', '66');
INSERT INTO `acos` VALUES ('35', '26', null, null, 'test', '67', '68');
INSERT INTO `acos` VALUES ('36', '26', null, null, 'changeLanguage', '69', '70');
INSERT INTO `acos` VALUES ('37', '26', null, null, 'getUser', '71', '72');
INSERT INTO `acos` VALUES ('38', '1', null, null, 'AclExtras', '74', '75');
INSERT INTO `acos` VALUES ('39', '1', null, null, 'Bower', '76', '77');

-- ----------------------------
-- Table structure for aros
-- ----------------------------
DROP TABLE IF EXISTS `aros`;
CREATE TABLE `aros` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_aros_lft_rght` (`lft`,`rght`),
  KEY `idx_aros_alias` (`alias`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of aros
-- ----------------------------
INSERT INTO `aros` VALUES ('1', null, 'Group', '1', 'admin', '1', '2');
INSERT INTO `aros` VALUES ('2', null, 'Group', '2', 'eigyo1', '3', '4');
INSERT INTO `aros` VALUES ('3', null, 'Group', '3', 'eigyo2', '5', '6');
INSERT INTO `aros` VALUES ('4', null, 'Group', '4', 'unyou1', '7', '8');
INSERT INTO `aros` VALUES ('5', null, 'Group', '5', 'unyou2', '9', '10');
INSERT INTO `aros` VALUES ('6', null, 'Group', '6', 'agency', '11', '12');
INSERT INTO `aros` VALUES ('7', null, 'Group', '7', 'client', '13', '14');
INSERT INTO `aros` VALUES ('8', null, null, null, 'admin', '15', '16');
INSERT INTO `aros` VALUES ('9', null, null, null, 'eigyo1', '17', '18');
INSERT INTO `aros` VALUES ('10', null, null, null, 'eigyo2', '19', '20');
INSERT INTO `aros` VALUES ('11', null, null, null, 'unyou1', '21', '22');
INSERT INTO `aros` VALUES ('12', null, null, null, 'unyou2', '23', '24');
INSERT INTO `aros` VALUES ('13', null, null, null, 'agency', '25', '26');
INSERT INTO `aros` VALUES ('14', null, null, null, 'client', '27', '28');
INSERT INTO `aros` VALUES ('15', null, null, null, 'admin', '29', '30');
INSERT INTO `aros` VALUES ('16', null, null, null, 'eigyo1', '31', '32');
INSERT INTO `aros` VALUES ('17', null, null, null, 'eigyo2', '33', '34');
INSERT INTO `aros` VALUES ('18', null, null, null, 'unyou1', '35', '36');
INSERT INTO `aros` VALUES ('19', null, null, null, 'unyou2', '37', '38');
INSERT INTO `aros` VALUES ('20', null, null, null, 'agency', '39', '40');
INSERT INTO `aros` VALUES ('21', null, null, null, 'client', '41', '42');
INSERT INTO `aros` VALUES ('22', null, null, null, 'admin', '43', '44');
INSERT INTO `aros` VALUES ('23', null, null, null, 'eigyo1', '45', '46');
INSERT INTO `aros` VALUES ('24', null, null, null, 'eigyo2', '47', '48');
INSERT INTO `aros` VALUES ('25', null, null, null, 'unyou1', '49', '50');
INSERT INTO `aros` VALUES ('26', null, null, null, 'unyou2', '51', '52');
INSERT INTO `aros` VALUES ('27', null, null, null, 'agency', '53', '54');
INSERT INTO `aros` VALUES ('28', null, null, null, 'client', '55', '56');
INSERT INTO `aros` VALUES ('29', null, null, null, 'admin', '57', '58');
INSERT INTO `aros` VALUES ('30', null, null, null, 'eigyo1', '59', '60');
INSERT INTO `aros` VALUES ('31', null, null, null, 'eigyo2', '61', '62');
INSERT INTO `aros` VALUES ('32', null, null, null, 'unyou1', '63', '64');
INSERT INTO `aros` VALUES ('33', null, null, null, 'unyou2', '65', '66');
INSERT INTO `aros` VALUES ('34', null, null, null, 'agency', '67', '68');
INSERT INTO `aros` VALUES ('35', null, null, null, 'client', '69', '70');
INSERT INTO `aros` VALUES ('36', null, null, null, 'admin', '71', '72');
INSERT INTO `aros` VALUES ('37', null, null, null, 'eigyo1', '73', '74');
INSERT INTO `aros` VALUES ('38', null, null, null, 'eigyo2', '75', '76');
INSERT INTO `aros` VALUES ('39', null, null, null, 'unyou1', '77', '78');
INSERT INTO `aros` VALUES ('40', null, null, null, 'unyou2', '79', '80');
INSERT INTO `aros` VALUES ('41', null, null, null, 'agency', '81', '82');
INSERT INTO `aros` VALUES ('42', null, null, null, 'client', '83', '84');
INSERT INTO `aros` VALUES ('43', null, null, null, 'admin', '85', '86');
INSERT INTO `aros` VALUES ('44', null, null, null, 'eigyo1', '87', '88');
INSERT INTO `aros` VALUES ('45', null, null, null, 'eigyo2', '89', '90');
INSERT INTO `aros` VALUES ('46', null, null, null, 'unyou1', '91', '92');
INSERT INTO `aros` VALUES ('47', null, null, null, 'unyou2', '93', '94');
INSERT INTO `aros` VALUES ('48', null, null, null, 'agency', '95', '96');
INSERT INTO `aros` VALUES ('49', null, null, null, 'client', '97', '98');
INSERT INTO `aros` VALUES ('50', null, null, null, 'admin', '99', '100');
INSERT INTO `aros` VALUES ('51', null, null, null, 'eigyo1', '101', '102');
INSERT INTO `aros` VALUES ('52', null, null, null, 'eigyo2', '103', '104');
INSERT INTO `aros` VALUES ('53', null, null, null, 'unyou1', '105', '106');
INSERT INTO `aros` VALUES ('54', null, null, null, 'unyou2', '107', '108');
INSERT INTO `aros` VALUES ('55', null, null, null, 'agency', '109', '110');
INSERT INTO `aros` VALUES ('56', null, null, null, 'client', '111', '112');
INSERT INTO `aros` VALUES ('57', null, null, null, 'admin', '113', '114');
INSERT INTO `aros` VALUES ('58', null, null, null, 'eigyo1', '115', '116');
INSERT INTO `aros` VALUES ('59', null, null, null, 'eigyo2', '117', '118');
INSERT INTO `aros` VALUES ('60', null, null, null, 'unyou1', '119', '120');
INSERT INTO `aros` VALUES ('61', null, null, null, 'unyou2', '121', '122');
INSERT INTO `aros` VALUES ('62', null, null, null, 'agency', '123', '124');
INSERT INTO `aros` VALUES ('63', null, null, null, 'client', '125', '126');
INSERT INTO `aros` VALUES ('64', null, null, null, 'admin', '127', '128');
INSERT INTO `aros` VALUES ('65', null, null, null, 'eigyo1', '129', '130');
INSERT INTO `aros` VALUES ('66', null, null, null, 'eigyo2', '131', '132');
INSERT INTO `aros` VALUES ('67', null, null, null, 'unyou1', '133', '134');
INSERT INTO `aros` VALUES ('68', null, null, null, 'unyou2', '135', '136');
INSERT INTO `aros` VALUES ('69', null, null, null, 'agency', '137', '138');
INSERT INTO `aros` VALUES ('70', null, null, null, 'client', '139', '140');
INSERT INTO `aros` VALUES ('71', null, null, null, 'admin', '141', '142');
INSERT INTO `aros` VALUES ('72', null, null, null, 'eigyo1', '143', '144');
INSERT INTO `aros` VALUES ('73', null, null, null, 'eigyo2', '145', '146');
INSERT INTO `aros` VALUES ('74', null, null, null, 'unyou1', '147', '148');
INSERT INTO `aros` VALUES ('75', null, null, null, 'unyou2', '149', '150');
INSERT INTO `aros` VALUES ('76', null, null, null, 'agency', '151', '152');
INSERT INTO `aros` VALUES ('77', null, null, null, 'client', '153', '154');
INSERT INTO `aros` VALUES ('78', null, null, null, 'admin', '155', '156');
INSERT INTO `aros` VALUES ('79', null, null, null, 'eigyo1', '157', '158');
INSERT INTO `aros` VALUES ('80', null, null, null, 'eigyo2', '159', '160');
INSERT INTO `aros` VALUES ('81', null, null, null, 'unyou1', '161', '162');
INSERT INTO `aros` VALUES ('82', null, null, null, 'unyou2', '163', '164');
INSERT INTO `aros` VALUES ('83', null, null, null, 'agency', '165', '166');
INSERT INTO `aros` VALUES ('84', null, null, null, 'client', '167', '168');
INSERT INTO `aros` VALUES ('85', null, null, null, 'admin', '169', '170');
INSERT INTO `aros` VALUES ('86', null, null, null, 'eigyo1', '171', '172');
INSERT INTO `aros` VALUES ('87', null, null, null, 'eigyo2', '173', '174');
INSERT INTO `aros` VALUES ('88', null, null, null, 'unyou1', '175', '176');
INSERT INTO `aros` VALUES ('89', null, null, null, 'unyou2', '177', '178');
INSERT INTO `aros` VALUES ('90', null, null, null, 'agency', '179', '180');
INSERT INTO `aros` VALUES ('91', null, null, null, 'client', '181', '182');
INSERT INTO `aros` VALUES ('92', null, null, null, 'admin', '183', '184');
INSERT INTO `aros` VALUES ('93', null, null, null, 'eigyo1', '185', '186');
INSERT INTO `aros` VALUES ('94', null, null, null, 'eigyo2', '187', '188');
INSERT INTO `aros` VALUES ('95', null, null, null, 'unyou1', '189', '190');
INSERT INTO `aros` VALUES ('96', null, null, null, 'unyou2', '191', '192');
INSERT INTO `aros` VALUES ('97', null, null, null, 'agency', '193', '194');
INSERT INTO `aros` VALUES ('98', null, null, null, 'client', '195', '196');

-- ----------------------------
-- Table structure for aros_acos
-- ----------------------------
DROP TABLE IF EXISTS `aros_acos`;
CREATE TABLE `aros_acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `aro_id` int(10) NOT NULL,
  `aco_id` int(10) NOT NULL,
  `_create` varchar(2) NOT NULL DEFAULT '0',
  `_read` varchar(2) NOT NULL DEFAULT '0',
  `_update` varchar(2) NOT NULL DEFAULT '0',
  `_delete` varchar(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ARO_ACO_KEY` (`aro_id`,`aco_id`),
  KEY `idx_aco_id` (`aco_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of aros_acos
-- ----------------------------
INSERT INTO `aros_acos` VALUES ('1', '1', '1', '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES ('2', '2', '1', '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES ('3', '3', '1', '-1', '-1', '-1', '-1');
INSERT INTO `aros_acos` VALUES ('4', '3', '15', '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES ('5', '3', '2', '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES ('6', '4', '1', '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES ('7', '5', '1', '-1', '-1', '-1', '-1');
INSERT INTO `aros_acos` VALUES ('8', '5', '15', '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES ('9', '5', '2', '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES ('10', '6', '1', '1', '1', '1', '1');
INSERT INTO `aros_acos` VALUES ('11', '7', '1', '-1', '-1', '-1', '-1');

-- ----------------------------
-- Table structure for groups
-- ----------------------------
DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `modified` int(8) NOT NULL,
  `created` int(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of groups
-- ----------------------------
INSERT INTO `groups` VALUES ('1', 'admin', '0', '0');
INSERT INTO `groups` VALUES ('2', 'eigyo1', '0', '0');
INSERT INTO `groups` VALUES ('3', 'eigyo2', '0', '0');
INSERT INTO `groups` VALUES ('4', 'unyou1 ', '0', '0');
INSERT INTO `groups` VALUES ('5', 'unyou2', '0', '0');
INSERT INTO `groups` VALUES ('6', 'agency', '0', '0');
INSERT INTO `groups` VALUES ('7', 'client', '0', '0');

-- ----------------------------
-- Table structure for oauths
-- ----------------------------
DROP TABLE IF EXISTS `oauths`;
CREATE TABLE `oauths` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `access_token` text NOT NULL,
  `refresh_token` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oauths
-- ----------------------------
INSERT INTO `oauths` VALUES ('12', 'wevnal@gmail.com', '{\"access_token\":\"ya29.CjHfAifGie3gNjL_KbMMNgHeVCYxP3Izzaqre4BhtCc8teIGQ19iZJzCQoiwM79MTfn6\",\"token_type\":\"Bearer\",\"expires_in\":3600,\"id_token\":\"eyJhbGciOiJSUzI1NiIsImtpZCI6ImMyNTFhYWM0ZjFmZWYxYWRkYmVjZDM5ZmE2ODgxNmM1YTk1YmM1MmUifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXRfaGFzaCI6IllYNnowSjN2c2pCcXB3MXpILWl0V3ciLCJhdWQiOiI1NjAxMjQ2MjcwOTgtaTh0aWIyNjN0YW9haW40bmJxZ3FsYmF2N283Z2h2c3EuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMTcxMDM1ODExNzc2NDQyNjcxMjIiLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwiYXpwIjoiNTYwMTI0NjI3MDk4LWk4dGliMjYzdGFvYWluNG5icWdxbGJhdjdvN2dodnNxLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiZW1haWwiOiJ3ZXZuYWxAZ21haWwuY29tIiwiaWF0IjoxNDYyOTU4ODk1LCJleHAiOjE0NjI5NjI0OTV9.JQ1vu-lLVMcMleGklz-xVVfW6BbbpZX7ptYDpKEVYbhoELgleLTcsEsL7KWk58S2xOEKR7AzhQjyA5N2A1OZpqZFAt22aa-uODVn_BiivVuZ4icmKrjC_0M0dTfcEYIqfMNP7r9T89Cvc0S0b2F-7z1UI2b-4KlCGEJ3W0vKun6vhFKpqJ2bBj10nWpJzpHVUSjCQgBnDDW00FUhjZkQQMbMj7XafKLdzmH_G-pqI-JKBfIViDMOciNQ9mb9VEnA8ckTRQ1VTcsxWMnON0LSj-ygskUYsF9jU6qflBeSLzsQUoKvmy7JvErzaci9cItjB6dDvPvTJPoBV6yZejN2jQ\",\"refresh_token\":\"1\\/sDZVcqU_T1I8fQ2EqBPj_O64e66hi_g3eAtGY6buA04MEudVrK5jSpoR30zcRFq6\",\"created\":1462958895}', '1/sDZVcqU_T1I8fQ2EqBPj_O64e66hi_g3eAtGY6buA04MEudVrK5jSpoR30zcRFq6');
INSERT INTO `oauths` VALUES ('13', 'vu.thi.my.linh@miyatsu.vn', '{\"access_token\":\"ya29.CjHfAoo94YpjmrmpXAXkCXjzjjlrCConue9myaODGhuAGPM-lvu_1cq5ZSmeMusy4ulG\",\"token_type\":\"Bearer\",\"expires_in\":3600,\"id_token\":\"eyJhbGciOiJSUzI1NiIsImtpZCI6ImMyNTFhYWM0ZjFmZWYxYWRkYmVjZDM5ZmE2ODgxNmM1YTk1YmM1MmUifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXRfaGFzaCI6Ik92SEx6eUhhSHl4NHVxRmdrU2ppS2ciLCJhdWQiOiI1NjAxMjQ2MjcwOTgtaTh0aWIyNjN0YW9haW40bmJxZ3FsYmF2N283Z2h2c3EuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMDk1MjMwNjI1MjAxMTUxNzMxODYiLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwiYXpwIjoiNTYwMTI0NjI3MDk4LWk4dGliMjYzdGFvYWluNG5icWdxbGJhdjdvN2dodnNxLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiaGQiOiJtaXlhdHN1LnZuIiwiZW1haWwiOiJ2dS50aGkubXkubGluaEBtaXlhdHN1LnZuIiwiaWF0IjoxNDYyOTU5MDA0LCJleHAiOjE0NjI5NjI2MDR9.kWnXOI8vRNYkxO1ovoiJwo4QVXLkYq5HSt-EzUfc2XqRTQuvZMgEfSEcqMY2Dbe4Ql43BUkIbXySzP1kBFStdP6MS9C9N_2DvaJuGX97C4YQrT9nhSFPAastALpFcYEiq2eEC3cSTGLAnD9kyicfmORNb-MC5HNMwOPz84dwaGUgS-J7TMpWfEnNfI8BIjZ1RX7-_dWd148opagjxzSbqclQotMHs0hv2BjwVIOv69om9mQ4EF--K5DgKGaJmU8Ja4A8Eo4I6ZSqG_Ueqgsuoc10QOB37HjLSPFC4oz2NR0vJKaa8FpIAvACE2DBUXBA5bP6OHeUkA_PjMFRK0stvQ\",\"refresh_token\":\"1\\/aRXlmI_2W6ixewNdwMc4i90YjDIeb1zccwuPHJXXQGkMEudVrK5jSpoR30zcRFq6\",\"created\":1462959004}', '1/aRXlmI_2W6ixewNdwMc4i90YjDIeb1zccwuPHJXXQGkMEudVrK5jSpoR30zcRFq6');
INSERT INTO `oauths` VALUES ('14', 'kisocci@gmail.com', '{\"access_token\":\"ya29.CjHfApKeFqpgxubCwN_MnAit6DXvw58vuu329l3FM106JWu7ZL7XjFl4djsIOpkJujEZ\",\"token_type\":\"Bearer\",\"expires_in\":3600,\"id_token\":\"eyJhbGciOiJSUzI1NiIsImtpZCI6ImMyNTFhYWM0ZjFmZWYxYWRkYmVjZDM5ZmE2ODgxNmM1YTk1YmM1MmUifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXRfaGFzaCI6Im53OW9Jb1pTUjNDT3hVWmcyOVNFTmciLCJhdWQiOiI1NjAxMjQ2MjcwOTgtaTh0aWIyNjN0YW9haW40bmJxZ3FsYmF2N283Z2h2c3EuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMTgwNjY4ODg0OTA1MTIwMzY2ODAiLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwiYXpwIjoiNTYwMTI0NjI3MDk4LWk4dGliMjYzdGFvYWluNG5icWdxbGJhdjdvN2dodnNxLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiZW1haWwiOiJraXNvY2NpQGdtYWlsLmNvbSIsImlhdCI6MTQ2Mjk2MDQyNywiZXhwIjoxNDYyOTY0MDI3fQ.MuDWDriNu_O8K8r22iCXum61pzk7eQ7SJbqHc0LRrTIjiJQb1ZCeOy-bayVyeucZLAbWL4TOolaAP4LM4cG6V9URMdbJeqB8kOrnlyqgm08j3nm8HnxaliJsJX9NlILnV1P7jGDCTGtsPJ70agtT0SlrITZziHXd0pTDY913s_bR36UFogWtW7tPJJTrrNpJ5hbcPGEWOKKauSSFbFQJLnMbnCh3SWrrigLw-Nx9ivM2qw9ZGZOPjaMCQS-VcXa9f2i3gZkE2gbIe6nMHtk5a_qrmtykKdjHYvZiZOwwmHyHbA-g6wbh828zTPVNybYo76Uy91gQG5zYYXWxwwYKzA\",\"refresh_token\":\"1\\/jBs-o_Pe0r7ZBHVpX437qa1-uTtWsWc827M2vsotAL4MEudVrK5jSpoR30zcRFq6\",\"created\":1462960427}', '1/jBs-o_Pe0r7ZBHVpX437qa1-uTtWsWc827M2vsotAL4MEudVrK5jSpoR30zcRFq6');
INSERT INTO `oauths` VALUES ('15', 'siro.linhvu@gmail.com', '{\"access_token\":\"ya29.CjHhAkuLEaEMIV4qG6N-x0HzAKJ-bEOtdezrxuN1bJMmgJGHgKuqJZIH2dBat_AG661m\",\"token_type\":\"Bearer\",\"expires_in\":3600,\"id_token\":\"eyJhbGciOiJSUzI1NiIsImtpZCI6ImU3ZGJmNTI2ZjYzOWMyMTRjZDc3YjM5NmVjYjlkN2Y4MWQ0N2IzODIifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXRfaGFzaCI6Ik5fUkRSMEtodjBzNVM5NjNLRHJMTkEiLCJhdWQiOiI1NjAxMjQ2MjcwOTgtaTh0aWIyNjN0YW9haW40bmJxZ3FsYmF2N283Z2h2c3EuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMDEyODk3NzIxMjgyMDE5MzU0NzgiLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwiYXpwIjoiNTYwMTI0NjI3MDk4LWk4dGliMjYzdGFvYWluNG5icWdxbGJhdjdvN2dodnNxLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiZW1haWwiOiJzaXJvLmxpbmh2dUBnbWFpbC5jb20iLCJpYXQiOjE0NjMxMjM0MzgsImV4cCI6MTQ2MzEyNzAzOH0.Qd7reEIylrBZIc_4eLVCGp2YgJCYsGrwiuxsF-U5ObOAbmoI2NXQrYVIIQ3CZDs56jgqeNPwaqQzvEsCpdW3hIwtkD0dlxJHlKe4xBFKMSMn-_xBIx7ooewMv489hq6k6UxfP9x8thpcdl957ydoG6yKK2VOsuoRF6eLTaLk9lc_W8NH_4ok8AiQnwfPX78PyFwlhGzvEBf99nC12PhjPBvzgjW9EkaZY-uRiTtoncVBqopW8C_0eSOvllMl_pombOHIlBTw5YQvlQGcg6frYXFw5MKE3-8VtyeQgYxQaAFd3JXXQo9N-eqpyA7O_F8YvTJDlwdlAsk4Mx5Eo6QVlQ\",\"refresh_token\":\"1\\/SXDlEGj_3ANmYidMhE4wS96ptlleThSwMFy1v62dX0A\",\"created\":1463123438}', '1/SXDlEGj_3ANmYidMhE4wS96ptlleThSwMFy1v62dX0A');
INSERT INTO `oauths` VALUES ('16', 'huv.optimize@gmail.com', '{\"access_token\":\"ya29.CjHkAkSTprRhuHzJ_BiR8RJjbIqbgCIIEqszx11eNGyXIYIEcxVbNcc9CKMj5e_tOqgu\",\"token_type\":\"Bearer\",\"expires_in\":3600,\"id_token\":\"eyJhbGciOiJSUzI1NiIsImtpZCI6IjJlNGMxZmNmNmU5NjgyODIzOTdmZTAzYWI4NDhkNGU5YzljMjdiYjMifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXRfaGFzaCI6IjB4aWdZRjhQQ1VZYzFKeVVDcWFVd1EiLCJhdWQiOiI1NjAxMjQ2MjcwOTgtaTh0aWIyNjN0YW9haW40bmJxZ3FsYmF2N283Z2h2c3EuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMTQxMDM5NjM2Mzk4MDI2NDAyMDEiLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwiYXpwIjoiNTYwMTI0NjI3MDk4LWk4dGliMjYzdGFvYWluNG5icWdxbGJhdjdvN2dodnNxLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiZW1haWwiOiJodXYub3B0aW1pemVAZ21haWwuY29tIiwiaWF0IjoxNDYzMzkxNTQ5LCJleHAiOjE0NjMzOTUxNDl9.lH_INHC6F9VGyw00_O5UAdLbOcM8l27qeByZ2IleXb8bUjwIGmIL_2xYe0hfMOZeFCDnWNqEtDdb3K6yJWhuWEG6bhiGPTn4jUpNNbNZb9AUeyL6UQlKM2W2afgFAlyK7XVIReD7oOeYQPgeZdn-hWQQ7NdZYokbyKWvt8buhylBVdGQz9yvw1JVtbsoga9hJDp1DEO1EcWk1mcvmGG68By3jCzhdJQmrnjy0u_7l8SG8MdoaEqz-vWexUh2RwZxhKPXNelV9GP-vBCupPsi3dVKGCs-8vGnhqAWn9CHRRdAjtns0-Z9hBSgy-zmSLebtiJGBgEwXw45C3U9Z10MmQ\",\"refresh_token\":\"1\\/LY83AJWfRERLVSzOVtx2C3r3SkIUkIqCbWAztxgGQlE\",\"created\":1463391549}', '1/LY83AJWfRERLVSzOVtx2C3r3SkIUkIqCbWAztxgGQlE');
INSERT INTO `oauths` VALUES ('17', 'huvrid@gmail.com', '{\"access_token\":\"ya29.CjHlAkOqG_a1GP0HfXxJXD4CJQm8YzRhJem6JqBQMX39rvD6mfkm_xDT_S-m0MkUrZ4A\",\"token_type\":\"Bearer\",\"expires_in\":3600,\"id_token\":\"eyJhbGciOiJSUzI1NiIsImtpZCI6IjUyNDNiNDI5ZGUxOGY0NTY4NTYwOTMwNDY3NDBlMDU2NjRjNDI5OTYifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXRfaGFzaCI6ImNnSk9FamppOVRzQ1pzeEVZay1ZdFEiLCJhdWQiOiI1NjAxMjQ2MjcwOTgtaTh0aWIyNjN0YW9haW40bmJxZ3FsYmF2N283Z2h2c3EuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMTYyMjQzNTcwMjA0MTc3ODU3MzgiLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwiYXpwIjoiNTYwMTI0NjI3MDk4LWk4dGliMjYzdGFvYWluNG5icWdxbGJhdjdvN2dodnNxLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiZW1haWwiOiJodXZyaWRAZ21haWwuY29tIiwiaWF0IjoxNDYzNDUxNDA2LCJleHAiOjE0NjM0NTUwMDZ9.0h1JXoYIC2oOgLZVik-vlrUenqKburLxMDE5sr5bLKgnJHgDBhCstx0BcUCxvoW556JM7--PyCg1arKEzWz-qQYMUPNhvW_tB7etXOpXunhjvWn3fc7GFZmhO7Hy4qs_ueN-TGvrdcrbnMISkSTOE3p2AqSpKbc2bBgY7d4zxXrdLDgKgtmnQuIrjn1HdJcJLw7M8Dw4X99OGC8aGFE_j5Kz_L7_a8P11YzHD5Wfhl3iTXZfxxR578Tz4P_3IrpNQrxK5fYkcMzrDKr5D7EIzd_CDGwMwiM1uoDUPAht8fYmMt1hi9fJYTlymSOR688ClAOExDWWTY7DRgG6CGdBOw\",\"refresh_token\":\"1\\/VP9H9DNdlTCj6huy0bq6fkOJfmncdAqP8yM2Km49j-I\",\"created\":1463451406}', '1/VP9H9DNdlTCj6huy0bq6fkOJfmncdAqP8yM2Km49j-I');

-- ----------------------------
-- Table structure for ranks
-- ----------------------------
DROP TABLE IF EXISTS `ranks`;
CREATE TABLE `ranks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ad_id` int(11) NOT NULL,
  `page_id` int(11) DEFAULT NULL,
  `rank_number` float(11,2) NOT NULL DEFAULT '0.00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ranks
-- ----------------------------

-- ----------------------------
-- Table structure for reports
-- ----------------------------
DROP TABLE IF EXISTS `reports`;
CREATE TABLE `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `error_msg` varchar(255) DEFAULT NULL,
  `start_time` int(8) NOT NULL,
  `end_time` int(8) NOT NULL,
  `status` int(1) NOT NULL,
  `lang` varchar(255) DEFAULT NULL,
  `created` int(8) NOT NULL,
  `modified` int(8) DEFAULT NULL,
  `deleted` int(8) DEFAULT NULL,
  `type_make_report` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of reports
-- ----------------------------
INSERT INTO `reports` VALUES ('4', '3', '1', 'Report_3_20160901_20160930_1476075422.xlsm', null, '1472655600', '1475161200', '3', 'jpn', '1476075422', '1476075439', null, '1');
INSERT INTO `reports` VALUES ('5', '1', '1', 'Report_1_20160901_20160930_1476075624.xlsm', null, '1472655600', '1475161200', '3', 'jpn', '1476075624', '1476076401', null, '2');
INSERT INTO `reports` VALUES ('6', '2', '1', 'Report_2_20160901_20160930_1476075628.xlsm', null, '1472655600', '1475161200', '3', 'jpn', '1476075628', '1476076457', null, '2');
INSERT INTO `reports` VALUES ('102', '1', '1', 'Report_1_20160901_20160930_1476154895.xlsm', null, '1472655600', '1475161200', '3', 'jpn', '1476154895', '1476154919', null, '1');
INSERT INTO `reports` VALUES ('103', '2', '1', 'Report_2_20160901_20160930_1476154903.xlsm', null, '1472655600', '1475161200', '3', 'jpn', '1476154903', '1476154927', null, '1');
INSERT INTO `reports` VALUES ('104', '1', '1', 'Report_1_20160901_20160930_1476155769.xlsm', null, '1472655600', '1475161200', '3', 'jpn', '1476155769', '1476155798', null, '1');
INSERT INTO `reports` VALUES ('105', '2', '1', 'Report_2_20160901_20160930_1476155775.xlsm', null, '1472655600', '1475161200', '3', 'jpn', '1476155775', '1476155807', null, '1');
INSERT INTO `reports` VALUES ('110', '14', '1', 'Report_14_20160901_20160930_1476155949.xlsm', null, '1472655600', '1475161200', '3', 'jpn', '1476155949', '1476155984', null, '1');
INSERT INTO `reports` VALUES ('112', '3', '1', 'Report_3_20160901_20160930_1476155955.xlsm', null, '1472655600', '1475161200', '3', 'jpn', '1476155955', '1476155992', null, '1');
INSERT INTO `reports` VALUES ('113', '1', '1', 'Report_1_20160901_20160930_1476155959.xlsm', null, '1472655600', '1475161200', '3', 'jpn', '1476155959', '1476155994', null, '1');
INSERT INTO `reports` VALUES ('115', '2', '1', 'Report_2_20160901_20160930_1476155965.xlsm', null, '1472655600', '1475161200', '3', 'jpn', '1476155965', '1476156046', null, '1');
INSERT INTO `reports` VALUES ('116', '1', '1', null, 'Unknown SSL protocol error in connection to www.googleapis.com:443 ', '1477926000', '1480431600', '4', 'jpn', '1481161216', '1481161227', null, '1');

-- ----------------------------
-- Table structure for sites
-- ----------------------------
DROP TABLE IF EXISTS `sites`;
CREATE TABLE `sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `view_id` varchar(255) DEFAULT NULL,
  `lang` varchar(3) NOT NULL DEFAULT 'jpn',
  `modified` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `deleted` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sites
-- ----------------------------
INSERT INTO `sites` VALUES ('1', '1', 'http://wevnal.co.jp/', '46274680', 'jpn', '1462942655', '1458636182', '78a658b5b9e0eda53e287c12842a97cfdb2946b298cdb11765aef3442a528e76', null);
INSERT INTO `sites` VALUES ('2', '1', 'http://huvrid.co.jp/', '68681269', 'jpn', '1462942655', '1458636182', '5feac60cd5c301c2db54d1035f327f5cdb2946b298cdb11765aef3442a528e76', null);
INSERT INTO `sites` VALUES ('3', '1', 'http://www.blocco-deli.co.jp/', '4000306', 'jpn', '1462942655', '1461122183', 'b8513d437b5a260e97c8e79f79d30010426793f2e1f802189b042219b04683f7', null);
INSERT INTO `sites` VALUES ('4', '1', 'http://cleverads.vn', '36117585', 'vie', '1463037058', '1462445835', 'c746afcd6648c76442f21bd81f3a248f5f27cf00e063f3e19b5927052c0fb36d', '1463037058');
INSERT INTO `sites` VALUES ('5', '2', 'http://www.ranrantour.jp/', '', 'jpn', '1462506379', '1462506379', 'c4e00a00d4ced5d89419e7e7bd2831aa7f8de4606835be59c42951f02bb27e83', null);
INSERT INTO `sites` VALUES ('6', '3', 'http://www.fa-j.co.jp/', '', 'jpn', '1462506455', '1462506455', '1fb4ba1544e7cb4de06f2723fb8b9d47164bc45727bb6239843f8828500438a0', null);
INSERT INTO `sites` VALUES ('7', '4', 'http://fajob.jp/', '', 'jpn', '1462506497', '1462506497', '288b45b7f44290444104df75945020bb08b83209c7e79a355599cb43a66f637e', null);
INSERT INTO `sites` VALUES ('8', '5', 'http://www.yokoi-package.co.jp/', '89243342', 'jpn', '1462853164', '1462506604', '377cbe7d2a64e7f6ebe5b74eeb157423c976171a780a2b5fb176db751c533ad7', null);
INSERT INTO `sites` VALUES ('9', '6', 'http://www.ranrantour.jp/', '56432734', 'jpn', '1462795731', '1462506861', 'd076efe991a04a89d429ed86d36bff79b6d354c2febe7a1986fef35ed062d197', null);
INSERT INTO `sites` VALUES ('10', '7', 'http://www.fa-j.co.jp/', '', 'jpn', '1462506913', '1462506913', 'adda9284ca08abf1fae11390628b1148daf6866ff24c1f57780a3e3dc651bdb0', null);
INSERT INTO `sites` VALUES ('11', '8', 'http://fajob.jp/', '', 'jpn', '1462506953', '1462506953', '9503f3685b0438386c1b22215bf21a3ccd9728f41d8601f1f21dffed757b1f65', null);
INSERT INTO `sites` VALUES ('12', '6', null, '', 'jpn', '1462795723', '1462795696', '0ea7b1668022fbd108f9cc0f95c72ab975d2abaada9187bfda1edd64f48739d7', '1462795723');
INSERT INTO `sites` VALUES ('13', '9', 'http://www.ranrantour.jp/', '', 'jpn', '1462958570', '1462958570', 'b294db6a4f94f873675c20aa0ee850de17de62514dc6acdcfac57ce333018ca4', null);
INSERT INTO `sites` VALUES ('14', '10', 'http://www.fa-j.co.jp/', '58025359', 'jpn', '1462958722', '1462958722', '6c4b6b47358159c8c444f34dc17695c8be47a4ef78fe9b500b2434ebd0ed7ac9', null);
INSERT INTO `sites` VALUES ('15', '11', 'http://fajob.jp/', '109348583', 'jpn', '1462958763', '1462958763', '728b4d334a1c026c4f7204fccff7b9c3473092072684ada18af9d7c7b35e2386', null);
INSERT INTO `sites` VALUES ('16', '12', 'http://www.yokoi-package.co.jp/', '', 'jpn', '1462958813', '1462958813', '2bec47973271292f612ceda2d416be0dac5336f232126585b87c9cca7fa1b12e', null);
INSERT INTO `sites` VALUES ('17', '13', 'http://www.chatwork.com', '118436382', 'vie', '1462960045', '1462958955', 'd03d29df88f74e557aa48638d4bd31316e9dd91eaaf875670b8918c11688608c', '1462960045');
INSERT INTO `sites` VALUES ('18', '13', 'http://www.chatwork.com', '118436382', 'jpn', '1462960589', '1462960061', '421bc1da4441cdd6de9049f2e43b53917276aefaba7e652e6e6368bb0462e638', null);
INSERT INTO `sites` VALUES ('19', '14', 'http://gakkoukyouzai.com', '533824', 'jpn', '1462962246', '1462960373', '16b1dc2ec74bd5fbd6881cbbae35c2ebdf4eff6ed3ca7ab55dc08333cbc73801', null);
INSERT INTO `sites` VALUES ('20', '14', null, '', 'jpn', '1462960485', '1462960373', '9201e17f6ba67b39b192edece5992a37df4eff6ed3ca7ab55dc08333cbc73801', '1462960485');
INSERT INTO `sites` VALUES ('21', '15', 'http://cleverads.vn', '36117585', 'vie', '1463018815', '1463018815', '695a4cd2847bb68e5d7aa3679c236d637a3154290a448dd6a8bab226699d00a0', null);
INSERT INTO `sites` VALUES ('22', '16', 'http://www.chatwork.com', '', 'jpn', '1463123213', '1463123213', 'e06c9abc54703ce47dbf2eb40538a46da71b73fe87301bfb9b28840de341e1e1', null);
INSERT INTO `sites` VALUES ('23', '17', 'http://www.weblio.jp', '117260011', 'jpn', '1463123458', '1463123359', '6b7fb01c227df3e30334f388c537072ec027cb6fcd1e455425e146a34c231264', null);
INSERT INTO `sites` VALUES ('24', '18', 'http://www.ranrantour.jp/', '56432734', 'jpn', '1463387789', '1463387789', 'ff9e7bf01031b5c2e519b08f3b92effdd2199d788b82e8ae14ea614bf701ba28', null);
INSERT INTO `sites` VALUES ('25', '18', null, '', 'jpn', '1463387830', '1463387789', '313ce6895400b2567ee1dcff64008207d2199d788b82e8ae14ea614bf701ba28', '1463387830');
INSERT INTO `sites` VALUES ('26', '19', 'http://huvrid.co.jp/blog/', '112897916', 'jpn', '1463391578', '1463391435', '90b47c8fef6842ec17762533e6b6d1db188edec9e940976407aa1035c4445205', null);
INSERT INTO `sites` VALUES ('27', '20', 'http://huvrid.co.jp/', '68681269', 'jpn', '1463451255', '1463451255', '8abcab7ce4ce063ebfee1cfceb63ae58b92b22422fd16f78a789bff2290537ca', null);
INSERT INTO `sites` VALUES ('28', '21', 'http://huvrid.co.jp/blog/', '112897916', 'jpn', '1463537858', '1463537760', '2a0c185901fbab21e4b3bb1d1cb68a953de613ef1c602c11f962f9715a06c0ee', null);
INSERT INTO `sites` VALUES ('29', '11', null, '', 'jpn', '1463991903', '1463991903', 'b3e457aa6906cc267f284a4b8e133b71b482e182b2b7c6d072f10cf81c7eca9c', null);
INSERT INTO `sites` VALUES ('30', '22', 'http://huvrid.co.jp/blog', '112897916', 'jpn', '1466739816', '1466739762', '5ba0442887ba7aa3dfc19e6a3799d4cbe664f04933be73b93ca60a555a9d4dcf', null);
INSERT INTO `sites` VALUES ('31', '23', 'http://huvrid.co.jp', '68681269', 'jpn', '1466740198', '1466739857', 'a49984deb05bd0de49c2df8fcdcdc533a979563050bf91655f59798d53596739', null);
INSERT INTO `sites` VALUES ('32', '24', 'http://hot-okinawa.co.jp', '122220763', 'jpn', '1466740045', '1466740045', '183e1d4f3415d1447497ed13a6d79c36771b79c763856dd701cec2aa182c102b', null);
INSERT INTO `sites` VALUES ('33', '24', null, '', 'jpn', '1466740287', '1466740045', '633802caf831260978d6905686459d03771b79c763856dd701cec2aa182c102b', '1466740287');
INSERT INTO `sites` VALUES ('34', '18', 'http://www.hokkaidotours.co.jp', '', 'jpn', '1466750234', '1466750234', '89f683dd33caeb316f9df5f7af0f0f00abcffdadad51fbb0e3f909e262a0ffcc', null);
INSERT INTO `sites` VALUES ('35', '18', 'http://www.otstour.jp', '', 'jpn', '1466750234', '1466750234', '32bc2c696e27a27736ee28832fd60aaaabcffdadad51fbb0e3f909e262a0ffcc', null);
INSERT INTO `sites` VALUES ('36', '25', 'http://www.big1-ds.co.jp/', '122312928', 'jpn', '1468468060', '1468468060', 'd81e95ef79280e2de8303616c22d020528fa81cad994ec3be0563e12be1acf37', null);
INSERT INTO `sites` VALUES ('37', '25', null, '', 'jpn', '1468468060', '1468468060', '6eee3a4c7b5992cbe5f4ca7d6d2dc7e628fa81cad994ec3be0563e12be1acf37', null);
INSERT INTO `sites` VALUES ('38', '26', 'http://www.n-cis.ac.jp/index.jsp', '90104566', 'jpn', '1468573350', '1468573350', 'b4ba36575a7ee293177a46e03fe8fda54139bcc0e144cc65d6838c1a23d46bbf', null);
INSERT INTO `sites` VALUES ('39', '27', 'http://www.okiden.co.jp/index.html', '100171523', 'jpn', '1469524671', '1469524671', '293ca6a7e5a622df1c828b1fb0d382a9f37fdc636b111e652622e1145cc812cd', null);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'encrypted',
  `group_id` int(11) unsigned NOT NULL,
  `modified` int(8) NOT NULL,
  `created` int(8) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `companyname` varchar(255) NOT NULL,
  `realname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `service` varchar(255) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  `lang` varchar(20) NOT NULL DEFAULT 'jpn',
  `deleted` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'admin', '$2a$10$xWF9RcRkoJMCkFb6G/x70.mQRMx8Zig3epId2zrRszqlgn8s/Y4Ha', '1', '1481530421', '1456717572', '0', null, 'WevNAL', 'テスト実施', 'asd@com.xeg', 'GA', '97b4f30cd5dcd44ea1de45a4ce538e28', 'vie', null);
INSERT INTO `users` VALUES ('2', 'Linh', '$2a$10$n0L7TsF2LS6Pd4KttGGnP.LBfKyzAz7Bdd6pI3qaqVdLMpjeXHWFi', '6', '1462501837', '1462501736', '1', null, 'WevNAL', 'Linh', 'WevNAL@gmail.com', 'Listing', '', 'jpn', null);
INSERT INTO `users` VALUES ('3', 'ict@otsinfo.co.jp', '$2a$10$vZDqyM4rQ3hZlwP8Bk4YPOZITSB0nv1USLUcirElxFAm1YxlsYSXu', '7', '1463387517', '1462505301', '1', null, '沖縄ツーリスト', '沖縄ツーリスト', 'ict@otsinfo.co.jp', 'リスティング', '', 'jpn', '1463387517');
INSERT INTO `users` VALUES ('4', 'fa-j@a-manage.jp', '$2a$10$p8ytjpxTge7yImS09W57ougyA7pV6fKWS380MZNcDeu4z8FKmdIPq', '7', '1462507392', '1462505369', '1', null, 'FAJ', 'FAJ', 'fa-j@a-manage.jp', 'リスティング', '', 'jpn', null);
INSERT INTO `users` VALUES ('5', 'fajfactory@gmail.com', '$2a$10$L/O4I5L6t0EmAfxkHW2RsuNQy6TUwWJiT6RPeU.awJu2Z0F0YYNRW', '7', '1462507418', '1462505435', '1', null, 'Fajob', 'Fajob', 'fajfactory@gmail.com', 'リスティング', '', 'jpn', null);
INSERT INTO `users` VALUES ('6', 'info@yokoi-package.co.jp', '$2a$10$ShsYX2oph5bWbuanNvdkbOLCMFXMTyw/Prrj7iq2.SprRZOYJfjuK', '7', '1462507462', '1462505487', '1', null, '横井パッケージ', '横井パッケージ', 'info@yokoi-package.co.jp', 'リスティング', '', 'jpn', null);
INSERT INTO `users` VALUES ('7', 'kiso@blocco-deli.co.jp', '$2a$10$GzOxqz6ijJ7DNHePB1Wrs.fGw6slPXiequDkX0IjFb3yMzUgVkThi', '7', '1462960288', '1462960288', '1', null, 'blocco deli architects Inc.', 'Takashi Kiso', 'kiso@blocco-deli.co.jp', 'テスト', '', 'jpn', null);
INSERT INTO `users` VALUES ('8', 'eigyou1', '$2a$10$Rp2gxXsXVlEs6IFOOG02H.SRCPxvra4cP0QBw8.IwQiuPkTuQyVgu', '2', '1463538071', '1463386350', '1', null, 'WevNAL', 'eigyou1', 'vu.thi.my.linh@wevnal.co.jp', 'Listing', '', 'jpn', '1463538071');
INSERT INTO `users` VALUES ('9', 'eigyou2', '$2a$10$SjrVFl0adndWSqSYORfSMuQ14KYbGY0mO9yLvatIu9TK2kpIRMSDu', '3', '1463538077', '1463386404', '1', null, 'WevNAL', 'eigyou2', 'vu.thi.my.linh@miyatsu.vn', 'リスティング', '', 'jpn', '1463538077');
INSERT INTO `users` VALUES ('10', 'unyou1', '$2a$10$3nXFVwuepsJFJZV0qCeVjOhhBYFI1bCWQff4iquMWUbY1SCutexQm', '4', '1463538083', '1463386514', '1', null, 'WevNAL', 'unyou1', 'le.thanh.hai@wevnal.co.jp', 'リスティング', '', 'jpn', '1463538083');
INSERT INTO `users` VALUES ('11', 'unyou2', '$2a$10$xeOuh7s8vnu5/IBdkSSBhurzvYbsA0p92SlNMWEziJEkBSiloxewK', '5', '1463538088', '1463386561', '1', null, 'WevNAL', 'unyou1', 'le.thanh.hai@miyatsu.vn', 'リスティング', '', 'jpn', '1463538088');
INSERT INTO `users` VALUES ('12', '前田テスト', '$2a$10$xYmEB7g40kqK8wKRB2MbrucleNrQ0I5wWdRU.6ZeS.9lLblsVOdSC', '4', '1463453218', '1463448525', '1', null, '株式会社WevNAL', '前田　康統', 'maeda2266@gmail.com', 'DSP', '', 'jpn', '1463453218');
INSERT INTO `users` VALUES ('13', 'chinatsu', '$2a$10$.VxpzSYore/G/0nmqZGn4esGoRdb2UjzdSqYmc5DUdGKBRnM3XEpu', '4', '1463453213', '1463452516', '12', null, 'huvrid', '伊波知夏', 'c-higa@huvrid.co.jp', 'twitter', '', 'jpn', '1463453213');
INSERT INTO `users` VALUES ('14', 'chinatsu01', '$2a$10$JDmJhPKg404P2.sJwHtqeeFbCpLPq6Gq4DZbrnV6D0GjF5OHjyHrO', '3', '1463453209', '1463452946', '13', null, 'huvrid', '比嘉知夏', 'leon-.-ch@i.softbank.jp', 'Twitter', '', 'jpn', '1463453209');
INSERT INTO `users` VALUES ('15', 'reomori', '$2a$10$9cRH/8ydm76db46mIcrqnOxG5UeXVb.enQKzGhY8e78KnaJc4rCGS', '2', '1467018181', '1463537091', '1', null, '株式会社WevNAL', '森元　昭博', 'a-morimoto@wevnal.co.jp', 'リスティング', '', 'jpn', null);
INSERT INTO `users` VALUES ('16', 'shintaro12', '$2a$10$F.g0nkRYak3wv6UY4Bq3mOEggJV65RAaKmXI7cWEKKFwrKBPhjQtG', '4', '1463537207', '1463537207', '1', null, '株式会社WevNAL', '荒井伸太郎', 's-arai@wevnal.co.jp', 'リスティング広告', '', 'jpn', null);
INSERT INTO `users` VALUES ('17', 'kashima', '$2a$10$Ngc9oERtSOtkjXyc46IHluNfGjCPxScENMrPRmB1gfr6uT3vQZvzS', '4', '1463537276', '1463537276', '15', null, '株式会社ＷｅｖＮＡＬ　', '加嶋　優', 's-kashima@wevnal.co.jp', 'リスティング広告', '', 'jpn', null);
INSERT INTO `users` VALUES ('18', 'testeigyo', '$2a$10$ZgoNiRBT7cY/EwsVASI/.Oryh7038qZgebZesEs/KX0zrn1Y.fWOO', '3', '1463537664', '1463537276', '1', null, '株式会社WevNAL', '前田テスト', 'y-maeda@wevnal.co.jp', 'DSP', '', 'jpn', '1463537664');
INSERT INTO `users` VALUES ('19', 'LALA', '$2a$10$Zj8lRqhLDztkNfFOX4gxFevgsCHXS7osahcuh5WaJ2RUT72ojUUnW', '2', '1463539504', '1463539492', '1', null, '1111', '1111', '1111@gmail.com', 'Listing', '', 'jpn', '1463539504');
INSERT INTO `users` VALUES ('20', 'test_delete', '$2a$10$hvMIpmBw3EmliEhIqYYm..h9I1QhMoCRvwuylJ/pH5yZ0Yx4rH0e6', '1', '1463557303', '1463557294', '1', null, '1111', '1111', 'abc@gmail.com', 'Listing', '', 'jpn', '1463557303');
INSERT INTO `users` VALUES ('21', 'test_delete', '$2a$10$CkOKO0BBhj6T0SdqYInU..COfEchEgRUBpfjPt8y5j/17tPsoTJ/6', '1', '1463557369', '1463557360', '1', null, '1111', '1111', 'abc@gmail.com', 'Listing', '', 'jpn', '1463557369');
INSERT INTO `users` VALUES ('22', 'test_delete', '$2a$10$M8ZIWXQLZK/63.P3iVkkXuQsXwkAvhIv6m8VKWebbHTCsE70SD1d.', '1', '1463557440', '1463557426', '1', null, '1111', '1111', 'abc@gmail.com', 'Listing', '', 'jpn', '1463557440');
INSERT INTO `users` VALUES ('23', 'hailt', '$2a$10$xWF9RcRkoJMCkFb6G/x70.mQRMx8Zig3epId2zrRszqlgn8s/Y4Ha', '1', '0', '0', '0', null, 'WevNAL', 'Hai', 'le.thanh.hai@wevnal.co.jp', 'Listing', '', 'jpn', null);
INSERT INTO `users` VALUES ('24', 'HUVRID', '$2a$10$vojouxKq.0sMdaxrBjsYP.KrIYmsBwQ3AQG.uDHMsQ./KYpOjwf2G', '6', '1466739537', '1466739537', '1', null, 'HUVRID', 'HUVRID', 'k-akamine@huvrid.co.jp', 'リスティング', '', 'jpn', null);
INSERT INTO `users` VALUES ('25', 'HaiLV', '$2a$10$N60X4wkVxIDiAq.FfheLmezh0qBBS/PWaZ7f8tKeTUbVk6f2Rr8tu', '1', '1476322459', '1476322282', '1', null, 'qs', 'qe', 'le.van.hai@miyatsu.vn', 'df', '', 'vie', null);
