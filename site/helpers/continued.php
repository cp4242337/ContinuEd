<?php
/**
 * @version		$Id: continued.php 2011-12-12 $
 * @package		ContinuEd.Site
 * @subpackage	com_continued
 * @copyright	Copyright (C) 2008 - 2011 Corona Productions.
 * @license		GNU General Public License version 2
 */
defined('_JEXEC') or die('Restricted access');

/**
 * ContinuEd Component Helper
 *
 * @static
 * @package		ContinuEd.Site
 * @subpackage	com_continued
 * @since		1.20
 */
class ContinuEdHelper {

	/**
	* Check if course access has been purchased. 
	*
	* @param int $courseid Course id number
	*
	* @return boolean true if purchased, false if not.
	*
	* @since 1.30
	*/
	function PurchaseCheck($courseid) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$q  = 'SELECT * FROM #__ce_purchased WHERE purchase_user = '.$userid.' && purchase_course = '.$courseid.' && purchase_status IN("accepted","completed")';
		$db->setQuery( $q );
		$pur = $db->loadObject();
		if ($pur->purchase_time) return true;
		return false;
	}
	
	/**
	* Get configuration for component.
	*
	* @return object The current config parameters
	*
	* @since 1.20
	*/
	function getConfig() {
		$ceConfig = JComponentHelper::getParams('com_continued'); 
		$cecfg = $ceConfig->toObject();
		return $cecfg;
	}
	
	/**
	* Set material page as started, incomplete.
	*
	* @param int $page Material page id number
	* @param string $tracktype Type of view
	*
	* @return boolean true if scucessfull, false if not.
	*
	* @since 1.20
	*/
	function trackMat($page,$tracktype) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$q  = 'INSERT INTO #__ce_mattrack (mt_user,mt_mat,mt_time,mt_session,mt_type,mt_ipaddr) ';
		$q .= 'VALUES ("'.$userid.'","'.$page.'",NOW(),"'.$sessionid.'","'.$tracktype.'","'.$_SERVER['REMOTE_ADDR'].'")';
		$db->setQuery( $q );
		if ($db->query()) return 1;
		else return 0;
	}
	
	/**
	* Set material page as started, incomplete.
	*
	* @param int $page Material page id number
	*
	* @return boolean true if scucessfull, false if not.
	*
	* @since 1.20
	*/
	function startMat($page) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$q  = 'INSERT INTO #__ce_matuser (mu_user,mu_mat,mu_start,mu_status) ';
		$q .= 'VALUES ("'.$userid.'","'.$page.'",NOW(),"incomplete")';
		$db->setQuery( $q );
		if ($db->query()) return 1;
		else return 0;
	}
	
	/**
	* Set material page as completed.
	*
	* @param int $page Material page id number
	*
	* @return boolean true if scucessfull, false if not.
	*
	* @since 1.20
	*/
	function endMat($page) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$q  = 'UPDATE #__ce_matuser SET mu_end = NOW(), mu_status="complete" WHERE mu_user = '.$userid.' && mu_mat = '.$page;
		$db->setQuery( $q );
		if ($db->query()) return 1;
		else return 0;
	}
	
	/**
	* Get material page status for user.
	*
	* @param int $page Material page id number
	*
	* @return object of users material page status.
	*
	* @since 1.20
	*/
	function checkMat($page) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$q  = 'SELECT * FROM #__ce_matuser  WHERE mu_user = '.$userid.' && mu_mat = '.$page;
		$db->setQuery( $q );
		return $db->loadObject();
	}
	
	/**
	* Mark step completed in current CE Session, set laststep in record.
	*
	* @param string $what Step
	* @param int $course Course id number
	* @param string $token CE Session token.
	*
	* @return boolean true if scucessfull, false if not.
	*
	* @since 1.20
	*/
	function trackViewed($what,$course,$token) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$q  = 'INSERT INTO #__ce_track (user,course,step,sessionid,token,track_ipaddr) ';
		$q .= 'VALUES ("'.$userid.'","'.$course.'","'.$what.'","'.$sessionid.'","'.$token.'","'.$_SERVER['REMOTE_ADDR'].'")';
		$db->setQuery( $q );
		$tracked = $db->query();
		$q2 = 'UPDATE #__ce_records SET rec_laststep = "'.$what.'" WHERE rec_token = "'.$token.'" && rec_user = "'.$userid.'"';
		$db->setQuery( $q2 );
		if ($db->query() && $tracked) return 1;
		else return 0;
	}
	
	/**
	* Check if step completed in current CE Session.
	*
	* @param string $what Step
	* @param int $course Course id number
	* @param string $token CE Session token.
	*
	* @return boolean true if scucessfull, false if not.
	*
	* @since 1.20
	*/
	function checkViewed($what,$course,$token) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query = 'SELECT * FROM #__ce_track WHERE step="'.$what.'" && user="'.$userid.'" && token="'.$token.'" && course="'.$course.'"';
		$db->setQuery($query);
		$data = $db->loadAssoc();
		return count($data);
	}
	
	/**
	* Start CE Session.
	*
	* @param int $course Course id number
	* @param string $token CE Session token.
	* @param string $type CE Session type: nonce,ce,review, or expired.
	*
	* @return boolean true if scucessfull, false if not.
	*
	* @since 1.20
	*/
	function startSession($course,$token,$type="ce") {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		if (!$userid) return 0;
		$recent=0;
		if ($type=='ce' || $type=='viewed') {
			$q1 = 'UPDATE #__ce_records SET rec_recent = 0 WHERE rec_user ="'.$userid.'" && rec_course = "'.$course.'"';
			$db->setQuery($q1);
			$db->query();
			$recent=1;
		}
		if ($type == 'ce') {
			$attempt=ContinuEdHelper::attemptCount($course);
			if ($attempt) $attempt = $attempt+1;
			else $attempt = 1;
		} else {
			$attempt=0;
		}
		$q = 'INSERT INTO #__ce_records (rec_user,rec_course,rec_start,rec_session,rec_token,rec_ipaddr,rec_recent,rec_pass,rec_type,rec_attempt) ';
		$q.= 'VALUES ("'.$userid.'","'.$course.'","'.date("Y-m-d H:i:s").'","'.$sessionid.'","'.$token.'","'.$_SERVER['REMOTE_ADDR'].'","'.$recent.'","incomplete","'.$type.'","'.$attempt.'")';
		$db->setQuery( $q );
		if ($db->query()) return 1;
		else return 0;
	}
	
	/**
	* Resume CE Session.
	*
	* @param int $course Course id number
	* @param string $token CE Session token.
	* @param string $type CE Session type: nonce,ce,review, or expired.
	*
	* @return boolean true if scucessfull, false if not.
	*
	* @since 1.20
	*/
	function resumeSession($token) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$q1 = 'UPDATE #__ce_records SET rec_session = "'.$sessionid.'" WHERE rec_user ="'.$userid.'" && rec_course = "'.$course.'"';
		$db->setQuery($q1);
		if ($db->query()) return 1;
		else return 0;
	}
	
	/**
	* End CE Session.
	*
	* @param int $course Course id number
	* @param string $token CE Session token.
	* @param float $prescore Pre test score
	* @param float $postscore Post test score
	* @param string $pass pass or fail based on score and pass percentage, audit if not a ce session
	*
	* @return boolean true if scucessfull, false if not.
	*
	* @since 1.20
	*/
	function endSession($course,$token,$prescore,$postscore,$pass) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$q = 'UPDATE #__ce_records SET rec_end = "'.date("Y-m-d H:i:s").'", rec_prescore = "'.$prescore.'", rec_postscore = "'.$postscore.'", rec_pass = "'.$pass.'" WHERE rec_token = "'.$token.'" && rec_user = "'.$userid.'"';
		$db->setQuery( $q );
		if ($db->query()) return 1;
		else return 0;
	}
	
	/**
	* User attempt count on course.
	*
	* @param int $course Course id number
	*
	* @return int of count.
	*
	* @since 1.35
	*/
	function attemptCount($course) {
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query = 'SELECT rec_attempt FROM #__ce_records WHERE rec_user = '.$userid.' && rec_course = '.$course.' ORDER BY rec_attempt DESC';
		$db =& JFactory::getDBO();
		$db->setQuery( $query );
		$ac = $db->loadResult();
		return $ac;
	}
	
	/**
	* Has user passed course.
	*
	* @param int $course Course id number
	*
	* @return boolean true if passed, false if not.
	*
	* @since 1.20
	*/
	function passedCourse($course) {
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query = 'SELECT * FROM #__ce_records WHERE rec_user = '.$userid.' && rec_pass IN ("pass","complete","flunked") && rec_course = '.$course;
		$db =& JFactory::getDBO();
		$db->setQuery( $query );
		$fmtext = $db->loadAssoc();
		if (count($fmtext) > 0) return true;
		else return false;
	}
	
	/**
	* Has user not completed the course.
	*
	* @param int $course Course id number
	*
	* @return session id if incomplete, 0 if no incomplete sessions
	*
	* @since 1.20
	*/
	function incompleteCourse($course) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query = 'SELECT rec_token FROM #__ce_records WHERE rec_user = '.$userid.' && rec_pass IN ("incomplete") && rec_course = '.$course.' && rec_type IN ("ce")  ORDER BY rec_start DESC';
		$db =& JFactory::getDBO();
		$db->setQuery( $query );
		$token = $db->loadResult();
		if ($token) return $token;
		else return false;
	}
	
	/**
	* Get users Course Completed list with status.
	*
	* @return Array list of completed courses courseid=>status.
	*
	* @since 1.20
	*/
	function completedList() {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query  = 'SELECT * ';
		$query .= 'FROM #__ce_records';
		$query .= ' WHERE rec_type IN ("ce") && rec_recent = 1 && rec_user = '.$userid;
		$db->setQuery( $query );
		$reclist = $db->loadObjectList();
		$rlist = array();
		foreach ($reclist as $r) {
			$rlist[$r->rec_course]=$r;
		}
		return $rlist;
	}
	
	/**
	* Get users Material Page list with all data.
	*
	* @param Array $matarr Array of Material page ids
	*
	* @return Array list of material pages data for user.
	*
	* @since 1.20
	*/
	function userMatData($matarr=0) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query  = 'SELECT * ';
		$query .= 'FROM #__ce_matuser ';
		$query .= 'WHERE mu_user = '.$userid;
		if ($matarr) $query .= ' && mu_mat IN ('.implode(",",$matarr).')';
		$db->setQuery( $query ); 
		$matlist = $db->loadObjectList();
		$mlist = array();
		foreach ($matlist as $m) {
			$mlist[$m->mu_mat]=$m;
		}
		return $mlist;
	}
	
	/**
	* User Info 
	*
	* @return object of user data.
	*
	* @since 1.20
	*/
	function getUserInfo($useids = false) {
		$user =& JFactory::getUser();
		$userid = $user->id;
		$db =& JFactory::getDBO();
		$query = 'SELECT ug.userg_group AS userGroupID, ug.userg_update AS lastUpdated, g.ug_name AS userGroupName FROM #__ce_usergroup as ug ';
		$query.= 'RIGHT JOIN #__ce_ugroups AS g ON ug.userg_group = g.ug_id ';
		$query.= 'WHERE ug.userg_user="'.$userid.'"';
		$db->setQuery($query); $groupdata=$db->loadObject();
		$user->userGroupID=$groupdata->userGroupID;
		$user->userGroupName=$groupdata->userGroupName;
		$user->lastUpdated=$groupdata->lastUpdated;
		$qd = 'SELECT f.uf_sname,f.uf_type,u.usr_data,f.uf_change FROM #__ce_uguf as g';
		$qd.= ' RIGHT JOIN #__ce_ufields as f ON g.uguf_field = f.uf_id';
		$qd.= ' RIGHT JOIN #__ce_users as u ON u.usr_field = f.uf_id && usr_user = '.$userid;
		$qd.= ' WHERE g.uguf_group='.$user->userGroupID;
		$db->setQuery( $qd ); 
		$udata = $db->loadObjectList();
		foreach ($udata as $u) {
			if (!$u->uf_cms) {
				$fn=$u->uf_sname;
				if ($u->uf_type == 'multi' || $u->uf_type == 'dropdown' || $u->uf_type == 'mcbox' || $u->uf_type == 'mlist') {
					if ($useids && $u->uf_change) {
						$user->$fn=explode(" ",$u->usr_data);
					} else { 
						if ($u->usr_data) {
							$ansarr=explode(" ",$u->usr_data);
							$q = 'SELECT opt_text FROM #__ce_ufields_opts WHERE opt_id IN('.implode(",",$ansarr).')';
							$db->setQuery($q);
							$user->$fn = implode(", ",$db->loadResultArray());
						} else {
							$user->$fn = "";
						}
					}
				} else if ($u->uf_type == 'cbox' || $u->uf_type == 'yesno') {
					if ($useids && $u->uf_change) $user->$fn=$u->usr_data;
					else $user->$fn = ($u->usr_data == "1") ? "Yes" : "No";
				} else if ($u->uf_type == 'birthday') {
					if ($useids && $u->uf_change) $user->$fn=$u->usr_data;
					else $user->$fn = date("F j",strtotime('2000-'.substr($u->usr_data,0,2)."-".substr($u->usr_data,2,2).''));
				} else{
					$user->$fn=$u->usr_data;
				}
			}
		}
		return $user;
	}
	
	/**
	* User Group 
	*
	* @return object of users group.
	*
	* @since 1.20
	*/
	function getUserGroup($userid = 0) {
		if (!$userid) {
			$user =& JFactory::getUser();
			$userid = $user->id;
		}
		$db =& JFactory::getDBO();
		$query = 'SELECT ug.userg_group AS userGroupID, ug.userg_update AS lastUpdated, g.* FROM #__ce_usergroup as ug ';
		$query.= 'RIGHT JOIN #__ce_ugroups AS g ON ug.userg_group = g.ug_id ';
		$query.= 'WHERE ug.userg_user="'.$userid.'"';
		$db->setQuery($query);
		$usergroup = $db->loadObject();
		return $usergroup;
	}
}
