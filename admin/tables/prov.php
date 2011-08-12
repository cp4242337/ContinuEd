<?php
defined('_JEXEC') or die('Restricted access');

class TableProv extends JTable
{
	var $pid = null;

	var $pname = null;
	var $plogo = null;

	function TableProv(& $db) {
		parent::__construct('#__ce_providers', 'pid', $db);
	}
}
?>
