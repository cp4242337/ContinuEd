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
class TableCourseCert extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $cd_id = null;

	/**
	 * @var string
	 */
	var $course_id = null;
	var $cert_id = null;



	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableCourseCert(& $db) {
		parent::__construct('#__ce_coursecerts', 'cd_id', $db);
	}
}
?>
