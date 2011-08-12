<?php
/**
 * Hello World table class
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


/**
 * Hello Table class
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class TableReport extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;

	/**
	 * @var string
	 */
	var $user = null;
	var $ctime = null;
	var $cpercent = null;
	var $cpass = null;
	var $course = null;
	var $crecent = null;



	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableReport(& $db) {
		parent::__construct('#__ce_completed', 'fid', $db);
	}
}
?>
