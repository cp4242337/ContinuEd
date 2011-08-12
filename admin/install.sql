CREATE TABLE `jos_ce_cats` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `catname` varchar(255) NOT NULL,
  `catdesc` text NOT NULL,
  `cathasfm` tinyint(1) NOT NULL DEFAULT '0',
  `catfm` text NOT NULL,
  `catsku` varchar(25) NOT NULL,
  `catfree` tinyint(1) NOT NULL DEFAULT '1',
  `catskulink` varchar(255) NOT NULL,
  `catprev` tinyint(1) NOT NULL DEFAULT '0',
  `catmenu` int(11) NOT NULL,
  `catfmlink` tinyint(1) NOT NULL DEFAULT '1',
  `cat_start` date NOT NULL,
  `cat_end` date NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `jos_ce_cattrack` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `cat` int(11) NOT NULL,
  `tdhit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `viewed` enum('fm','menu','det') NOT NULL,
  `sessionid` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `jos_ce_certifs` (
  `crt_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `crt_name` varchar(50) NOT NULL,
  PRIMARY KEY (`crt_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `jos_ce_certiftempl` (
  `ctmpl_id` int(11) NOT NULL AUTO_INCREMENT,
  `ctmpl_cert` int(11) NOT NULL,
  `ctmpl_prov` int(11) NOT NULL,
  `ctmpl_content` text NOT NULL,
  PRIMARY KEY (`ctmpl_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `jos_ce_completed` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `jos_ce_config` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `jos_ce_coursecerts` (
  `cd_id` int(4) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `cert_id` tinyint(4) NOT NULL,
  PRIMARY KEY (`cd_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `jos_ce_courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cname` varchar(255) NOT NULL,
  `keywords` text NOT NULL,
  `certifname` varchar(255) NOT NULL,
  `provider` int(11) NOT NULL,
  `cdesc` text NOT NULL,
  `frontmatter` text NOT NULL,
  `material` text NOT NULL,
  `startdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `enddate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ccat` int(11) NOT NULL,
  `evalparts` tinyint(4) NOT NULL DEFAULT '4',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `credits` varchar(100) NOT NULL,
  `faculty` varchar(255) NOT NULL,
  `actdate` date NOT NULL,
  `cneprognum` varchar(30) NOT NULL,
  `cpeprognum` varchar(255) NOT NULL,
  `dateadded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `previmg` varchar(255) NOT NULL,
  `prereq` int(11) NOT NULL DEFAULT '0',
  `ordering` smallint(6) NOT NULL,
  `hascertif` tinyint(1) NOT NULL DEFAULT '1',
  `cataloglink` varchar(255) NOT NULL,
  `hasfm` tinyint(1) NOT NULL DEFAULT '1',
  `hasmat` tinyint(1) NOT NULL DEFAULT '1',
  `defaultcertif` tinyint(4) NOT NULL DEFAULT '1',
  `haseval` tinyint(1) NOT NULL DEFAULT '1',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `course_catlink` tinyint(1) NOT NULL DEFAULT '0',
  `course_catmenu` int(11) NOT NULL,
  `course_catexp` tinyint(1) NOT NULL DEFAULT '0',
  `course_compcourse` int(11) NOT NULL DEFAULT '0',
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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `jos_ce_degreecert` (
  `dc_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `degree` varchar(25) NOT NULL,
  `cert` tinyint(4) NOT NULL,
  PRIMARY KEY (`dc_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `jos_ce_evalans` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `jos_ce_parts` (
  `part_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `part_course` int(11) NOT NULL,
  `part_part` tinyint(4) NOT NULL,
  `part_name` varchar(255) NOT NULL,
  `part_desc` text NOT NULL,
  `part_area` enum('pre','post','inter') NOT NULL DEFAULT 'post',
  PRIMARY KEY (`part_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `jos_ce_providers` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `pname` varchar(50) NOT NULL,
  `plogo` varchar(255) NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `jos_ce_questions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `course` int(11) NOT NULL,
  `ordering` smallint(6) NOT NULL COMMENT 'qnum',
  `qtext` text NOT NULL,
  `qtype` enum('textar','textbox','multi','cbox','mcbox','yesno','dropdown','qanda') NOT NULL,
  `qcat` enum('eval','assess','qanda') NOT NULL,
  `qsec` tinyint(4) NOT NULL,
  `qreq` tinyint(1) NOT NULL DEFAULT '1',
  `q_depq` bigint(20) NOT NULL DEFAULT '0',
  `q_area` enum('pre','post','inter','qanda') NOT NULL DEFAULT 'post',
  `q_expl` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `q_addedby` int(11) NOT NULL DEFAULT '62',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `jos_ce_questions_opts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `question` bigint(20) NOT NULL,
  `opttxt` text NOT NULL,
  `correct` tinyint(1) NOT NULL,
  `optexpl` text NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `jos_ce_track` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `course` int(11) NOT NULL,
  `tdhit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `step` enum('fm','mt','qz','chk','asm','crt','vo','fmp','mtp','ans','rate','pre','lnk','fme','qaa') NOT NULL,
  `sessionid` varchar(32) NOT NULL,
  `token` varchar(32) NOT NULL,
  `track_ipaddr` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
