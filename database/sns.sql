/*
Navicat MySQL Data Transfer

Source Server         : homstead
Source Server Version : 50712
Source Host           : localhost:33060
Source Database       : sns

Target Server Type    : MYSQL
Target Server Version : 50712
File Encoding         : 65001

Date: 2016-11-18 17:41:47
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `accounts`
-- ----------------------------
DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `rival_flg` int(2) DEFAULT '0',
  `service_code` varchar(3) DEFAULT NULL,
  `screen_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `description` text CHARACTER SET utf8,
  `created_time` datetime DEFAULT NULL,
  `avatar_url` varchar(1000) DEFAULT NULL,
  `followers_count` text,
  `friends_count` text,
  `listed_count` text,
  `favourites_count` text,
  `statuses_count` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of accounts
-- ----------------------------

-- ----------------------------
-- Table structure for `auths`
-- ----------------------------
DROP TABLE IF EXISTS `auths`;
CREATE TABLE `auths` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `account_name` varchar(255) NOT NULL,
  `account_id` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `access_token` varchar(255) NOT NULL DEFAULT '1',
  `refresh_token` varchar(255) DEFAULT '4',
  `service_code` varchar(3) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rival_flg` int(2) DEFAULT '0' COMMENT '0-me;1-rival',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auths
-- ----------------------------
INSERT INTO `auths` VALUES ('1', '13', '', 'lehai_1991', '4033827353', null, '4033827353.f159adb.510f9108fc914af792198b0366173986', '4', '003', null, '2016-11-18 14:21:24', '2016-11-18 14:21:24', '0');
INSERT INTO `auths` VALUES ('2', '13', 'haile04.1991.3@gmail.com', '3Haile04', '3197456179', null, '3197456179-DZX1dVDCJHQFvRASYuH4pQsZQp2jAS6L2EAvHlt', 'eFkVWDyAFAUYCR6cpgQNEzRUUExg4YC9mXdeok5aSDDdS', '002', null, '2016-11-18 14:21:47', '2016-11-18 14:21:47', '0');
INSERT INTO `auths` VALUES ('3', '13', null, 'Donald J. Trump', '25073877', null, '', '4', '002', null, '2016-11-18 14:22:08', '2016-11-18 17:40:36', '1');
INSERT INTO `auths` VALUES ('4', '13', null, 'cocorocha', '59624844', null, '', '4', '002', null, '2016-11-18 15:31:14', '2016-11-18 17:40:37', '1');
INSERT INTO `auths` VALUES ('5', '13', '', 'le.van.hai', '4048145333', null, '4048145333.f159adb.c97fc76f7bc74b3c925a17a229785db5', '4', '003', null, '2016-11-18 15:38:59', '2016-11-18 15:38:59', '0');

-- ----------------------------
-- Table structure for `masters`
-- ----------------------------
DROP TABLE IF EXISTS `masters`;
CREATE TABLE `masters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` varchar(255) NOT NULL,
  `code` char(10) NOT NULL,
  `name_vn` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `name_ja` varchar(255) NOT NULL,
  `active_flg` tinyint(2) NOT NULL DEFAULT '1',
  `attr1` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of masters
-- ----------------------------
INSERT INTO `masters` VALUES ('1', 'contract_status', '001', 'Kiểm tra', 'Test', 'Test', '1', null, null, null, '2016-11-09 09:52:03', null);
INSERT INTO `masters` VALUES ('2', 'contract_status', '002', 'Đang thực hiện', 'Processing', 'Processing', '1', null, null, null, '2016-11-09 09:52:03', null);
INSERT INTO `masters` VALUES ('3', 'contract_status', '003', 'Hết hạn', 'Expired', 'Expired', '1', null, null, null, '2016-11-09 09:52:03', null);
INSERT INTO `masters` VALUES ('4', 'contract_status', '004', 'Đã dừng', 'Stop', 'Stop', '1', null, null, null, '2016-11-09 09:52:03', null);
INSERT INTO `masters` VALUES ('5', 'services', '001', 'Facebook', 'Facebook', 'Facebook', '1', '1', null, null, '2016-11-18 03:31:15', null);
INSERT INTO `masters` VALUES ('6', 'services', '002', 'Twitter', 'Twitter', 'Twitter', '1', '1', null, null, '2016-11-18 03:31:16', null);
INSERT INTO `masters` VALUES ('7', 'services', '003', 'Instagram', 'Instagram', 'Instagram', '1', '1', null, null, '2016-11-18 03:31:16', null);
INSERT INTO `masters` VALUES ('8', 'services', '004', 'Snapchat', 'Snapchat', 'Snapchat', '1', '1', null, null, '2016-11-18 03:31:24', null);

-- ----------------------------
-- Table structure for `migrations`
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('2014_10_12_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('2014_10_12_100000_create_password_resets_table', '1');

-- ----------------------------
-- Table structure for `page_details`
-- ----------------------------
DROP TABLE IF EXISTS `page_details`;
CREATE TABLE `page_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) DEFAULT NULL,
  `friends_count` text,
  `posts_count` text,
  `followers_count` text,
  `favourites_count` text,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of page_details
-- ----------------------------
INSERT INTO `page_details` VALUES ('1', '1', '3', '44', '5', '0', '2016-11-18', '2016-11-18 14:21:25', '2016-11-18 14:21:25');
INSERT INTO `page_details` VALUES ('2', '2', '5', '13', '5', '4', '2016-11-18', '2016-11-18 14:21:49', '2016-11-18 14:21:49');
INSERT INTO `page_details` VALUES ('3', '3', '41', '33999', '15381319', '45', '2016-11-18', '2016-11-18 14:22:09', '2016-11-18 14:22:09');
INSERT INTO `page_details` VALUES ('4', '4', '763', '9188', '1550276', '3594', '2016-11-18', '2016-11-18 15:31:16', '2016-11-18 15:31:16');
INSERT INTO `page_details` VALUES ('5', '5', '5', '0', '0', '0', '2016-11-18', '2016-11-18 15:39:00', '2016-11-18 15:39:00');

-- ----------------------------
-- Table structure for `pages`
-- ----------------------------
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auth_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `screen_name` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `banner_url` varchar(1000) DEFAULT NULL,
  `avatar_url` varchar(1000) DEFAULT NULL,
  `sns_page_id` varchar(255) DEFAULT NULL,
  `access_token` varchar(255) DEFAULT '1',
  `description` text,
  `created_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pages
-- ----------------------------
INSERT INTO `pages` VALUES ('1', '1', 'lehai_1991', 'Hai Le', '', null, 'https://www.instagram.com/lehai_1991', null, 'https://igcdn-photos-a-a.akamaihd.net/hphotos-ak-xfa1/t51.2885-19/s150x150/14596692_224605911284448_8437411301067587584_a.jpg', '4033827353', '4033827353.f159adb.510f9108fc914af792198b0366173986', 'bio', null, '2016-11-18 14:21:25', '2016-11-18 14:21:25');
INSERT INTO `pages` VALUES ('2', '2', 'hai le', '3Haile04', '', '', '', null, 'http://abs.twimg.com/sticky/default_profile_images/default_profile_1_normal.png', '3197456179', '3197456179-DZX1dVDCJHQFvRASYuH4pQsZQp2jAS6L2EAvHlt', '', '2015-05-16 19:46:49', '2016-11-18 14:21:49', '2016-11-18 14:21:49');
INSERT INTO `pages` VALUES ('3', '3', 'Donald J. Trump', 'realDonaldTrump', '', 'New York, NY', '', 'https://pbs.twimg.com/profile_banners/25073877/1479267159', 'http://pbs.twimg.com/profile_images/1980294624/DJT_Headshot_V2_normal.jpg', '25073877', '', 'President-elect of the United States', '2016-11-18 07:22:55', '2016-11-18 14:22:09', '2016-11-18 07:22:55');
INSERT INTO `pages` VALUES ('4', '4', 'Coco  Rocha', 'cocorocha', '', 'New York, NY', '', 'https://pbs.twimg.com/profile_banners/59624844/1474918322', 'http://pbs.twimg.com/profile_images/780490122882125824/nRQwuQuf_normal.jpg', '59624844', '1', 'Model, Owner & Director at @NOMADMGMT. Founder & Designer at @COCObyCOCOROCHA. Represented by WME | NOMAD worldwide. coco.rocha.connect@gmail.com.', '2009-07-24 06:32:23', '2016-11-18 15:31:16', '2016-11-18 15:31:16');
INSERT INTO `pages` VALUES ('5', '5', 'le.van.hai', 'le van hai', '', null, 'https://www.instagram.com/le.van.hai', null, 'https://igcdn-photos-a-a.akamaihd.net/hphotos-ak-xfa1/t51.2885-19/s150x150/14716538_1774945532774592_8915309735113129984_a.jpg', '4048145333', '4048145333.f159adb.c97fc76f7bc74b3c925a17a229785db5', '', null, '2016-11-18 15:39:00', '2016-11-18 15:39:00');

-- ----------------------------
-- Table structure for `password_resets`
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for `post_facebook_details`
-- ----------------------------
DROP TABLE IF EXISTS `post_facebook_details`;
CREATE TABLE `post_facebook_details` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` varchar(55) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `like_count` int(255) DEFAULT NULL,
  `comment_count` int(255) DEFAULT NULL,
  `share_count` int(15) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of post_facebook_details
-- ----------------------------

-- ----------------------------
-- Table structure for `post_facebooks`
-- ----------------------------
DROP TABLE IF EXISTS `post_facebooks`;
CREATE TABLE `post_facebooks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` varchar(50) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `image_thumbnail` varchar(255) DEFAULT NULL,
  `link` varchar(250) DEFAULT NULL,
  `sns_post_id` varchar(52) DEFAULT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of post_facebooks
-- ----------------------------

-- ----------------------------
-- Table structure for `post_instagram_details`
-- ----------------------------
DROP TABLE IF EXISTS `post_instagram_details`;
CREATE TABLE `post_instagram_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_count` int(11) DEFAULT NULL,
  `like_count` int(11) DEFAULT NULL,
  `post_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of post_instagram_details
-- ----------------------------
INSERT INTO `post_instagram_details` VALUES ('1', '0', '0', '1', '2016-11-18', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagram_details` VALUES ('2', '0', '0', '2', '2016-11-18', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagram_details` VALUES ('3', '0', '0', '3', '2016-11-18', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagram_details` VALUES ('4', '0', '0', '4', '2016-11-18', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagram_details` VALUES ('5', '0', '0', '5', '2016-11-18', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagram_details` VALUES ('6', '0', '0', '6', '2016-11-18', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagram_details` VALUES ('7', '0', '0', '7', '2016-11-18', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagram_details` VALUES ('8', '0', '0', '8', '2016-11-18', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagram_details` VALUES ('9', '0', '0', '9', '2016-11-18', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagram_details` VALUES ('10', '0', '0', '10', '2016-11-18', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagram_details` VALUES ('11', '0', '0', '11', '2016-11-18', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagram_details` VALUES ('12', '0', '0', '12', '2016-11-18', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagram_details` VALUES ('13', '0', '0', '13', '2016-11-18', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagram_details` VALUES ('14', '0', '0', '14', '2016-11-18', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagram_details` VALUES ('15', '0', '0', '15', '2016-11-18', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagram_details` VALUES ('16', '0', '0', '16', '2016-11-18', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagram_details` VALUES ('17', '0', '0', '17', '2016-11-18', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagram_details` VALUES ('18', '0', '0', '18', '2016-11-18', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagram_details` VALUES ('19', '0', '0', '19', '2016-11-18', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagram_details` VALUES ('20', '0', '0', '20', '2016-11-18', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);

-- ----------------------------
-- Table structure for `post_instagrams`
-- ----------------------------
DROP TABLE IF EXISTS `post_instagrams`;
CREATE TABLE `post_instagrams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) DEFAULT NULL,
  `sns_post_id` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `content` text,
  `type` varchar(255) DEFAULT NULL,
  `image_low_resolution` varchar(255) DEFAULT NULL,
  `image_thumbnail` varchar(255) DEFAULT NULL,
  `image_standard_resolution` varchar(255) DEFAULT NULL,
  `video_low_resolution` varchar(255) DEFAULT NULL,
  `video_standard_resolution` varchar(255) DEFAULT NULL,
  `video_low_bandwidth` varchar(255) DEFAULT NULL,
  `location_id` varchar(255) DEFAULT NULL,
  `location_name` varchar(255) DEFAULT NULL,
  `location_lat` varchar(255) DEFAULT NULL,
  `location_long` varchar(255) DEFAULT NULL,
  `tag` varchar(255) DEFAULT NULL,
  `created_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of post_instagrams
-- ----------------------------
INSERT INTO `post_instagrams` VALUES ('1', '1', '1380581970467373711_4033827353', 'https://www.instagram.com/p/BMoz_zNBEKP/', '041', 'image', 'https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/14733316_940457316099179_2951535851189829632_n.jpg?ig_cache_key=MTM4MDU4MTk3MDQ2NzM3MzcxMQ%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/14733316_940457316099179_2951535851189829632_n.jpg?ig_cache_key=MTM4MDU4MTk3MDQ2NzM3MzcxMQ%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/14733316_940457316099179_2951535851189829632_n.jpg?ig_cache_key=MTM4MDU4MTk3MDQ2NzM3MzcxMQ%3D%3D.2', '', '', '', '', '', '', '', '', '2016-11-11 00:16:57', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagrams` VALUES ('2', '1', '1380581806352735838_4033827353', 'https://www.instagram.com/p/BMoz9aXBZ5e/', '040', 'image', 'https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/15046881_336103186750660_5330341343921176576_n.jpg?ig_cache_key=MTM4MDU4MTgwNjM1MjczNTgzOA%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/15046881_336103186750660_5330341343921176576_n.jpg?ig_cache_key=MTM4MDU4MTgwNjM1MjczNTgzOA%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/15046881_336103186750660_5330341343921176576_n.jpg?ig_cache_key=MTM4MDU4MTgwNjM1MjczNTgzOA%3D%3D.2', '', '', '', '', '', '', '', '', '2016-11-11 00:16:38', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagrams` VALUES ('3', '1', '1380581672277687677_4033827353', 'https://www.instagram.com/p/BMoz7dfhr19/', '039', 'image', 'https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/15043618_1815929848644753_1152644058309459968_n.jpg?ig_cache_key=MTM4MDU4MTY3MjI3NzY4NzY3Nw%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/15043618_1815929848644753_1152644058309459968_n.jpg?ig_cache_key=MTM4MDU4MTY3MjI3NzY4NzY3Nw%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/15043618_1815929848644753_1152644058309459968_n.jpg?ig_cache_key=MTM4MDU4MTY3MjI3NzY4NzY3Nw%3D%3D.2', '', '', '', '', '', '', '', '', '2016-11-11 00:16:22', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagrams` VALUES ('4', '1', '1380581544854641537_4033827353', 'https://www.instagram.com/p/BMoz5m0hVuB/', '038', 'image', 'https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/14624692_154182695052332_2435998518490431488_n.jpg?ig_cache_key=MTM4MDU4MTU0NDg1NDY0MTUzNw%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/14624692_154182695052332_2435998518490431488_n.jpg?ig_cache_key=MTM4MDU4MTU0NDg1NDY0MTUzNw%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/14624692_154182695052332_2435998518490431488_n.jpg?ig_cache_key=MTM4MDU4MTU0NDg1NDY0MTUzNw%3D%3D.2', '', '', '', '', '', '', '', '', '2016-11-11 00:16:06', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagrams` VALUES ('5', '1', '1380581430643843940_4033827353', 'https://www.instagram.com/p/BMoz38dBuNk/', '037', 'image', 'https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/14733317_1218722688150697_328291142439272448_n.jpg?ig_cache_key=MTM4MDU4MTQzMDY0Mzg0Mzk0MA%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/14733317_1218722688150697_328291142439272448_n.jpg?ig_cache_key=MTM4MDU4MTQzMDY0Mzg0Mzk0MA%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/14733317_1218722688150697_328291142439272448_n.jpg?ig_cache_key=MTM4MDU4MTQzMDY0Mzg0Mzk0MA%3D%3D.2', '', '', '', '', '', '', '', '', '2016-11-11 00:15:53', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagrams` VALUES ('6', '1', '1380581314159505986_4033827353', 'https://www.instagram.com/p/BMoz2P-BPJC/', '036', 'image', 'https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/14709450_424032341054106_5056791462339936256_n.jpg?ig_cache_key=MTM4MDU4MTMxNDE1OTUwNTk4Ng%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/14709450_424032341054106_5056791462339936256_n.jpg?ig_cache_key=MTM4MDU4MTMxNDE1OTUwNTk4Ng%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/14709450_424032341054106_5056791462339936256_n.jpg?ig_cache_key=MTM4MDU4MTMxNDE1OTUwNTk4Ng%3D%3D.2', '', '', '', '', '', '', '', '', '2016-11-11 00:15:39', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagrams` VALUES ('7', '1', '1380581192558250539_4033827353', 'https://www.instagram.com/p/BMoz0euBQor/', '035', 'image', 'https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/15048157_1812884728987435_6946478455835852800_n.jpg?ig_cache_key=MTM4MDU4MTE5MjU1ODI1MDUzOQ%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/15048157_1812884728987435_6946478455835852800_n.jpg?ig_cache_key=MTM4MDU4MTE5MjU1ODI1MDUzOQ%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/15048157_1812884728987435_6946478455835852800_n.jpg?ig_cache_key=MTM4MDU4MTE5MjU1ODI1MDUzOQ%3D%3D.2', '', '', '', '', '', '', '', '', '2016-11-11 00:15:24', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagrams` VALUES ('8', '1', '1380581075277280562_4033827353', 'https://www.instagram.com/p/BMozyxfh3Uy/', '034', 'image', 'https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/15034963_1665226993767229_1309442774436675584_n.jpg?ig_cache_key=MTM4MDU4MTA3NTI3NzI4MDU2Mg%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/15034963_1665226993767229_1309442774436675584_n.jpg?ig_cache_key=MTM4MDU4MTA3NTI3NzI4MDU2Mg%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/15034963_1665226993767229_1309442774436675584_n.jpg?ig_cache_key=MTM4MDU4MTA3NTI3NzI4MDU2Mg%3D%3D.2', '', '', '', '', '', '', '', '', '2016-11-11 00:15:10', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagrams` VALUES ('9', '1', '1380580947635996293_4033827353', 'https://www.instagram.com/p/BMozw6nhAaF/', '033', 'image', 'https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/14574067_1803458666598567_1721450453041938432_n.jpg?ig_cache_key=MTM4MDU4MDk0NzYzNTk5NjI5Mw%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/14574067_1803458666598567_1721450453041938432_n.jpg?ig_cache_key=MTM4MDU4MDk0NzYzNTk5NjI5Mw%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/14574067_1803458666598567_1721450453041938432_n.jpg?ig_cache_key=MTM4MDU4MDk0NzYzNTk5NjI5Mw%3D%3D.2', '', '', '', '', '', '', '', '', '2016-11-11 00:14:55', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagrams` VALUES ('10', '1', '1380580726940151666_4033827353', 'https://www.instagram.com/p/BMozttFBK9y/', '031', 'image', 'https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/15035024_216238955470889_7475742027271897088_n.jpg?ig_cache_key=MTM4MDU4MDcyNjk0MDE1MTY2Ng%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/15035024_216238955470889_7475742027271897088_n.jpg?ig_cache_key=MTM4MDU4MDcyNjk0MDE1MTY2Ng%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/15035024_216238955470889_7475742027271897088_n.jpg?ig_cache_key=MTM4MDU4MDcyNjk0MDE1MTY2Ng%3D%3D.2', '', '', '', '', '', '', '', '', '2016-11-11 00:14:29', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagrams` VALUES ('11', '1', '1380580545461215547_4033827353', 'https://www.instagram.com/p/BMozrEEB-E7/', '032', 'image', 'https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/15057402_162531714211581_4833901231116124160_n.jpg?ig_cache_key=MTM4MDU4MDU0NTQ2MTIxNTU0Nw%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/15057402_162531714211581_4833901231116124160_n.jpg?ig_cache_key=MTM4MDU4MDU0NTQ2MTIxNTU0Nw%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/15057402_162531714211581_4833901231116124160_n.jpg?ig_cache_key=MTM4MDU4MDU0NTQ2MTIxNTU0Nw%3D%3D.2', '', '', '', '', '', '', '', '', '2016-11-11 00:14:07', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagrams` VALUES ('12', '1', '1380580521016653149_4033827353', 'https://www.instagram.com/p/BMozqtTBXVd/', '030', 'image', 'https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/14540630_1529873860361290_8558475472680255488_n.jpg?ig_cache_key=MTM4MDU4MDUyMTAxNjY1MzE0OQ%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/14540630_1529873860361290_8558475472680255488_n.jpg?ig_cache_key=MTM4MDU4MDUyMTAxNjY1MzE0OQ%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/14540630_1529873860361290_8558475472680255488_n.jpg?ig_cache_key=MTM4MDU4MDUyMTAxNjY1MzE0OQ%3D%3D.2', '', '', '', '', '', '', '', '', '2016-11-11 00:14:04', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagrams` VALUES ('13', '1', '1380580090588912238_4033827353', 'https://www.instagram.com/p/BMozkcbh1pu/', '028', 'image', 'https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/15057378_1846933722209533_6820102917211553792_n.jpg?ig_cache_key=MTM4MDU4MDA5MDU4ODkxMjIzOA%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/15057378_1846933722209533_6820102917211553792_n.jpg?ig_cache_key=MTM4MDU4MDA5MDU4ODkxMjIzOA%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/15057378_1846933722209533_6820102917211553792_n.jpg?ig_cache_key=MTM4MDU4MDA5MDU4ODkxMjIzOA%3D%3D.2', '', '', '', '', '', '', '', '', '2016-11-11 00:13:13', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagrams` VALUES ('14', '1', '1380579931524142744_4033827353', 'https://www.instagram.com/p/BMoziISh5aY/', '026', 'image', 'https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/15048078_620834001375260_482167867760967680_n.jpg?ig_cache_key=MTM4MDU3OTkzMTUyNDE0Mjc0NA%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/15048078_620834001375260_482167867760967680_n.jpg?ig_cache_key=MTM4MDU3OTkzMTUyNDE0Mjc0NA%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/15048078_620834001375260_482167867760967680_n.jpg?ig_cache_key=MTM4MDU3OTkzMTUyNDE0Mjc0NA%3D%3D.2', '', '', '', '', '', '', '', '', '2016-11-11 00:12:54', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagrams` VALUES ('15', '1', '1380579073394565578_4033827353', 'https://www.instagram.com/p/BMozVpGBWHK/', '027', 'image', 'https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/14566684_1866396610257344_5690336534780182528_n.jpg?ig_cache_key=MTM4MDU3OTA3MzM5NDU2NTU3OA%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/14566684_1866396610257344_5690336534780182528_n.jpg?ig_cache_key=MTM4MDU3OTA3MzM5NDU2NTU3OA%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/14566684_1866396610257344_5690336534780182528_n.jpg?ig_cache_key=MTM4MDU3OTA3MzM5NDU2NTU3OA%3D%3D.2', '', '', '', '', '', '', '', '', '2016-11-11 00:11:12', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagrams` VALUES ('16', '1', '1380579048891467549_4033827353', 'https://www.instagram.com/p/BMozVSRhccd/', '025', 'image', 'https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/15057225_698591330304728_9085264185176096768_n.jpg?ig_cache_key=MTM4MDU3OTA0ODg5MTQ2NzU0OQ%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/15057225_698591330304728_9085264185176096768_n.jpg?ig_cache_key=MTM4MDU3OTA0ODg5MTQ2NzU0OQ%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/15057225_698591330304728_9085264185176096768_n.jpg?ig_cache_key=MTM4MDU3OTA0ODg5MTQ2NzU0OQ%3D%3D.2', '', '', '', '', '', '', '', '', '2016-11-11 00:11:09', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagrams` VALUES ('17', '1', '1380578847489315944_4033827353', 'https://www.instagram.com/p/BMozSWtBNRo/', '024', 'image', 'https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/15057376_1131199900266747_6658048849036181504_n.jpg?ig_cache_key=MTM4MDU3ODg0NzQ4OTMxNTk0NA%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/15057376_1131199900266747_6658048849036181504_n.jpg?ig_cache_key=MTM4MDU3ODg0NzQ4OTMxNTk0NA%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/15057376_1131199900266747_6658048849036181504_n.jpg?ig_cache_key=MTM4MDU3ODg0NzQ4OTMxNTk0NA%3D%3D.2', '', '', '', '', '', '', '', '', '2016-11-11 00:10:45', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagrams` VALUES ('18', '1', '1380578230473690307_4033827353', 'https://www.instagram.com/p/BMozJYEBYzD/', '023', 'image', 'https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/15043590_716119015209760_5926054295718854656_n.jpg?ig_cache_key=MTM4MDU3ODIzMDQ3MzY5MDMwNw%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/15043590_716119015209760_5926054295718854656_n.jpg?ig_cache_key=MTM4MDU3ODIzMDQ3MzY5MDMwNw%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/15043590_716119015209760_5926054295718854656_n.jpg?ig_cache_key=MTM4MDU3ODIzMDQ3MzY5MDMwNw%3D%3D.2', '', '', '', '', '', '', '', '', '2016-11-11 00:09:31', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagrams` VALUES ('19', '1', '1380577887740453733_4033827353', 'https://www.instagram.com/p/BMozEY3h2Nl/', '022', 'image', 'https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/14553134_221718851584327_1573721024216694784_n.jpg?ig_cache_key=MTM4MDU3Nzg4Nzc0MDQ1MzczMw%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/14553134_221718851584327_1573721024216694784_n.jpg?ig_cache_key=MTM4MDU3Nzg4Nzc0MDQ1MzczMw%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/14553134_221718851584327_1573721024216694784_n.jpg?ig_cache_key=MTM4MDU3Nzg4Nzc0MDQ1MzczMw%3D%3D.2', '', '', '', '', '', '', '', '', '2016-11-11 00:08:50', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);
INSERT INTO `post_instagrams` VALUES ('20', '1', '1380577777555916816_4033827353', 'https://www.instagram.com/p/BMozCyQBMgQ/', '021', 'image', 'https://scontent.cdninstagram.com/t51.2885-15/s320x320/e35/14624612_196418880806535_4039087236292018176_n.jpg?ig_cache_key=MTM4MDU3Nzc3NzU1NTkxNjgxNg%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s150x150/e35/14624612_196418880806535_4039087236292018176_n.jpg?ig_cache_key=MTM4MDU3Nzc3NzU1NTkxNjgxNg%3D%3D.2', 'https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/14624612_196418880806535_4039087236292018176_n.jpg?ig_cache_key=MTM4MDU3Nzc3NzU1NTkxNjgxNg%3D%3D.2', '', '', '', '', '', '', '', '', '2016-11-11 00:08:37', '2016-11-18 14:21:27', '2016-11-18 14:21:27', null);

-- ----------------------------
-- Table structure for `post_twitter_details`
-- ----------------------------
DROP TABLE IF EXISTS `post_twitter_details`;
CREATE TABLE `post_twitter_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `retweet_count` text,
  `favorite_count` text,
  `date` date DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of post_twitter_details
-- ----------------------------
INSERT INTO `post_twitter_details` VALUES ('1', '1', '0', '0', '2016-11-18', '2016-11-18 14:21:50', '2016-11-18 14:21:50');
INSERT INTO `post_twitter_details` VALUES ('2', '2', '0', '0', '2016-11-18', '2016-11-18 14:21:50', '2016-11-18 14:21:50');
INSERT INTO `post_twitter_details` VALUES ('3', '3', '0', '0', '2016-11-18', '2016-11-18 14:21:50', '2016-11-18 14:21:50');
INSERT INTO `post_twitter_details` VALUES ('4', '4', '0', '0', '2016-11-18', '2016-11-18 14:21:50', '2016-11-18 14:21:50');
INSERT INTO `post_twitter_details` VALUES ('5', '5', '0', '0', '2016-11-18', '2016-11-18 14:21:50', '2016-11-18 14:21:50');
INSERT INTO `post_twitter_details` VALUES ('6', '6', '0', '1', '2016-11-18', '2016-11-18 14:21:50', '2016-11-18 14:21:50');
INSERT INTO `post_twitter_details` VALUES ('7', '7', '1', '1', '2016-11-18', '2016-11-18 14:21:50', '2016-11-18 14:21:50');
INSERT INTO `post_twitter_details` VALUES ('8', '8', '0', '1', '2016-11-18', '2016-11-18 14:21:50', '2016-11-18 14:21:50');
INSERT INTO `post_twitter_details` VALUES ('9', '9', '0', '0', '2016-11-18', '2016-11-18 14:21:50', '2016-11-18 14:21:50');
INSERT INTO `post_twitter_details` VALUES ('10', '10', '0', '0', '2016-11-18', '2016-11-18 14:21:50', '2016-11-18 14:21:50');
INSERT INTO `post_twitter_details` VALUES ('11', '11', '0', '0', '2016-11-18', '2016-11-18 14:21:50', '2016-11-18 14:21:50');

-- ----------------------------
-- Table structure for `post_twitters`
-- ----------------------------
DROP TABLE IF EXISTS `post_twitters`;
CREATE TABLE `post_twitters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) DEFAULT NULL,
  `sns_post_id` varchar(255) DEFAULT NULL,
  `content` text CHARACTER SET utf8,
  `image_thumbnail` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of post_twitters
-- ----------------------------
INSERT INTO `post_twitters` VALUES ('1', '2', '794117547859804160', 'video https://t.co/vsYtui5GRt', 'http://pbs.twimg.com/ext_tw_video_thumb/794117423263662080/pu/img/YtHKNFTJ6BoTAMfT.jpg', '3Haile04', '2016-11-03 17:02:32', '2016-11-18 14:21:50', '2016-11-18 14:21:50');
INSERT INTO `post_twitters` VALUES ('2', '2', '794117390254637056', 'https://t.co/ciTukUbgUP https://t.co/QNU9mLnDYB', 'http://pbs.twimg.com/media/CwVFhEyUkAAhvuZ.jpg', '3Haile04', '2016-11-03 17:01:54', '2016-11-18 14:21:50', '2016-11-18 14:21:50');
INSERT INTO `post_twitters` VALUES ('3', '2', '794112358075551746', 'zzzzzzzzzzzz https://t.co/M5fnkOHjUb', 'http://pbs.twimg.com/media/CwVA8RsUcAA0e_w.jpg', '3Haile04', '2016-11-03 16:41:54', '2016-11-18 14:21:50', '2016-11-18 14:21:50');
INSERT INTO `post_twitters` VALUES ('4', '2', '794110632463388672', 'testyttttttttttttt..............hhhhh https://t.co/0RQ8SooXIF', 'http://pbs.twimg.com/media/CwU_W-BVQAEcKGW.jpg', '3Haile04', '2016-11-03 16:35:03', '2016-11-18 14:21:50', '2016-11-18 14:21:50');
INSERT INTO `post_twitters` VALUES ('5', '2', '790824841515368448', 'test https://t.co/voI8nQwscy', null, '3Haile04', '2016-10-25 14:58:29', '2016-11-18 14:21:50', '2016-11-18 14:21:50');
INSERT INTO `post_twitters` VALUES ('6', '2', '615671182981926912', 'Kamen rider ep 35 \nhttps://t.co/p3RmJwcQpz\n#kamenrider @KamenRiderDCD @KamenRiderDB @KamenRiderDK @KamenRiderDelta @kamenriderdraga', null, '3Haile04', '2015-06-30 07:00:23', '2016-11-18 14:21:50', '2016-11-18 14:21:50');
INSERT INTO `post_twitters` VALUES ('7', '2', '615670677173985280', 'Kamen rider ep 35 \nhttps://t.co/p3RmJwcQpz\n@kamenRider_faiz @kamen_drive @KamenRiderBot @nikou_kamrider @kamenriderbebek', null, '3Haile04', '2015-06-30 06:58:22', '2016-11-18 14:21:50', '2016-11-18 14:21:50');
INSERT INTO `post_twitters` VALUES ('8', '2', '613962276823904257', 'https://t.co/LbHTbpuVrx\n#kamenrider #kamenashi @KamenRiderBot @RiderDiner http://t.co/XykcOFGbyI', 'http://pbs.twimg.com/media/CIU7b2QVAAAIzhY.jpg', '3Haile04', '2015-06-25 13:49:48', '2016-11-18 14:21:50', '2016-11-18 14:21:50');
INSERT INTO `post_twitters` VALUES ('9', '2', '605510510142816256', 'https://t.co/nI9R2a2t11', null, '3Haile04', '2015-06-02 06:05:30', '2016-11-18 14:21:50', '2016-11-18 14:21:50');
INSERT INTO `post_twitters` VALUES ('10', '2', '599831703314567168', 'One piece 693 english sub', null, '3Haile04', '2015-05-17 13:59:57', '2016-11-18 14:21:50', '2016-11-18 14:21:50');
INSERT INTO `post_twitters` VALUES ('11', '2', '599823642784960513', 'Watch full sci fi movie Parazit Eve http://t.co/tBDcQ31QjT', null, '3Haile04', '2015-05-17 13:27:55', '2016-11-18 14:21:50', '2016-11-18 14:21:50');

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contract_status` varchar(3) COLLATE utf8_unicode_ci DEFAULT '001',
  `company_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `authority` tinyint(2) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('2', 'admin', 'admin@wevnal.co.jp', '$2y$10$F815iZOxBipf3Qh/5ogAbOoPmJsDzHl2Ugyo9G.U42R3qjtB11cdG', '001', 'Miyatsu', 'http://miyatsu.vn', null, '2', 'meFVlv02Aqz6cgQ2GZJXyiypBJp54LpGAPYNxaAwI5YrSqLbZlylZAbgX81Q', null, '2016-11-18 08:56:34', null);
INSERT INTO `users` VALUES ('13', 'client', 'client@wevnal.co.jp', '$2y$10$NdfgomEaKvjyFmeskFlXheGQITwN7TUpyTZ9gobi.0dV1Yo3mI7V.', '001', 'Miyatsu', 'http://miyatsu.vn', null, '1', 'zQhGvPCfeWOQS7qnVdOSvedhnmvgpRu1PjV7CBaUZ3TbPdRPNUCaS4r2wiPk', null, '2016-11-16 17:05:17', null);
INSERT INTO `users` VALUES ('14', 'HaiLV', 'le.van.hai@miyatsu.vn', '$2y$10$JYt/w0vkJbiRYpb4yyShk.deL9urddVkGSI/gYE6ZFqNsdmsemExO', '001', 'company', '', '', '1', null, null, '2016-11-16 16:15:59', '2016-11-16 16:15:59');
