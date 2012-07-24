<?php
/**
 * @version		$Id: continued.php 2012-07-24 $
 * @package		ContinuEd.Site
 * @subpackage	purchase
 * @copyright	Copyright (C) 2008 - 2012 Corona Productions.
 * @license		GNU General Public License version 2
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * ContinuEd Purchase Model
 *
 * @static
 * @package		ContinuEd.Site
 * @subpackage	purchase
 * @since		always
 */
class ContinuEdModelPurchase extends JModel
{

	/**
	* Course Info 
	*
	* @param int $cid Course id 
	*
	* @return course object.
	*
	* @since always
	*/
	function getCourseInfo($cid)
	{
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$query  = 'SELECT c.*';
		$query .= 'FROM #__ce_courses as c ';
		$query .= 'WHERE c.published = 1 && c.access IN ('.implode(",",$user->getAuthorisedViewLevels()).') ';
		$query .= ' && c.course_id = '.$cid;
		$db->setQuery( $query );
		return $db->loadObject();
	}
	
}
