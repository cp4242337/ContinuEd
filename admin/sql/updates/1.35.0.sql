CREATE TABLE IF NOT EXISTS `#__ce_questions_tags` (
  `qt_id` int(11) NOT NULL AUTO_INCREMENT,
  `qt_name` varchar(255) NOT NULL,
  PRIMARY KEY (`qt_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ce_questiontags` (
  `qtag_id` int(11) NOT NULL AUTO_INCREMENT,
  `qtag_q` int(11) NOT NULL,
  `qtag_tag` int(11) NOT NULL,
  PRIMARY KEY (`qtag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE  `#__ce_records` ADD  `rec_attempt` int(11) NOT NULL DEFAULT '0' AFTER  `rec_recent`;
ALTER TABLE  `#__ce_questions` ADD    `checked_out` int(11) NOT NULL DEFAULT '0';
ALTER TABLE  `#__ce_questions` ADD   `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE  `#__ce_courses` ADD    `checked_out` int(11) NOT NULL DEFAULT '0';
ALTER TABLE  `#__ce_courses` ADD   `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00';

ALTER TABLE  `#__ce_records` CHANGE  `rec_pass`  `rec_pass` ENUM(  'pass',  'fail',  'incomplete',  'audit',  'complete',  'flunked' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE  `#__ce_courses` ADD  `course_materialintro` TEXT NOT NULL AFTER  `course_frontmatter`