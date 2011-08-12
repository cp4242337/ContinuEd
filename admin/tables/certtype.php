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
class TableCertType extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $crt_id = null;

	/**
	 * @var string
	 */
	var $crt_name = null;



	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableCertType(& $db) {
		parent::__construct('#__ce_certifs', 'crt_id', $db);
	}
}
?>
