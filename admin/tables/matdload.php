<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
/**
 * @version		$Id: matdload.php 2012-05-24 $
 * @package		ContinuEd.Admin
 * @subpackage	matdload
 * @copyright	Copyright (C) 2012 Corona Productions.
 * @license		GNU General Public License version 2
 */

// import Joomla table library
jimport('joomla.database.table');

/**
 * ContinuEd Material Download Table
 *
 * @static
 * @package		ContinuEd.Admin
 * @subpackage	matdload
 * @since		1.0
 */
class ContinuEdTableMatDload extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) 
	{
		parent::__construct('#__ce_matdl', 'md_id', $db);
	}
	
	
	
}