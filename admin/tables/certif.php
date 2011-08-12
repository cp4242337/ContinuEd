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
class TableCertif extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $ctmpl_id = null;

	/**
	 * @var string
	 */
	var $ctmpl_cert = null;
	var $ctmpl_prov = null;
	var $ctmpl_content = null;



	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableCertif(& $db) {
		parent::__construct('#__ce_certiftempl', 'ctmpl_id', $db);
	}
}
?>
