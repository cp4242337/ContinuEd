ALTER TABLE  `jos_ce_courses` CHANGE  `course_purchaselink`  `course_purchaseprice` FLOAT NOT NULL;

CREATE TABLE IF NOT EXISTS `jos_ce_purchased` (
  `purchase_id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_user` int(11) NOT NULL,
  `purchase_course` int(11) NOT NULL,
  `purchase_paypalid` varchar(255) NOT NULL,
  `purchase_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `purchase_ip` varchar(20) NOT NULL,
  `purchase_status` enum('notyetstarted','verified','canceled','accepted','pending','started','denied') NOT NULL,
  PRIMARY KEY (`purchase_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE  `jos_ce_courses` ADD  `course_purchaseinfo` TEXT NOT NULL AFTER  `course_purchasesku`;

CREATE TABLE IF NOT EXISTS `jos_ce_purchased_log` (
  `pl_id` int(11) NOT NULL AUTO_INCREMENT,
  `pl_pid` int(11) NOT NULL,
  `pl_user` int(11) NOT NULL,
  `pl_course` int(11) NOT NULL,
  `pl_resarray` text NOT NULL,
  `pl_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pl_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;