CREATE TABLE IF NOT EXISTS `#__ce_cats` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_cattrack` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `cat` int(11) NOT NULL,
  `tdhit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `viewed` enum('fm','menu','det') NOT NULL,
  `sessionid` varchar(32) NOT NULL,
  `ct_ipaddr` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_certifs` (
  `crt_id` int(11) NOT NULL AUTO_INCREMENT,
  `crt_name` varchar(50) NOT NULL,
  PRIMARY KEY (`crt_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_certiftempl` (
  `ctmpl_id` int(11) NOT NULL AUTO_INCREMENT,
  `ctmpl_cert` int(11) NOT NULL,
  `ctmpl_prov` int(11) NOT NULL,
  `ctmpl_content` text NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ctmpl_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_courseauth` (
  `ca_id` int(11) NOT NULL AUTO_INCREMENT,
  `ca_course` int(11) NOT NULL,
  `ca_auth` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  PRIMARY KEY (`ca_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_coursecat` (
  `cc_id` int(11) NOT NULL AUTO_INCREMENT,
  `cc_course` int(11) NOT NULL,
  `cc_cat` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  PRIMARY KEY (`cc_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_coursecerts` (
  `cd_id` int(4) NOT NULL AUTO_INCREMENT,
  `cd_course` int(11) NOT NULL,
  `cd_cert` int(11) NOT NULL,
  PRIMARY KEY (`cd_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_courses` (
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
  `course_purchaseprice` float NOT NULL,
  `course_purchaseco` tinyint(1) NOT NULL DEFAULT '0',
  `course_purchaseinfo` text NOT NULL,
  `course_learntype` varchar(255) NOT NULL DEFAULT 'online enduring material',
  `course_hasinter` tinyint(1) NOT NULL DEFAULT '0',
  `course_qanda` enum('none','submit','panda','all') NOT NULL DEFAULT 'none',
  `course_nocredit` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`course_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_evalans` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_groupcerts` (
  `gc_id` int(11) NOT NULL AUTO_INCREMENT,
  `gc_group` int(11) NOT NULL,
  `gc_cert` int(11) NOT NULL,
  PRIMARY KEY (`gc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_matdl` (
  `md_id` int(11) NOT NULL AUTO_INCREMENT,
  `md_dload` int(11) NOT NULL,
  `md_mat` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  PRIMARY KEY (`md_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_material` (
  `mat_id` int(11) NOT NULL AUTO_INCREMENT,
  `mat_course` int(11) NOT NULL,
  `mat_title` varchar(255) NOT NULL,
  `mat_desc` text NOT NULL,
  `mat_content` text NOT NULL,
  `mat_type` enum('text','articulate','accordent') NOT NULL,
  `access` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  PRIMARY KEY (`mat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_matmed` (
  `mm_id` int(11) NOT NULL AUTO_INCREMENT,
  `mm_mat` int(11) NOT NULL,
  `mm_media` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  PRIMARY KEY (`mm_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_matuser` (
  `mu_mat` int(11) NOT NULL,
  `mu_user` int(11) NOT NULL,
  `mu_start` datetime NOT NULL,
  `mu_end` datetime NOT NULL,
  `mu_status` enum('complete','incomplete') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_parts` (
  `part_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `part_course` int(11) NOT NULL,
  `part_part` tinyint(4) NOT NULL,
  `part_name` varchar(255) NOT NULL,
  `part_desc` text NOT NULL,
  `part_area` enum('pre','post','inter') NOT NULL DEFAULT 'post',
  PRIMARY KEY (`part_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_prereqs` (
  `pr_id` int(11) NOT NULL AUTO_INCREMENT,
  `pr_course` int(11) NOT NULL,
  `pr_reqcourse` int(11) NOT NULL,
  PRIMARY KEY (`pr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_providers` (
  `prov_id` int(11) NOT NULL AUTO_INCREMENT,
  `prov_name` varchar(50) NOT NULL,
  `prov_logo` varchar(255) NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`prov_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_purchased` (
  `purchase_id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_user` int(11) NOT NULL,
  `purchase_course` int(11) NOT NULL,
  `purchase_type` enum('paypal','redeem','admin') NOT NULL,
  `purchase_transid` varchar(255) NOT NULL,
  `purchase_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `purchase_ip` varchar(20) NOT NULL,
  `purchase_status` enum('notyetstarted','verified','canceled','accepted','pending','started','denied','refunded','failed','pending','reversed','canceled_reversal','expired','voided','completed','dispute') NOT NULL,
  PRIMARY KEY (`purchase_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_purchased_codes` (
  `code_id` int(11) NOT NULL AUTO_INCREMENT,
  `code_code` varchar(10) NOT NULL,
  `code_course` int(11) NOT NULL,
  `code_limit` int(11) NOT NULL,
  PRIMARY KEY (`code_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_purchased_log` (
  `pl_id` int(11) NOT NULL AUTO_INCREMENT,
  `pl_pid` int(11) NOT NULL,
  `pl_user` int(11) NOT NULL,
  `pl_course` int(11) NOT NULL,
  `pl_resarray` text NOT NULL,
  `pl_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pl_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_questions` (
  `q_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `q_course` int(11) NOT NULL,
  `ordering` smallint(6) NOT NULL COMMENT 'qnum',
  `q_text` text NOT NULL,
  `q_type` enum('textar','textbox','multi','cbox','mcbox','yesno','dropdown','message') NOT NULL,
  `q_group` int(11) NOT NULL,
  `q_cat` enum('eval','assess','message') NOT NULL,
  `q_part` int(11) NOT NULL,
  `q_req` tinyint(1) NOT NULL DEFAULT '1',
  `q_depq` bigint(20) NOT NULL DEFAULT '0',
  `q_area` enum('pre','post','inter','qanda') NOT NULL DEFAULT 'post',
  `q_expl` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `q_addedby` int(11) NOT NULL DEFAULT '62',
  PRIMARY KEY (`q_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_questions_groups` (
  `qg_id` int(11) NOT NULL AUTO_INCREMENT,
  `qg_name` varchar(255) NOT NULL,
  PRIMARY KEY (`qg_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_questions_opts` (
  `opt_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `opt_question` bigint(20) NOT NULL,
  `opt_text` text NOT NULL,
  `opt_correct` tinyint(1) NOT NULL,
  `opt_expl` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`opt_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_records` (
  `rec_token` varchar(255) NOT NULL,
  `rec_user` int(11) NOT NULL,
  `rec_start` datetime NOT NULL,
  `rec_end` datetime NOT NULL,
  `rec_postscore` float NOT NULL,
  `rec_pass` enum('pass','fail','incomplete','audit','complete') NOT NULL,
  `rec_course` int(11) NOT NULL,
  `rec_recent` tinyint(1) NOT NULL DEFAULT '1',
  `rec_session` varchar(32) NOT NULL,
  `rec_prescore` int(11) DEFAULT '0',
  `rec_ipaddr` varchar(15) NOT NULL,
  `rec_type` enum('nonce','ce','review','expired','viewed') NOT NULL,
  `rec_laststep` varchar(10) NOT NULL,
  PRIMARY KEY (`rec_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_track` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `course` int(11) NOT NULL,
  `tdhit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `step` enum('fm','mt','qz','chk','asm','crt','vo','fmp','mtp','ans','rate','pre','lnk','fme','qaa','fmn','mtn') NOT NULL,
  `sessionid` varchar(32) NOT NULL,
  `token` varchar(32) NOT NULL,
  `track_ipaddr` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_ufields` (
  `uf_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uf_sname` varchar(50) NOT NULL,
  `ordering` smallint(6) NOT NULL COMMENT 'qnum',
  `uf_name` varchar(255) NOT NULL,
  `uf_type` enum('textar','textbox','multi','cbox','mcbox','yesno','dropdown','message','email','username','phone','password','mlist','birthday','captcha') NOT NULL,
  `uf_cms` tinyint(1) NOT NULL,
  `uf_req` tinyint(1) NOT NULL DEFAULT '1',
  `uf_note` text NOT NULL,
  `uf_match` varchar(50) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `uf_hidden` tinyint(1) NOT NULL DEFAULT '0',
  `uf_change` tinyint(1) NOT NULL DEFAULT '1',
  `uf_reg` tinyint(1) NOT NULL DEFAULT '1',
  `uf_profile` tinyint(1) NOT NULL DEFAULT '1',
  `uf_min` int(11) NOT NULL DEFAULT '0',
  `uf_max` int(11) NOT NULL DEFAULT '0',
  `uf_default` varchar(255) NOT NULL,
  PRIMARY KEY (`uf_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_ufields_opts` (
  `opt_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `opt_field` bigint(20) NOT NULL,
  `opt_text` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`opt_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_ugroups` (
  `ug_id` int(11) NOT NULL AUTO_INCREMENT,
  `ug_name` varchar(255) NOT NULL,
  `ug_desc` text NOT NULL,
  `ug_welcome_email` text NOT NULL,
  `ug_lostinfo_email` text NOT NULL,
  `access` int(11) NOT NULL,
  `published` tinyint(4) NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`ug_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_uguf` (
  `uguf_field` int(11) NOT NULL,
  `uguf_group` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_usergroup` (
  `userg_user` int(11) NOT NULL,
  `userg_group` int(11) NOT NULL,
  `userg_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userg_notes` text NOT NULL,
  PRIMARY KEY (`userg_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_users` (
  `usr_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `usr_user` int(11) NOT NULL,
  `usr_field` int(11) NOT NULL,
  `usr_data` text NOT NULL,
  PRIMARY KEY (`usr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `#__ce_ufields` (`uf_id`, `uf_sname`, `ordering`, `uf_name`, `uf_type`, `uf_cms`, `uf_req`, `uf_note`, `uf_match`, `published`, `uf_hidden`, `uf_change`, `uf_reg`, `uf_profile`, `uf_min`, `uf_max`, `uf_default`) VALUES
(1, 'fname', 2, 'First Name', 'textbox', 0, 1, '', '', 1, 0, 1, 1, 1, 0, 0, ''),
(2, 'lname', 3, 'Last Name', 'textbox', 0, 1, '', '', 1, 0, 1, 1, 1, 0, 0, ''),
(3, 'email', 4, 'Email Address', 'email', 1, 1, '', '', 1, 0, 0, 1, 1, 0, 0, ''),
(4, 'username', 6, 'Username', 'username', 1, 1, '', '', 1, 0, 0, 1, 1, 6, 0, ''),
(5, 'block', 7, 'Block User', 'yesno', 1, 1, '', '', 1, 1, 0, 0, 0, 0, 0, ''),
(6, 'cemail', 5, 'Confirm Email', 'email', 1, 1, '', 'email', 1, 0, 0, 1, 0, 0, 0, ''),
(7, 'password', 8, 'Password', 'password', 1, 1, '', '', 1, 0, 1, 1, 0, 0, 0, ''),
(8, 'cpassword', 9, 'Confirm Password', 'password', 1, 1, '', 'password', 1, 0, 1, 1, 1, 0, 0, '');
