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
class TableAnswer extends JTable
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
	var $question = null;
	var $opttxt = null;
	var $correct = null;
	var $optexpl = null;
	var $ordering = null;



	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableAnswer(& $db) {
		parent::__construct('#__ce_questions_opts', 'id', $db);
	}
}
?>
