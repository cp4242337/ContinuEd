<?php
/**
 * @version		$Id: matpage.php 2011-12-12 $
 * @package		ContinuEd.Site
 * @subpackage	matpage
 * @copyright	Copyright (C) 2008 - 2011 Corona Productions.
 * @license		GNU General Public License version 2
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * ContinuEd MatPage Model
 *
 * @static
 * @package		ContinuEd.Site
 * @subpackage	matpage
 * @since		1.20
 */
class ContinuEdModelMatpage extends JModel
{

	/**
	* Get matpage information.
	*
	* @param int $pageid Matpage id number
	*
	* @return object of matpage info.
	*
	* @since 1.20
	*/
	function getMatPage($pageid)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__ce_material WHERE mat_id = '.$pageid;
		$db->setQuery( $query );
		$mtext = $db->loadObject();
		return $mtext;
	}

}
