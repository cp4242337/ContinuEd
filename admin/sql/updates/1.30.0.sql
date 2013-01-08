ALTER TABLE  `jos_ce_courses` CHANGE  `course_purchaselink`  `course_purchaseprice` FLOAT NOT NULL;
ALTER TABLE  `jos_ce_courses` CHANGE  `course_purchasesku`  `course_purchaseco` BOOLEAN NOT NULL DEFAULT  '0';
ALTER TABLE  `jos_ce_courses` ADD  `course_purchaseinfo` TEXT NOT NULL AFTER  `course_purchasesku`;

CREATE TABLE IF NOT EXISTS `jos_ce_purchased` (
  `purchase_id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_user` int(11) NOT NULL,
  `purchase_course` int(11) NOT NULL,
  `purchase_type` enum('paypal','redeem','admin','google') NOT NULL,
  `purchase_transid` varchar(255) NOT NULL,
  `purchase_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `purchase_ip` varchar(20) NOT NULL,
  `purchase_status` enum('notyetstarted','verified','canceled','accepted','pending','started','denied','refunded','failed','pending','reversed','canceled_reversal','expired','voided','completed','dispute') NOT NULL,
  PRIMARY KEY (`purchase_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_ce_purchased_log` (
  `pl_id` int(11) NOT NULL AUTO_INCREMENT,
  `pl_pid` int(11) NOT NULL,
  `pl_user` int(11) NOT NULL,
  `pl_course` int(11) NOT NULL,
  `pl_resarray` text NOT NULL,
  `pl_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pl_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_ce_purchased_codes` (
  `code_id` int(11) NOT NULL AUTO_INCREMENT,
  `code_code` varchar(10) NOT NULL,
  `code_limit` int(11) NOT NULL,
  PRIMARY KEY (`code_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_ce_mattrack` (
  `mt_mat` int(11) NOT NULL,
  `mt_user` int(11) NOT NULL,
  `mt_time` datetime NOT NULL,
  `mt_session` varchar(255) NOT NULL,
  `mt_type` VARCHAR( 10 ) NOT NULL,
  `mt_ipaddr` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
