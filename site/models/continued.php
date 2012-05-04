<?php
/**
 * @version		$Id: continued.php 2012-01-09 $
 * @package		ContinuEd.Site
 * @subpackage	continued
 * @copyright	Copyright (C) 2008 - 2012 Corona Productions.
 * @license		GNU General Public License version 2
 */


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * ContinuEd Catatlog Model
 *
 * @static
 * @package		ContinuEd.Site
 * @subpackage	continued
 * @since		always
 */
class ContinuEdModelContinuEd extends JModel
{

	/**
	* Course list 
	*
	* @param int $cat Category id 
	*
	* @return array course object list with user status.
	*
	* @since always
	*/
	function getCatalog($cat)
	{
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$query  = 'SELECT c.*,p.*';
		$query .= ',r.course_rating as catrating ';
		$query .= 'FROM #__ce_courses as c ';
		$query .= 'LEFT JOIN #__ce_providers as p ON c.course_provider = p.prov_id ';
		$query .= 'LEFT JOIN #__ce_courses as r ON c.course_catrate = r.course_id ';
		$query .= 'WHERE c.published = 1 && c.access IN ('.implode(",",$user->getAuthorisedViewLevels()).') ';
		if ($cat != 0) $query .= ' && c.course_cat = '.$cat;
		$query .= ' GROUP BY c.course_id ORDER BY c.ordering ASC';
		$db->setQuery( $query );
		$clist = $db->loadObjectList();
		$clist = $this->statusCheck($clist);
		return $clist;
	}
	
	/**
	* Course list user status check.
	*
	* @param array $clist Course object list
	*
	* @return array course object list with user status.
	*
	* @since 1.20
	*/
	function statusCheck($clist) {
		$cmpllist =ContinuEdHelper::completedList();
		foreach ($clist as &$c) {
			// expired
			if ((strtotime($c->course_enddate."+ 1 day") <= strtotime("now")) && ($c->course_enddate != '0000-00-00 00:00:00')) {
				$c->expired=true;
			} else {
				$c->expired=false;
			}
			// status
			$c->status=$cmpllist[$c->course_id]->rec_pass;
			// can take
			if (!$c->course_prereq || $c->expired) $c->cantake = true;
			else {
				$qp = 'SELECT pr_reqcourse FROM #__ce_prereqs WHERE pr_course = '.$c->course_id;
				$this->_db->setQuery($qp);
				$prlist = $this->_db->loadResultArray();
				$prm=true;
				foreach ($prlist as $p) {
					if ($cmpllist[$p]) {
						if ($cmpllist[$p]->rec_pass == 'incomplete' || $cmpllist[$p]->rec_pass == 'fail') $prm=false;
					} else {
						$prm=false;
					}
				} 	
				$c->cantake=$prm;
			}
			if ($c->status == 'pass' && !$c->course_hasfm && !$c->course_hasmat) $c->cantake = false;
			if ($c->expired && !$c->course_hasmat) $c->cantake = false;
			$c->type = $cmpllist[$c->course_id]->rec_type;
		}
		return $clist;
	}
	
	/**
	* Category Info 
	*
	* @param int $cat Category id 
	*
	* @return object object with cat info.
	*
	* @since always
	*/
	function getCatInfo($cat)
	{
		$db =& JFactory::getDBO();
		$q='SELECT * FROM #__ce_cats WHERE cat_id = "'.$cat.'"';
		$db->setQuery($q);
		$cn = $db->loadObject();
		return $cn;
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
				if ($res = $db->loadResultArray()) $user->$fn = implode(", ",$res);
				else $user->$fn = '';
			} else {
				$user->$fn=$u->usr_data;
			}
		}
		
		return $user;
	}
	
	/**
	* Mark FM Viewed 
	*
	* @param int $userid User id 
	* @param int $catid Category id 
	*
	* @since always
	*/
	function viewedFM($userid,$catid) {
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$db =& JFactory::getDBO();
		$query = 'INSERT INTO #__ce_cattrack (user,cat,viewed,sessionid,ct_ipaddr) VALUES ("'.$userid.'","'.$catid.'","fm","'.$sessionid.'","'.$_SERVER['REMOTE_ADDR'].'")';
		$db->setQuery( $query ); $db->query();
	}
	
	/**
	* Mark Menu Viewed 
	*
	* @param int $userid User id 
	* @param int $catid Category id 
	*
	* @since always
	*/
	function viewedMenu($userid,$catid) {
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$db =& JFactory::getDBO();
		$query = 'INSERT INTO #__ce_cattrack (user,cat,viewed,sessionid,ct_ipaddr) VALUES ("'.$userid.'","'.$catid.'","menu","'.$sessionid.'","'.$_SERVER['REMOTE_ADDR'].'")';
		$db->setQuery( $query ); $db->query();
	}
	
	/**
	* Mark Details Viewed 
	*
	* @param int $userid User id 
	* @param int $catid Category id 
	*
	* @since always
	*/
	function viewedDetails($userid,$catid) {
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$db =& JFactory::getDBO();
		$query = 'INSERT INTO #__ce_cattrack (user,cat,viewed,sessionid,ct_ipaddr) VALUES ("'.$userid.'","'.$catid.'","det","'.$sessionid.'","'.$_SERVER['REMOTE_ADDR'].'")';
		$db->setQuery( $query ); $db->query();
	}
	
	/**
	* Mark FM Viewed 
	*
	* @param int $userid User id 
	* @param int $catid Category id 
	*
	* @return boolean true if viewed, false if not.
	*
	* @since always
	*/
	function hasViewedFM($userid,$catid) {
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__ce_cattrack WHERE sessionid = "'.$sessionid.'" && viewed = "fm" && user="'.$userid.'" && cat = '.$catid;
		$db->setQuery( $query );
		$futext = $db->loadAssoc();
		if (count($futext) > 0) return true;
		else return false;
	}



}
