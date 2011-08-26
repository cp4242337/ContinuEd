CREATE TABLE IF NOT EXISTS `jos_ce_cats` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL,
  `cat_desc` text NOT NULL,
  `cat_hasfm` tinyint(1) NOT NULL DEFAULT '0',
  `cat_fm` text NOT NULL,
  `cat_sku` varchar(25) NOT NULL,
  `cat_free` tinyint(1) NOT NULL DEFAULT '1',
  `cat_skulink` varchar(255) NOT NULL,
  `cat_prev` tinyint(1) NOT NULL DEFAULT '0',
  `cat_menu` int(11) NOT NULL,
  `cat_fmlink` tinyint(1) NOT NULL DEFAULT '1',
  `cat_start` datetime NOT NULL,
  `cat_end` datetime NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_ce_cattrack` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `cat` int(11) NOT NULL,
  `tdhit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `viewed` enum('fm','menu','det') NOT NULL,
  `sessionid` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_ce_certifs` (
  `crt_id` int(11) NOT NULL AUTO_INCREMENT,
  `crt_name` varchar(50) NOT NULL,
  PRIMARY KEY (`crt_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_ce_certiftempl` (
  `ctmpl_id` int(11) NOT NULL AUTO_INCREMENT,
  `ctmpl_cert` int(11) NOT NULL,
  `ctmpl_prov` int(11) NOT NULL,
  `ctmpl_content` text NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ctmpl_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_ce_completed` (
  `fid` bigint(20) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cpercent` float NOT NULL,
  `cpass` enum('pass','fail') NOT NULL,
  `course` int(11) NOT NULL,
  `crecent` tinyint(1) NOT NULL DEFAULT '1',
  `fsessionid` varchar(32) NOT NULL,
  `cmpl_prescore` int(11) DEFAULT '0',
  PRIMARY KEY (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_ce_config` (
  `CFG_ID` smallint(6) NOT NULL AUTO_INCREMENT,
  `FM_TEXT` text NOT NULL,
  `EV_TEXT` text NOT NULL,
  `EVAL_PERCENT` float NOT NULL,
  `EVAL_EXPL` tinyint(1) NOT NULL,
  `EVAL_REQD` tinyint(1) NOT NULL,
  `EVAL_ASSD` tinyint(1) NOT NULL,
  `EVAL_REQI` varchar(255) NOT NULL,
  `EVAL_ASSI` varchar(255) NOT NULL,
  `CAT_PROV` tinyint(1) NOT NULL,
  `CAT_GUEST` tinyint(1) NOT NULL,
  `NODEG_MSG` text NOT NULL,
  `EVAL_ANSRPT` tinyint(1) NOT NULL,
  `REC_TIT` varchar(255) NOT NULL,
  `REC_PRETXT` text NOT NULL,
  `REC_POSTTXT` text NOT NULL,
  `SHOW_FAC` tinyint(1) NOT NULL,
  `LOGIN_MSG` varchar(255) NOT NULL,
  `NEEDS_DEGREE` tinyint(4) NOT NULL DEFAULT '1',
  `TEMPLATE` varchar(50) NOT NULL DEFAULT 'default',
  `INTER_REQMSG` varchar(255) NOT NULL,
  PRIMARY KEY (`CFG_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_ce_coursecerts` (
  `cd_id` int(4) NOT NULL AUTO_INCREMENT,
  `cd_course` int(11) NOT NULL,
  `cd_cert` int(11) NOT NULL,
  PRIMARY KEY (`cd_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_ce_courses` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(255) NOT NULL,
  `course_keywords` text NOT NULL,
  `course_certifname` varchar(255) NOT NULL,
  `course_provider` int(11) NOT NULL,
  `course_desc` text NOT NULL,
  `course_frontmatter` text NOT NULL,
  `course_material` text NOT NULL,
  `course_startdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `course_enddate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `course_cat` int(11) NOT NULL,
  `course_postparts` tinyint(4) NOT NULL DEFAULT '4',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `course_credits` varchar(100) NOT NULL,
  `course_faculty` varchar(255) NOT NULL,
  `course_actdate` date NOT NULL,
  `course_cneprognum` varchar(30) NOT NULL,
  `course_cpeprognum` varchar(255) NOT NULL,
  `course_dateadded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `course_previmg` varchar(255) NOT NULL,
  `course_prereq` int(11) NOT NULL DEFAULT '0',
  `ordering` smallint(6) NOT NULL,
  `course_hascertif` tinyint(1) NOT NULL DEFAULT '1',
  `course_cataloglink` varchar(255) NOT NULL,
  `course_hasfm` tinyint(1) NOT NULL DEFAULT '1',
  `course_hasmat` tinyint(1) NOT NULL DEFAULT '1',
  `course_defaultcertif` tinyint(4) NOT NULL DEFAULT '1',
  `course_haseval` tinyint(1) NOT NULL DEFAULT '1',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `course_catlink` tinyint(1) NOT NULL DEFAULT '0',
  `course_catmenu` int(11) NOT NULL,
  `course_catexp` tinyint(1) NOT NULL DEFAULT '0',
  `course_catrate` int(11) NOT NULL DEFAULT '0',
  `course_viewans` tinyint(1) NOT NULL DEFAULT '0',
  `course_passmsg` text NOT NULL,
  `course_failmsg` text NOT NULL,
  `course_allowrate` tinyint(1) NOT NULL DEFAULT '0',
  `course_rating` float NOT NULL,
  `course_subtitle` varchar(255) NOT NULL,
  `course_searchable` tinyint(1) NOT NULL DEFAULT '1',
  `course_evaltype` enum('assess','unassess') NOT NULL DEFAULT 'assess',
  `course_extlink` tinyint(1) NOT NULL DEFAULT '0',
  `course_exturl` varchar(255) NOT NULL,
  `course_haspre` tinyint(1) NOT NULL DEFAULT '0',
  `course_preparts` int(11) NOT NULL DEFAULT '0',
  `course_changepre` tinyint(1) NOT NULL DEFAULT '0',
  `course_purchase` tinyint(1) NOT NULL DEFAULT '0',
  `course_purchaselink` varchar(255) NOT NULL,
  `course_purchasesku` varchar(255) NOT NULL,
  `course_learntype` varchar(255) NOT NULL DEFAULT 'online enduring material',
  `course_hasinter` tinyint(1) NOT NULL DEFAULT '0',
  `course_qanda` enum('none','submit','panda','all') NOT NULL DEFAULT 'none',
  PRIMARY KEY (`course_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_ce_degreecert` (
  `dc_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `degree` varchar(25) NOT NULL,
  `cert` tinyint(4) NOT NULL,
  PRIMARY KEY (`dc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_ce_evalans` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `course` int(11) NOT NULL,
  `question` bigint(20) NOT NULL,
  `part` tinyint(4) NOT NULL,
  `answer` text NOT NULL,
  `sessionid` varchar(32) NOT NULL,
  `tokenid` varchar(32) NOT NULL,
  `anstime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ans_area` enum('pre','post','inter') NOT NULL DEFAULT 'post',
  PRIMARY KEY (`id`),
  KEY `course` (`course`),
  KEY `question` (`question`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_ce_parts` (
  `part_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `part_course` int(11) NOT NULL,
  `part_part` tinyint(4) NOT NULL,
  `part_name` varchar(255) NOT NULL,
  `part_desc` text NOT NULL,
  `part_area` enum('pre','post','inter') NOT NULL DEFAULT 'post',
  PRIMARY KEY (`part_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_ce_providers` (
  `prov_id` int(11) NOT NULL AUTO_INCREMENT,
  `prov_name` varchar(50) NOT NULL,
  `prov_logo` varchar(255) NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`prov_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_ce_questions` (
  `q_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `q_course` int(11) NOT NULL,
  `ordering` smallint(6) NOT NULL COMMENT 'qnum',
  `q_text` text NOT NULL,
  `q_type` enum('textar','textbox','multi','cbox','mcbox','yesno','dropdown','message') NOT NULL,
  `q_cat` enum('eval','assess','message') NOT NULL,
  `q_part` int(11) NOT NULL,
  `q_req` tinyint(1) NOT NULL DEFAULT '1',
  `q_depq` bigint(20) NOT NULL DEFAULT '0',
  `q_area` enum('pre','post','inter','qanda') NOT NULL DEFAULT 'post',
  `q_expl` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `q_addedby` int(11) NOT NULL DEFAULT '62',
  PRIMARY KEY (`q_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_ce_questions_opts` (
  `opt_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `opt_question` bigint(20) NOT NULL,
  `opt_text` text NOT NULL,
  `opt_correct` tinyint(1) NOT NULL,
  `opt_expl` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`opt_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_ce_track` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `course` int(11) NOT NULL,
  `tdhit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `step` enum('fm','mt','qz','chk','asm','crt','vo','fmp','mtp','ans','rate','pre','lnk','fme','qaa') NOT NULL,
  `sessionid` varchar(32) NOT NULL,
  `token` varchar(32) NOT NULL,
  `track_ipaddr` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_ce_ufields` (
  `uf_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uf_sname` varchar(50) NOT NULL,
  `ordering` smallint(6) NOT NULL COMMENT 'qnum',
  `uf_name` varchar(255) NOT NULL,
  `uf_type` enum('textar','textbox','multi','cbox','mcbox','yesno','dropdown','message') NOT NULL,
  `uf_req` tinyint(1) NOT NULL DEFAULT '1',
  `uf_note` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`uf_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `jos_ce_ugroups` (
  `ug_id` int(11) NOT NULL AUTO_INCREMENT,
  `ug_name` varchar(255) NOT NULL,
  `access` int(11) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY (`ug_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;