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
class TableCat extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $cid = null;

	/**
	 * @var string
	 */
	var $catname = null;
	var $catdesc = null;
	var $cathasfm = null;
	var $catfm = null;
	var $catsku = null;
	var $catfree = null;
	var $catskulink = null;
	var $catprev = null;
	var $catmenu = null;
	var $catfmlink = null;
	var $cat_start = null;
	var $cat_end = null;



	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableCat(& $db) {
		parent::__construct('#__ce_cats', 'cid', $db);
	}
}
?>
