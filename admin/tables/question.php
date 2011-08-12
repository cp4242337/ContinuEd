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
class TableQuestion extends JTable
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
	var $course = null;
	var $ordering = null;
	var $qtext = null;
	var $qtype = null;
	var $qcat = null;
	var $qsec = null;
	var $qreq = null;
	var $q_depq = null;
	var $q_area = null;
	var $q_expl = null;
	var $published = null;
	var $q_addedby = null;



	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableQuestion(& $db) {
		parent::__construct('#__ce_questions', 'id', $db);
	}
}
?>
