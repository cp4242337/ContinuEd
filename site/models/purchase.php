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
	
	var $codeError= "";
	
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
	
	function redeemCode($cinfo,$code) {
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$qc = 'SELECT * FROM #__ce_purchased_codes WHERE code_code = "'.$code.'" && code_course IN (0,'.$cinfo->course_id.')';
		$db->setQuery( $qc );
		$codeinfo = $db->loadObject();
		if (!$codeinfo) {
			$this->codeError = 'Code invalid';
			return false;
		}
		if ($codeinfo->code_limit == 0) { 
			$this->codeError = 'Code use limit met';
			return false;
		} else if ($codeinfo->code_limit != -1) { 
			$qu = 'UPDATE #__ce_purchased_codes SET code_limit=code_limit-1 WHERE code_code = "'.$code.'" && code_course IN (0,'.$cinfo->course_id.')';
			$db->setQuery($qu); $db->query();
		}
		$q = 'INSERT INTO #__ce_purchased (purchase_user,purchase_course,purchase_status,purchase_type,purchase_transid,purchase_ip) VALUES ('.$user->id.','.$cinfo->course_id.',"completed","redeem","'.$code.'","'.$_SERVER['REMOTE_ADDR'].'")';
		$db->setQuery($q); $db->query();
		return true;
	}
	
}
