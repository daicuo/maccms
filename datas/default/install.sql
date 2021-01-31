DROP TABLE IF EXISTS `dc_op`;
CREATE TABLE `dc_op` (
    `op_id` BIGINT(20)  PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `op_name` VARCHAR(150)  NOT NULL,
    `op_value` LONGTEXT  NULL,
    `op_module` VARCHAR(50)  NULL,
    `op_controll` VARCHAR(50)  NULL,
    `op_action` VARCHAR(50)  NULL,
    `op_order` INT(11) DEFAULT '0' NULL,
    `op_autoload` VARCHAR(20) DEFAULT 'yes' NULL,
    `op_status` VARCHAR(60) DEFAULT 'normal' NULL
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
DROP TABLE IF EXISTS `dc_info`;
CREATE TABLE `dc_info` (
    `info_id` BIGINT(20)  PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `info_title` VARCHAR(255)  NULL,
    `info_name` VARCHAR(255)  NULL,
    `info_slug` VARCHAR(255)  NULL,
    `info_excerpt` TEXT  NULL,
    `info_content` LONGTEXT  NULL,
    `info_password` VARCHAR(255)  NULL,
    `info_create_time` INT(11) DEFAULT '0' NULL,
    `info_update_time` INT(11) DEFAULT '0' NULL,
    `info_parent` BIGINT(20) DEFAULT '0' NULL,
    `info_order` INT(11) DEFAULT '0' NULL,
    `info_user_id` BIGINT(20) DEFAULT '0' NULL,
    `info_type` VARCHAR(100) NULL,
    `info_mime_type` VARCHAR(100) NULL,
    `info_status` VARCHAR(60) DEFAULT 'normal' NULL,
    `info_comment_status` VARCHAR(100) DEFAULT 'open' NULL,
    `info_comment_count` BIGINT(20) DEFAULT '0' NULL,
    `info_views` BIGINT(20) DEFAULT '0' NULL,
    `info_hits` BIGINT(20) DEFAULT '0' NULL,
    `info_module` VARCHAR(100) DEFAULT 'common' NULL,
    `info_controll` VARCHAR(100) NULL,
    `info_action` VARCHAR(100) NULL
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
DROP TABLE IF EXISTS `dc_info_meta`;
CREATE TABLE `dc_info_meta` (
    `info_meta_id` BIGINT(20)  PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `info_meta_key` VARCHAR(255)  NOT NULL,
    `info_meta_value` LONGTEXT  NULL,
    `info_id` BIGINT(20) DEFAULT '0' NOT NULL
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
DROP TABLE IF EXISTS `dc_term`;
CREATE TABLE `dc_term` (
    `term_id` BIGINT(20)  PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `term_name` VARCHAR(255)  NULL,
    `term_slug` VARCHAR(255)  NULL,
    `term_module` VARCHAR(50) DEFAULT 'common' NULL,
    `term_status` VARCHAR(60) DEFAULT 'normal' NULL,
    `term_order` INT(11) DEFAULT '0' NULL
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
DROP TABLE IF EXISTS `dc_term_map`;
CREATE TABLE `dc_term_map` (
    `detail_id` BIGINT(20) DEFAULT '0' NULL,
    `term_much_id` BIGINT(20) DEFAULT '0' NULL
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
DROP TABLE IF EXISTS `dc_term_meta`;
CREATE TABLE `dc_term_meta` (
    `term_meta_id` BIGINT(20)  PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `term_meta_key` VARCHAR(255)  NOT NULL,
    `term_meta_value` LONGTEXT  NULL,
    `term_id` BIGINT(20) DEFAULT '0' NOT NULL
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
DROP TABLE IF EXISTS `dc_term_much`;
CREATE TABLE `dc_term_much` (
    `term_much_id` BIGINT(20)  PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `term_much_type` VARCHAR(32)  NULL,
    `term_much_info` LONGTEXT  NULL,
    `term_much_parent` BIGINT(20) DEFAULT '0' NULL,
    `term_much_count` INT(11) DEFAULT '0' NULL,
    `term_id` BIGINT(20) DEFAULT '0' NULL
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
DROP TABLE IF EXISTS `dc_user`;
CREATE TABLE `dc_user` (
    `user_id` BIGINT(20)  PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `user_email` VARCHAR(150)  NULL,
    `user_name` VARCHAR(150)  NOT NULL,
    `user_nick_name` VARCHAR(255)  NULL,
    `user_mobile` VARCHAR(20)  NULL,
    `user_pass` VARCHAR(32)  NOT NULL,
    `user_status` VARCHAR(60) DEFAULT 'normal' NOT NULL,
    `user_create_time` INT(11) DEFAULT '0' NULL,
    `user_update_time` INT(11) DEFAULT '0' NULL,
    `user_create_ip` VARCHAR(32)  NULL,
    `user_update_ip` VARCHAR(32)  NULL,
    `user_slug` VARCHAR(255)  NULL,
    `user_views` BIGINT(20) DEFAULT '0' NULL,
    `user_hits` BIGINT(20) DEFAULT '0' NULL,
    `user_token` VARCHAR(255)  NULL,
    `user_module` VARCHAR(100) DEFAULT 'common' NULL,
    `user_controll` VARCHAR(100) NULL,
    `user_action` VARCHAR(100) NULL
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
DROP TABLE IF EXISTS `dc_user_meta`;
CREATE TABLE `dc_user_meta` (
    `user_meta_id` BIGINT(20)  PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `user_meta_key` VARCHAR(255)  NOT NULL,
    `user_meta_value` LONGTEXT  NULL,
    `user_id` BIGINT(20) DEFAULT '0' NOT NULL
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
create index op_module on dc_op(op_module);
create index op_status on dc_op(op_status);
create index info_type on dc_info(info_type);
create index info_module on dc_info(info_module);
create index info_controll on dc_info(info_controll);
create index info_action on dc_info(info_action);
create index info_slug on dc_info(info_slug);
create index info_status on dc_info(info_status);
create index info_parent on dc_info(info_parent);
create index info_user_id on dc_info(info_user_id);
create index info_id on dc_info_meta(info_id);
create index info_meta_key on dc_info_meta(info_meta_key);
create index term_name on dc_term(term_name);
create index term_slug on dc_term(term_slug);
create index term_status on dc_term(term_status);
create index detail_id on dc_term_map(detail_id);
create index term_much_id on dc_term_map(term_much_id);
create index term_id on dc_term_meta(term_id);
create index term_meta_key on dc_term_meta(term_meta_key);
create index term_id_index on dc_term_much(term_id);
create index term_much_type on dc_term_much(term_much_type);
create index user_name on dc_user(user_name);
create index user_email on dc_user(user_email);
create index user_status on dc_user(user_status);
create index user_id on dc_user_meta(user_id);
create index user_meta_key on dc_user_meta(user_meta_key);

INSERT INTO `dc_user` (`user_id`, `user_email`, `user_name`, `user_nick_name`, `user_mobile`, `user_pass`, `user_status`, `user_create_time`, `user_update_time`, `user_create_ip`, `user_update_ip`, `user_slug`, `user_views`, `user_hits`, `user_module`, `user_controll`, `user_action`) VALUES ('1', 'admin@daicuo.org', 'admin', '', '13800138000', '7fef6171469e80d32c0559f88b377245', 'normal', '0', '1599138991', '0', '127.0.0.1', '', '0', '0', 'common','','');
INSERT INTO `dc_user_meta` (`user_meta_id`, `user_meta_key`, `user_meta_value`, `user_id`) VALUES ('20', 'user_capabilities', 'a:2:{i:0;s:5:\"guest\";i:1;s:13:\"administrator\";}', '1');
INSERT INTO `dc_term` (`term_id`, `term_name`, `term_slug`, `term_module`, `term_status`, `term_order`) VALUES ('1', '分类1', 'fenlei1', 'common', 'normal', '0');
INSERT INTO `dc_term` (`term_id`, `term_name`, `term_slug`, `term_module`, `term_status`, `term_order`) VALUES ('2', '分类2', 'fenlei2', 'common', 'normal', '0');
INSERT INTO `dc_term` (`term_id`, `term_name`, `term_slug`, `term_module`, `term_status`, `term_order`) VALUES ('3', '分类3', 'fenlei3', 'common', 'normal', '0');
INSERT INTO `dc_term` (`term_id`, `term_name`, `term_slug`, `term_module`, `term_status`, `term_order`) VALUES ('4', '子分类', 'zifenlei', 'common', 'normal', '0');
INSERT INTO `dc_term` (`term_id`, `term_name`, `term_slug`, `term_module`, `term_status`, `term_order`) VALUES ('5', '孙分类', 'sunfenlei', 'common', 'normal', '0');
INSERT INTO `dc_term` (`term_id`, `term_name`, `term_slug`, `term_module`, `term_status`, `term_order`) VALUES ('6', '标签1', 'biaoqian1', 'common', 'normal', '0');
INSERT INTO `dc_term` (`term_id`, `term_name`, `term_slug`, `term_module`, `term_status`, `term_order`) VALUES ('7', '标签2', 'biaoqian2', 'common', 'normal', '0');
INSERT INTO `dc_term` (`term_id`, `term_name`, `term_slug`, `term_module`, `term_status`, `term_order`) VALUES ('8', '标签3', 'biaoqian3', 'common', 'normal', '0');
INSERT INTO `dc_term` (`term_id`, `term_name`, `term_slug`, `term_module`, `term_status`, `term_order`) VALUES ('9', '标签4', 'biaoqian4', 'common', 'normal', '0');
INSERT INTO `dc_term` (`term_id`, `term_name`, `term_slug`, `term_module`, `term_status`, `term_order`) VALUES ('10', '标签5', 'biaoqian5', 'common', 'normal', '0');
INSERT INTO `dc_term_much` (`term_much_id`, `term_much_type`, `term_much_info`, `term_much_parent`, `term_much_count`, `term_id`) VALUES ('1', 'category', '', '0', '0', '1');
INSERT INTO `dc_term_much` (`term_much_id`, `term_much_type`, `term_much_info`, `term_much_parent`, `term_much_count`, `term_id`) VALUES ('2', 'category', '', '0', '0', '2');
INSERT INTO `dc_term_much` (`term_much_id`, `term_much_type`, `term_much_info`, `term_much_parent`, `term_much_count`, `term_id`) VALUES ('3', 'category', '', '0', '0', '3');
INSERT INTO `dc_term_much` (`term_much_id`, `term_much_type`, `term_much_info`, `term_much_parent`, `term_much_count`, `term_id`) VALUES ('4', 'category', '', '1', '0', '4');
INSERT INTO `dc_term_much` (`term_much_id`, `term_much_type`, `term_much_info`, `term_much_parent`, `term_much_count`, `term_id`) VALUES ('5', 'category', '', '4', '0', '5');
INSERT INTO `dc_term_much` (`term_much_id`, `term_much_type`, `term_much_info`, `term_much_parent`, `term_much_count`, `term_id`) VALUES ('6', 'tag', '', '0', '0', '6');
INSERT INTO `dc_term_much` (`term_much_id`, `term_much_type`, `term_much_info`, `term_much_parent`, `term_much_count`, `term_id`) VALUES ('7', 'tag', '', '0', '0', '7');
INSERT INTO `dc_term_much` (`term_much_id`, `term_much_type`, `term_much_info`, `term_much_parent`, `term_much_count`, `term_id`) VALUES ('8', 'tag', '', '0', '0', '8');
INSERT INTO `dc_term_much` (`term_much_id`, `term_much_type`, `term_much_info`, `term_much_parent`, `term_much_count`, `term_id`) VALUES ('9', 'tag', '', '0', '0', '9');
INSERT INTO `dc_term_much` (`term_much_id`, `term_much_type`, `term_much_info`, `term_much_parent`, `term_much_count`, `term_id`) VALUES ('10', 'tag', '', '0', '0', '10');
INSERT INTO `dc_term_meta` (`term_meta_id`, `term_meta_key`, `term_meta_value`, `term_id`) VALUES ('1', 'term_tpl', 'index', '1');
INSERT INTO `dc_term_meta` (`term_meta_id`, `term_meta_key`, `term_meta_value`, `term_id`) VALUES ('2', 'term_hook', '分类1', '1');
INSERT INTO `dc_term_meta` (`term_meta_id`, `term_meta_key`, `term_meta_value`, `term_id`) VALUES ('5', 'term_tpl', 'index', '3');
INSERT INTO `dc_term_meta` (`term_meta_id`, `term_meta_key`, `term_meta_value`, `term_id`) VALUES ('6', 'term_hook', '分类3', '3');
INSERT INTO `dc_term_meta` (`term_meta_id`, `term_meta_key`, `term_meta_value`, `term_id`) VALUES ('7', 'term_tpl', 'index', '4');
INSERT INTO `dc_term_meta` (`term_meta_id`, `term_meta_key`, `term_meta_value`, `term_id`) VALUES ('8', 'term_hook', '子分类', '4');
INSERT INTO `dc_term_meta` (`term_meta_id`, `term_meta_key`, `term_meta_value`, `term_id`) VALUES ('9', 'term_tpl', 'index', '5');
INSERT INTO `dc_term_meta` (`term_meta_id`, `term_meta_key`, `term_meta_value`, `term_id`) VALUES ('10', 'term_hook', '孙分类', '5');
INSERT INTO `dc_term_meta` (`term_meta_id`, `term_meta_key`, `term_meta_value`, `term_id`) VALUES ('11', 'term_tpl', 'index', '2');
INSERT INTO `dc_term_meta` (`term_meta_id`, `term_meta_key`, `term_meta_value`, `term_id`) VALUES ('12', 'term_hook', '分类2', '2');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('1', 'site_route', 'a:5:{s:4:\"rule\";s:1:\"/\";s:7:\"address\";s:17:\"index/index/index\";s:6:\"method\";s:3:\"get\";s:6:\"option\";s:0:\"\";s:7:\"pattern\";s:0:\"\";}', 'common', 'route', '', '0', 'no', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('2', 'app_debug', 'off', 'common', '', '', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('3', 'app_domain', 'off', 'common', '', '', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('4', 'site_status', 'on', 'common', '', '', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('5', 'site_name', 'DaiCuo', 'common', '', '', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('6', 'site_domain', 'www.daicuo.net', 'common', '', '', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('7', 'wap_domain', '', 'common', '', '', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('8', 'site_theme', 'default_pc', 'common', '', '', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('9', 'wap_theme', 'default_wap', 'common', '', '', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('10', 'site_icp', '', 'common', '', '', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('11', 'site_secret', 'abcdefghijklmnopqrst', 'common', '', '', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('12', 'site_tongji', '', 'common', '', '', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('13', 'site_close', '网站升级中~~~', 'common', '', '', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('14', 'site_title', '呆错开发框架', 'index', '', '', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('15', 'site_keywords', '欢迎使用呆错（DaiCuo）开发您的项目', 'index', '', '', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('16', 'site_description', '基于ThinkPHP、Bootstrap、Jquery的极速后台开发框架', 'index', '', '', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('17', 'theme', 'default_pc', 'index', '', '', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('18', 'theme_wap', 'default_wap', 'index', '', '', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('21', 'site_applys', 'a:1:{s:5:\"index\";a:10:{s:6:\"module\";s:5:\"index\";s:4:\"name\";s:6:\"首页\";s:4:\"info\";s:99:\"首页插件是默认自带的应用，主要的是提供插件开发、安装、卸载的演示！\";s:7:\"version\";s:5:\"1.0.0\";s:3:\"ico\";s:7:\"fa-home\";s:6:\"subico\";s:7:\"fa-gear\";s:3:\"nav\";s:6:\"首页\";s:6:\"subnav\";a:1:{i:0;a:4:{s:5:\"title\";s:12:\"基本设置\";s:8:\"controll\";s:5:\"index\";s:6:\"action\";s:5:\"index\";s:4:\"link\";s:63:\"/admin.php/addon/index?module=index&controll=admin&action=index\";}}s:8:\"datatype\";a:2:{i:0;s:6:\"sqlite\";i:1;s:5:\"mysql\";}s:4:\"rely\";a:1:{s:6:\"daicuo\";a:1:{i:0;s:7:\"1.2.0-9\";}}}}', 'common', '', '', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('22', 'site_title', '呆错开发框架', 'index', 'admin', 'update', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('23', 'site_keywords', '欢迎使用呆错（DaiCuo）开发您的项目', 'index', 'admin', 'update', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('24', 'site_description', '基于ThinkPHP、Bootstrap、Jquery的极速后台开发框架', 'index', 'admin', 'update', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('25', 'theme', 'default_pc', 'index', 'admin', 'update', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('26', 'theme_wap', 'default_wap', 'index', 'admin', 'update', '0', 'yes', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('28', 'site_nav', 'a:10:{s:8:\"nav_text\";s:7:\"导航1\";s:10:\"nav_target\";s:6:\"_blank\";s:8:\"nav_type\";s:4:\"link\";s:7:\"nav_url\";s:24:\"http://www.daicuo.net/?1\";s:10:\"nav_module\";s:0:\"\";s:12:\"nav_controll\";s:0:\"\";s:10:\"nav_action\";s:0:\"\";s:10:\"nav_params\";s:0:\"\";s:7:\"nav_ico\";s:16:\"fa fa-fw fa-list\";s:10:\"nav_active\";s:0:\"\";}', '', '', '', '0', 'no', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('29', 'site_nav', 'a:11:{s:8:\"nav_text\";s:7:\"导航2\";s:10:\"nav_parent\";s:1:\"0\";s:10:\"nav_target\";s:6:\"_blank\";s:8:\"nav_type\";s:4:\"link\";s:7:\"nav_url\";s:24:\"http://www.daicuo.net/?2\";s:10:\"nav_module\";s:0:\"\";s:12:\"nav_controll\";s:0:\"\";s:10:\"nav_action\";s:0:\"\";s:10:\"nav_params\";s:0:\"\";s:7:\"nav_ico\";s:16:\"fa fa-fw fa-list\";s:10:\"nav_active\";s:0:\"\";}', '', '', '', '0', 'no', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('30', 'site_nav', 'a:11:{s:8:\"nav_text\";s:9:\"子导航\";s:10:\"nav_parent\";s:2:\"29\";s:10:\"nav_target\";s:6:\"_blank\";s:8:\"nav_type\";s:4:\"link\";s:7:\"nav_url\";s:26:\"http://www.daicuo.net/?2-1\";s:10:\"nav_module\";s:0:\"\";s:12:\"nav_controll\";s:0:\"\";s:10:\"nav_action\";s:0:\"\";s:10:\"nav_params\";s:0:\"\";s:7:\"nav_ico\";s:16:\"fa fa-fw fa-list\";s:10:\"nav_active\";s:0:\"\";}', '', '', '', '0', 'no', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('31', 'site_nav', 'a:11:{s:8:\"nav_text\";s:9:\"孙导航\";s:10:\"nav_parent\";s:2:\"30\";s:10:\"nav_target\";s:6:\"_blank\";s:8:\"nav_type\";s:4:\"link\";s:7:\"nav_url\";s:28:\"http://www.daicuo.net/?2-1-1\";s:10:\"nav_module\";s:0:\"\";s:12:\"nav_controll\";s:0:\"\";s:10:\"nav_action\";s:0:\"\";s:10:\"nav_params\";s:0:\"\";s:7:\"nav_ico\";s:16:\"fa fa-fw fa-list\";s:10:\"nav_active\";s:0:\"\";}', '', '', '', '0', 'no', 'normal');
INSERT INTO `dc_op` (`op_id`, `op_name`, `op_value`, `op_module`, `op_controll`, `op_action`, `op_order`, `op_autoload`, `op_status`) VALUES ('32', 'site_hook', 'a:4:{s:9:\"hook_name\";s:18:\"admin_index_header\";s:9:\"hook_path\";s:23:\"app\\admin\\behavior\\Hook\";s:9:\"hook_info\";s:0:\"\";s:12:\"hook_overlay\";s:2:\"no\";}', 'common', 'hook', '', '0', 'no', 'normal');