/*
 Navicat Premium Data Transfer

 Source Server         : 47.244.2.19
 Source Server Type    : MySQL
 Source Server Version : 50562
 Source Host           : 47.244.2.19:3306
 Source Schema         : usb_360drm_com

 Target Server Type    : MySQL
 Target Server Version : 50562
 File Encoding         : 65001

 Date: 12/07/2021 17:41:05
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for u_activation_codes
-- ----------------------------
DROP TABLE IF EXISTS `u_activation_codes`;
CREATE TABLE `u_activation_codes`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '激活码',
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '状态[0:未激活|1:已激活]',
  `batch_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '批次号',
  `auth_count` int(10) NOT NULL DEFAULT 0 COMMENT '新增USB授权数量',
  `active_time` datetime NULL DEFAULT NULL COMMENT '激活时间',
  `active_merchant_id` int(11) NOT NULL DEFAULT 0 COMMENT '激活商家id',
  `active_ip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '激活商家ip',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uni_code`(`code`) USING BTREE,
  INDEX `index_status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '激活码' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of u_activation_codes
-- ----------------------------

-- ----------------------------
-- Table structure for u_crm_admin_logs
-- ----------------------------
DROP TABLE IF EXISTS `u_crm_admin_logs`;
CREATE TABLE `u_crm_admin_logs`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT 0 COMMENT '创建者',
  `title` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '日志标题',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '日志类型',
  `ip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ip地址',
  `url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '请求url',
  `method` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '请求方式',
  `param` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '请求参数',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'CRM管理员日志' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of u_crm_admin_logs
-- ----------------------------

-- ----------------------------
-- Table structure for u_crm_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `u_crm_admin_role`;
CREATE TABLE `u_crm_admin_role`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_role_id_rule_id`(`admin_id`, `role_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'CRM管理员角色表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of u_crm_admin_role
-- ----------------------------
INSERT INTO `u_crm_admin_role` VALUES (1, 1, 1, '2021-06-04 16:17:14', '2021-06-04 16:17:16');
INSERT INTO `u_crm_admin_role` VALUES (2, 2, 2, NULL, NULL);

-- ----------------------------
-- Table structure for u_crm_admins
-- ----------------------------
DROP TABLE IF EXISTS `u_crm_admins`;
CREATE TABLE `u_crm_admins`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '邮箱',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '登录令牌',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '账号状态',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique_username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'CRM管理员表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of u_crm_admins
-- ----------------------------
INSERT INTO `u_crm_admins` VALUES (1, 'admin', '$2y$12$X/AfJBYMdrniMxHxVaCx4uIks4z.uD8K1leF3oAJQTRC.7Nmfy/mC', '1501111dd@sddd.com', '', 1, '2021-06-04 16:18:01', '2021-07-05 07:29:53');
INSERT INTO `u_crm_admins` VALUES (2, 'demo', '$2y$12$yiTk/CULHKAEz9a3INFlh.WrtKuBNj/By2UXdmgUfSw88Js16ojxW', NULL, NULL, 1, '2021-07-12 09:40:03', '2021-07-12 09:40:03');

-- ----------------------------
-- Table structure for u_crm_role_rule
-- ----------------------------
DROP TABLE IF EXISTS `u_crm_role_rule`;
CREATE TABLE `u_crm_role_rule`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `rule_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_role_id_rule_id`(`role_id`, `rule_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 166 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'CRM角色权限' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of u_crm_role_rule
-- ----------------------------
INSERT INTO `u_crm_role_rule` VALUES (1, 1, 1, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (2, 1, 2, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (3, 1, 3, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (4, 1, 4, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (5, 1, 5, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (6, 1, 6, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (156, 2, 40, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (8, 1, 8, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (9, 1, 9, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (10, 1, 10, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (11, 1, 11, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (12, 1, 12, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (13, 1, 13, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (14, 1, 14, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (15, 1, 15, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (16, 1, 16, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (17, 1, 17, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (18, 1, 18, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (19, 1, 19, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (20, 1, 20, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (21, 1, 21, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (22, 1, 22, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (24, 1, 24, '2021-05-25 14:18:27', '2021-05-25 14:18:27');
INSERT INTO `u_crm_role_rule` VALUES (70, 1, 45, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (69, 1, 44, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (68, 1, 43, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (67, 1, 42, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (66, 1, 41, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (65, 1, 40, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (64, 1, 39, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (63, 1, 38, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (62, 1, 50, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (61, 1, 37, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (60, 1, 36, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (59, 1, 35, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (58, 1, 34, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (57, 1, 33, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (56, 1, 32, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (84, 1, 7, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (54, 1, 30, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (53, 1, 29, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (52, 1, 28, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (51, 1, 27, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (50, 1, 26, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (49, 1, 25, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (72, 1, 47, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (71, 1, 46, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (73, 1, 48, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (74, 1, 49, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (75, 1, 51, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (76, 1, 52, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (79, 1, 55, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (80, 1, 56, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (81, 1, 57, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (82, 1, 60, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (83, 1, 61, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (155, 2, 39, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (154, 2, 38, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (147, 2, 46, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (146, 2, 52, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (145, 2, 45, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (144, 2, 47, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (143, 2, 48, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (142, 2, 49, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (141, 2, 51, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (140, 2, 44, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (139, 2, 50, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (138, 2, 33, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (137, 2, 34, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (136, 2, 35, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (135, 2, 36, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (134, 2, 37, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (133, 2, 32, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (132, 2, 2, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (131, 2, 1, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (157, 2, 41, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (158, 2, 42, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (159, 2, 43, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (160, 2, 24, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (161, 2, 55, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (162, 2, 56, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (163, 2, 61, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (164, 2, 57, NULL, NULL);
INSERT INTO `u_crm_role_rule` VALUES (165, 2, 60, NULL, NULL);

-- ----------------------------
-- Table structure for u_crm_roles
-- ----------------------------
DROP TABLE IF EXISTS `u_crm_roles`;
CREATE TABLE `u_crm_roles`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户组名',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'CRM角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of u_crm_roles
-- ----------------------------
INSERT INTO `u_crm_roles` VALUES (1, '超级管理员', '2021-06-04 16:15:07', '2021-06-04 16:15:10');
INSERT INTO `u_crm_roles` VALUES (2, '系统管理员', '2021-07-05 07:27:43', '2021-07-05 07:27:43');

-- ----------------------------
-- Table structure for u_crm_rules
-- ----------------------------
DROP TABLE IF EXISTS `u_crm_rules`;
CREATE TABLE `u_crm_rules`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '权限菜单名称',
  `href` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '链接url',
  `rule` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '控制器方法',
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '父级id',
  `check` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否需要验证',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '类型:0仅权限,1菜单和权限',
  `level` tinyint(4) NOT NULL,
  `icon` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '图标',
  `sort` smallint(6) NOT NULL COMMENT '排序',
  `islog` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否需要记录日志:0不需要,1需要',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 62 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'CRM访问/路由规则' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of u_crm_rules
-- ----------------------------
INSERT INTO `u_crm_rules` VALUES (1, '首页', '/crm/first', 'crm.first', 0, 0, 1, 1, 'layui-icon-home', 1, 0, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (2, '修改密码', NULL, 'crm.admin.password', 1, 1, 0, 2, '', 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (3, 'CRM管理', NULL, NULL, 0, 1, 1, 1, 'layui-icon-template', 2, 0, NULL, '2021-07-05 08:03:51');
INSERT INTO `u_crm_rules` VALUES (4, 'CRM人员', '/crm/admin', 'crm.admin.index', 3, 1, 1, 2, NULL, 1, 0, NULL, '2021-07-06 02:40:13');
INSERT INTO `u_crm_rules` VALUES (5, '添加CRM管理员页面', NULL, 'crm.admin.create', 4, 1, 0, 3, NULL, 50, 0, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (6, '添加CRM管理员', NULL, 'crm.admin.store', 4, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (7, '禁用CRM管理员', NULL, 'crm.admin.active', 4, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (8, '编辑CRM管理员页面', NULL, 'crm.admin.edit', 4, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (9, '编辑CRM管理员', NULL, 'crm.admin.update', 4, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (10, 'CRM权限', '/crm/rule', 'crm.rule.index', 3, 1, 1, 2, NULL, 50, 0, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (11, '添加CRM权限页面', NULL, 'crm.rule.create', 10, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (12, '添加CRM权限', NULL, 'crm.rule.store', 10, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (13, '编辑CRM权限页面', NULL, 'crm.rule.edit', 10, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (14, '编辑CRM权限', NULL, 'crm.rule.update', 10, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (15, '删除CRM权限', NULL, 'crm.rule.destroy', 10, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (16, 'CRM角色', '/crm/role', 'crm.role.index', 3, 1, 1, 2, NULL, 2, 0, NULL, '2021-07-06 02:40:21');
INSERT INTO `u_crm_rules` VALUES (17, '添加CRM角色', NULL, 'crm.role.store', 16, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (18, '编辑CRM角色', NULL, 'crm.role.update', 16, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (19, '删除CRM角色', NULL, 'crm.role.destroy', 16, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (20, '配置CRM权限页面', NULL, 'crm.role.rule', 16, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (21, '配置CRM权限', NULL, 'crm.role.rule.set', 16, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (22, '删除CRM管理员', NULL, 'crm.admin.destroy', 4, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (24, '日志管理', '/crm/admin/log', 'crm.admin.log', 0, 1, 1, 1, 'layui-icon-circle', 9, 0, NULL, '2021-07-05 08:07:09');
INSERT INTO `u_crm_rules` VALUES (25, '语言包管理', '/crm/language', 'crm.language.index', 0, 0, 1, 1, 'layui-icon-fonts-i', 5, 0, NULL, '2021-07-05 08:05:51');
INSERT INTO `u_crm_rules` VALUES (26, '添加多语言页面', '', 'crm.language.create', 25, 0, 0, 3, '', 50, 0, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (27, '创建多语言', '', 'crm.language.store', 25, 0, 0, 3, '', 50, 0, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (28, '编辑多语言页面', '', 'crm.language.edit', 25, 0, 0, 3, '', 50, 0, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (29, '更新多语言', '', 'crm.language.update', 25, 0, 0, 3, '', 50, 0, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (30, '删除多语言', '', 'crm.language.destroy', 25, 0, 0, 3, '', 50, 0, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (32, '商户管理', '/crm/merchant', 'crm.merchant.index', 0, 1, 1, 1, 'layui-icon-user', 3, 0, NULL, '2021-07-05 08:04:42');
INSERT INTO `u_crm_rules` VALUES (33, '添加商户页面', NULL, 'crm.merchant.create', 32, 1, 0, 3, NULL, 50, 0, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (34, '添加商户', NULL, 'crm.merchant.store', 32, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (35, '禁用商户', NULL, 'crm.merchant.active', 32, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (36, '编辑商户页面', NULL, 'crm.merchant.edit', 32, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (37, '编辑商户', NULL, 'crm.merchant.update', 32, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (38, '权限管理', '/crm/merchant/rule', 'crm.merchant.rule.index', 0, 1, 1, 1, 'layui-icon-cols', 6, 1, NULL, '2021-07-06 02:25:57');
INSERT INTO `u_crm_rules` VALUES (39, '添加商户权限页面', NULL, 'crm.merchant.rule.create', 38, 1, 0, 3, NULL, 50, 0, NULL, '2021-06-08 03:42:14');
INSERT INTO `u_crm_rules` VALUES (40, '添加商户权限', NULL, 'crm.merchant.rule.store', 38, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (41, '编辑商户权限页面', NULL, 'crm.merchant.rule.edit', 38, 1, 0, 3, NULL, 50, 0, NULL, '2021-06-08 03:42:09');
INSERT INTO `u_crm_rules` VALUES (42, '编辑商户权限', NULL, 'crm.merchant.rule.update', 38, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (43, '删除商户权限', NULL, 'crm.merchant.rule.destroy', 38, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (44, '版本管理', '/crm/merchant/version', 'crm.merchant.version.index', 0, 1, 1, 1, 'layui-icon-read', 4, 1, NULL, '2021-07-05 08:05:06');
INSERT INTO `u_crm_rules` VALUES (45, '添加商户版本', NULL, 'crm.merchant.version.store', 44, 1, 0, 3, NULL, 51, 1, NULL, '2021-07-06 06:15:08');
INSERT INTO `u_crm_rules` VALUES (46, '编辑商户版本', NULL, 'crm.merchant.version.update', 44, 1, 0, 3, NULL, 53, 1, NULL, '2021-07-06 06:15:28');
INSERT INTO `u_crm_rules` VALUES (47, '删除商户版本', NULL, 'crm.merchant.version.destroy', 44, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (48, '配置商户权限页面', NULL, 'crm.merchant.version.rule', 44, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (49, '配置商户权限', NULL, 'crm.merchant.version.rule.set', 44, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (50, '删除商户', NULL, 'crm.merchant.destroy', 32, 1, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (51, '添加商户版本页面', NULL, 'crm.merchant.version.create', 44, 1, 0, 3, NULL, 50, 0, NULL, '2021-06-08 03:41:54');
INSERT INTO `u_crm_rules` VALUES (52, '编辑商户版本页面', NULL, 'crm.merchant.version.edit', 44, 1, 0, 3, NULL, 52, 0, NULL, '2021-07-06 06:15:21');
INSERT INTO `u_crm_rules` VALUES (55, '激活码', '/crm/activation_code', 'crm.activation_code.index', 0, 1, 1, 1, 'layui-icon-survey', 50, 0, '2021-06-11 02:57:43', '2021-07-05 08:07:32');
INSERT INTO `u_crm_rules` VALUES (56, '添加激活码页面', '', 'crm.activation_code.create', 55, 0, 0, 3, '', 50, 0, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (57, '创建激活码', '', 'crm.activation_code.store', 55, 0, 0, 3, '', 50, 0, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (60, '删除激活码', '', 'crm.activation_code.destroy', 55, 0, 0, 3, '', 50, 0, NULL, NULL);
INSERT INTO `u_crm_rules` VALUES (61, '批次号查看', '', 'crm.activation_code.batch_no', 55, 1, 0, 2, '', 50, 0, '2021-06-11 09:04:16', '2021-06-11 09:04:16');

-- ----------------------------
-- Table structure for u_disk_encrypt_records
-- ----------------------------
DROP TABLE IF EXISTS `u_disk_encrypt_records`;
CREATE TABLE `u_disk_encrypt_records`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant_id` int(11) NOT NULL DEFAULT 0 COMMENT '商家ID',
  `disk_id` int(11) NOT NULL DEFAULT 0 COMMENT 'u盘ID',
  `logical_sequence` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '逻辑序列号',
  `ip` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '操作IP',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_merchant_id_disk_id`(`merchant_id`, `disk_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = 'U盘加密记录' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of u_disk_encrypt_records
-- ----------------------------

-- ----------------------------
-- Table structure for u_disk_tracks
-- ----------------------------
DROP TABLE IF EXISTS `u_disk_tracks`;
CREATE TABLE `u_disk_tracks`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant_id` int(11) NOT NULL DEFAULT 0 COMMENT '商家ID',
  `disk_id` int(11) NOT NULL DEFAULT 0 COMMENT 'U盘ID',
  `event_username` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '事件用户',
  `event_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '事件名',
  `event_desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '事件详情',
  `machine_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '机器码',
  `ip` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '操作IP',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = 'u盘轨迹' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of u_disk_tracks
-- ----------------------------

-- ----------------------------
-- Table structure for u_disks
-- ----------------------------
DROP TABLE IF EXISTS `u_disks`;
CREATE TABLE `u_disks`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant_id` int(11) NOT NULL DEFAULT 0 COMMENT '商家ID',
  `name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '备注名',
  `strategy_update_id` int(11) NOT NULL DEFAULT 0 COMMENT '更新策略',
  `strategy_auth_id` int(11) NOT NULL DEFAULT 0 COMMENT '权限策略',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '状态',
  `run_count` int(10) NOT NULL DEFAULT 0 COMMENT '已运行次数',
  `encrypt_count` int(10) NOT NULL DEFAULT 0 COMMENT '已加密次数',
  `capacity` bigint(20) NOT NULL DEFAULT 0 COMMENT '容量（字节byte）',
  `usb_serial` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '物理序列号',
  `first_time_use` datetime NULL DEFAULT NULL COMMENT '开始使用时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uni_usb_serial`(`merchant_id`, `usb_serial`) USING BTREE,
  INDEX `index_status`(`status`) USING BTREE,
  INDEX `index_merchant_id`(`merchant_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = 'u盘' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of u_disks
-- ----------------------------

-- ----------------------------
-- Table structure for u_languages
-- ----------------------------
DROP TABLE IF EXISTS `u_languages`;
CREATE TABLE `u_languages`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '语言包',
  `desc` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '逻辑序列号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '语言包' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of u_languages
-- ----------------------------
INSERT INTO `u_languages` VALUES (1, 'en', 'English', '2021-06-09 11:45:15', '2021-06-11 02:39:26');
INSERT INTO `u_languages` VALUES (2, 'zh-cn', '中文', '2021-06-11 10:47:28', '2021-06-11 10:47:31');

-- ----------------------------
-- Table structure for u_merchant_rules
-- ----------------------------
DROP TABLE IF EXISTS `u_merchant_rules`;
CREATE TABLE `u_merchant_rules`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '权限标识',
  `href` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '链接url',
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '父级id',
  `check` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否需要验证',
  `level` tinyint(4) NOT NULL,
  `icon` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '图标',
  `sort` smallint(6) NOT NULL COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uni_title`(`title`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '商家访问/路由规则' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of u_merchant_rules
-- ----------------------------
INSERT INTO `u_merchant_rules` VALUES (1, 'usb_management', '/merchant/disk', 0, 1, 1, 'ftsucai-39', 1, NULL, '2021-07-05 07:20:23');
INSERT INTO `u_merchant_rules` VALUES (3, 'file_management', '/merchant/file', 2, 1, 2, 'ftsucai-file-openoffice', 1, NULL, '2021-07-05 07:22:31');
INSERT INTO `u_merchant_rules` VALUES (4, 'file_update_strategy', '/merchant/strategy_update', 2, 1, 2, 'ftsucai-105', 2, NULL, '2021-07-05 07:22:38');
INSERT INTO `u_merchant_rules` VALUES (5, 'permission_policy', '/merchant/strategy_auth', 0, 1, 1, 'ftsucai-104', 3, NULL, '2021-07-09 09:21:14');
INSERT INTO `u_merchant_rules` VALUES (6, 'add_authorization', '/merchant/authorization', 0, 1, 1, 'ftsucai-214', 5, NULL, '2021-07-05 07:21:50');
INSERT INTO `u_merchant_rules` VALUES (7, 'operation_record', '/merchant/disk_encrypt_record', 0, 1, 1, 'ftsucai-format_align_justify', 6, NULL, '2021-07-06 02:27:51');
INSERT INTO `u_merchant_rules` VALUES (8, 'advanced_settings', '/merchant/merchant_setting', 0, 1, 1, 'ftsucai-jshezhi', 4, NULL, '2021-07-05 07:21:00');
INSERT INTO `u_merchant_rules` VALUES (2, 'update_file_strategy', '', 0, 0, 1, 'ftsucai-105', 2, '2021-07-05 07:18:01', '2021-07-05 07:20:29');

-- ----------------------------
-- Table structure for u_merchant_settings
-- ----------------------------
DROP TABLE IF EXISTS `u_merchant_settings`;
CREATE TABLE `u_merchant_settings`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant_id` int(11) NOT NULL DEFAULT 0 COMMENT '商家ID',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置名称',
  `data` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '配置数据',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uni_name`(`merchant_id`, `name`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '全局配置' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of u_merchant_settings
-- ----------------------------

-- ----------------------------
-- Table structure for u_merchant_version_relation
-- ----------------------------
DROP TABLE IF EXISTS `u_merchant_version_relation`;
CREATE TABLE `u_merchant_version_relation`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant_id` int(11) NOT NULL DEFAULT 0,
  `version_id` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uni_merchant_id`(`merchant_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '商家版本关联表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of u_merchant_version_relation
-- ----------------------------

-- ----------------------------
-- Table structure for u_merchant_version_rule
-- ----------------------------
DROP TABLE IF EXISTS `u_merchant_version_rule`;
CREATE TABLE `u_merchant_version_rule`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `version_id` int(11) NOT NULL,
  `rule_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 49 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '商家版本权限' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of u_merchant_version_rule
-- ----------------------------
INSERT INTO `u_merchant_version_rule` VALUES (1, 1, 1, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (2, 1, 2, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (3, 1, 3, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (4, 1, 4, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (5, 1, 5, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (6, 1, 8, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (7, 1, 6, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (8, 1, 7, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (9, 2, 1, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (10, 2, 2, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (11, 2, 3, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (12, 2, 4, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (13, 2, 5, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (14, 2, 8, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (15, 2, 6, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (16, 2, 7, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (17, 3, 1, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (18, 3, 2, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (19, 3, 3, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (20, 3, 4, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (21, 3, 5, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (22, 3, 8, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (23, 3, 6, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (24, 3, 7, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (25, 4, 1, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (26, 4, 2, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (27, 4, 3, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (28, 4, 4, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (29, 4, 5, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (30, 4, 8, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (31, 4, 6, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (32, 4, 7, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (33, 5, 1, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (34, 5, 2, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (35, 5, 3, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (36, 5, 4, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (37, 5, 5, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (38, 5, 8, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (39, 5, 6, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (40, 5, 7, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (41, 6, 1, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (42, 6, 2, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (43, 6, 3, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (44, 6, 4, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (45, 6, 5, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (46, 6, 8, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (47, 6, 6, NULL, NULL);
INSERT INTO `u_merchant_version_rule` VALUES (48, 6, 7, NULL, NULL);

-- ----------------------------
-- Table structure for u_merchant_versions
-- ----------------------------
DROP TABLE IF EXISTS `u_merchant_versions`;
CREATE TABLE `u_merchant_versions`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '版本标识',
  `disk_number` int(10) NOT NULL DEFAULT 0 COMMENT '可加密U盘数量',
  `price` decimal(10, 2) NOT NULL COMMENT '价格',
  `extra_price` decimal(10, 2) NOT NULL COMMENT '额外授权价格/每个',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uni_name`(`name`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '商家版本表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of u_merchant_versions
-- ----------------------------
INSERT INTO `u_merchant_versions` VALUES (1, 'personal', 245, 5.00, 2.00, '2021-07-03 06:43:09', '2021-07-06 06:16:46');
INSERT INTO `u_merchant_versions` VALUES (2, 'professional', 10, 24.00, 28.00, '2021-07-03 06:43:09', '2021-07-05 07:49:38');
INSERT INTO `u_merchant_versions` VALUES (3, 'enterprise', 45, 45.00, 54.00, '2021-07-03 06:43:09', '2021-07-05 07:49:38');
INSERT INTO `u_merchant_versions` VALUES (4, 'vip', 50, 10.00, 1.00, '2021-07-06 02:07:00', '2021-07-06 02:07:00');
INSERT INTO `u_merchant_versions` VALUES (5, 'exe', 100, 12.00, 1.00, '2021-07-06 02:07:17', '2021-07-06 02:07:17');
INSERT INTO `u_merchant_versions` VALUES (6, 'exclusive', 2, 10.00, 1.00, '2021-07-06 02:07:32', '2021-07-06 06:18:01');

-- ----------------------------
-- Table structure for u_merchants
-- ----------------------------
DROP TABLE IF EXISTS `u_merchants`;
CREATE TABLE `u_merchants`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '商户名称',
  `username` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `email` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `password` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `expire_time` datetime NULL DEFAULT NULL COMMENT '过期时间',
  `is_permanent` tinyint(4) NOT NULL DEFAULT 0 COMMENT '过期时间是否永久',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '状态',
  `remarks` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `lang_id` int(11) NOT NULL DEFAULT 0 COMMENT '语言',
  `auth_number` int(11) NOT NULL DEFAULT 0 COMMENT '已授权数量',
  `add_auth_count` int(11) NOT NULL DEFAULT 0 COMMENT '额外新增授权数量',
  `root_directory` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '跟目录',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uni_username`(`username`) USING BTREE,
  UNIQUE INDEX `uni_root_directory`(`root_directory`) USING BTREE,
  INDEX `index_status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商家' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of u_merchants
-- ----------------------------

-- ----------------------------
-- Table structure for u_settings
-- ----------------------------
DROP TABLE IF EXISTS `u_settings`;
CREATE TABLE `u_settings`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置名称',
  `data` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '配置数据',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uni_name`(`name`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '全局配置' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of u_settings
-- ----------------------------

-- ----------------------------
-- Table structure for u_strategy_auths
-- ----------------------------
DROP TABLE IF EXISTS `u_strategy_auths`;
CREATE TABLE `u_strategy_auths`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant_id` int(11) NOT NULL DEFAULT 0 COMMENT '商家ID',
  `name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '策略名称',
  `expired_type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '过期类型',
  `expired_day` int(10) NOT NULL DEFAULT 0 COMMENT '过期天数',
  `expired_time` datetime NULL DEFAULT NULL COMMENT '过期日期',
  `run_number` int(11) NOT NULL DEFAULT 0 COMMENT '运行次数',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_merchant_id`(`merchant_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '权限策略' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of u_strategy_auths
-- ----------------------------

-- ----------------------------
-- Table structure for u_strategy_update_files
-- ----------------------------
DROP TABLE IF EXISTS `u_strategy_update_files`;
CREATE TABLE `u_strategy_update_files`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant_id` int(10) NOT NULL DEFAULT 0 COMMENT '商家ID',
  `strategy_id` int(10) NOT NULL DEFAULT 0 COMMENT '策略ID',
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '路径',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_strategy_id`(`strategy_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '更新策略文件' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of u_strategy_update_files
-- ----------------------------

-- ----------------------------
-- Table structure for u_strategy_updates
-- ----------------------------
DROP TABLE IF EXISTS `u_strategy_updates`;
CREATE TABLE `u_strategy_updates`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant_id` int(11) NOT NULL DEFAULT 0 COMMENT '商家ID',
  `name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '策略名称',
  `valid_time` datetime NULL DEFAULT NULL COMMENT '生效值',
  `automatic_update_prompt` tinyint(4) NOT NULL DEFAULT 0 COMMENT '自动更新提示',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_name`(`name`) USING BTREE,
  INDEX `index_merchant_id`(`merchant_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '更新策略' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of u_strategy_updates
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
