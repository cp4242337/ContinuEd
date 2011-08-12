<?php
defined('_JEXEC') or die('Restricted access');


class TableCfgE extends JTable
{
	var $CFG_ID = null;
	var $FM_TEXT = null;
	var $EV_TEXT  = null;
	var $EVAL_PERCENT = null;
	var $EVAL_EXPL 	 = null;
	var $EVAL_REQD 	 = null;
	var $EVAL_ASSD 	 = null;
	var $EVAL_REQI 	 = null;
	var $EVAL_ASSI 	 = null;
	var $CAT_PROV 	 = null;
	var $CAT_GUEST 	 = null;
	var $NODEG_MSG 	 = null;
	var $EVAL_ANSRPT = null;
	var $REC_PRETXT = null;
	var $REC_POSTTXT = null;
	var $REC_TIT = null;
	var $SHOW_FAC = null;
	var $LOGIN_MSG = null;
	var $NEEDS_DEGREE = null;
	var $TEMPLATE = null;

	function TableCfgE(& $db) {
		parent::__construct('#__ce_config', 'CFG_ID', $db);
	}
}
?>
