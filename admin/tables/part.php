<?php
defined('_JEXEC') or die('Restricted access');
class TablePart extends JTable
{
	var $part_id = null;
	var $part_course = null;
	var $part_part = null;
	var $part_name = null;
	var $part_desc = null;
	var $part_area = null;

	function TablePart(& $db) {
		parent::__construct('#__ce_parts', 'part_id', $db);
	}
}
?>
