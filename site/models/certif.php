<?php
/**
 *
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 *
 */
class ContinuEdModelCertif extends JModel
{

	function getCourseInfo($courseid)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__ce_courses WHERE course_id = '.$courseid;
		$db->setQuery( $query );
		$fmtext = $db->loadObject();
		return $fmtext;
	}
	
	/**
	* Course list user status check.
	*
	* @param int $course Course id
	*
	* @return string user status for course.
	*
	* @since 1.20
	*/
	function statusCheck($course) {
		$cmpllist =ContinuEdHelper::completedList();
		$status=$cmpllist[$course];
		return $status;
	}
		
	/**
	* User Info 
	*
	* @return object of user data.
	*
	* @since 1.20
	*/
	function getUserInfo() {
		$user =& JFactory::getUser();
		$userid = $user->id;
		$db =& JFactory::getDBO();
		$query = 'SELECT userg_group FROM #__ce_usergroup WHERE userg_user="'.$userid.'"';
		$db->setQuery($query); $groupid=$db->loadResult();
		$user->group = $groupid;
		$qd = 'SELECT f.uf_sname,f.uf_type,u.usr_data FROM #__ce_uguf as g';
		$qd.= ' RIGHT JOIN #__ce_ufields as f ON g.uguf_field = f.uf_id';
		$qd.= ' RIGHT JOIN #__ce_users as u ON u.usr_field = f.uf_id && usr_user = '.$userid;
		$qd.= ' WHERE g.uguf_group='.$groupid;
		$db->setQuery( $qd ); 
		$udata = $db->loadObjectList();
		foreach ($udata as $u) {
			$fn=$u->uf_sname;
			if ($u->uf_type == 'multi' || $u->uf_type == 'dropdown' || $u->uf_type == 'mcbox') {
				$ansarr=explode(" ",$u->usr_data);
				$q = 'SELECT opt_text FROM #__ce_ufields_opts WHERE opt_id IN('.implode(",",$ansarr).')';
				$db->setQuery($q);
				$user->$fn = implode(", ",$db->loadResultArray());
			} else {
				$user->$fn=$u->usr_data;
			}
		}
		
		return $user;
	}
	
	function getCertif($group,$provider,$courseid,$defcert)
	{
		$db =& JFactory::getDBO();
		//determine which certif
		$q='SELECT gc_cert FROM #__ce_groupcerts WHERE gc_group = "'.$group.'"';
		$db->setQuery($q); 
		$usercert = $db->loadResult();
		if (!$this->checkDegree($courseid,$cn)) $usercert = $defcert;
		//get that certificate
		$query = 'SELECT * FROM #__ce_certiftempl WHERE ctmpl_cert = '.$usercert.' && ctmpl_prov = '.$provider;
		$db->setQuery( $query ); 
		$fmtext = $db->loadObject();
		return $fmtext;
	}

	function checkDegree($courseid,$usercert) {
		$coursecerts=$this->getCourseGroups($courseid);
		$cando = in_array($usercert,$coursecerts);
		return $cando;
	}
	
	function getCourseGroups($courseid)
	{
		$db =& JFactory::getDBO();
		$q='SELECT cd_cert FROM #__ce_coursecerts WHERE cd_course = "'.$courseid.'"';
		$db->setQuery($q);
		$cn = $db->loadResultArray();
		return $cn;
	}
	
	
}
