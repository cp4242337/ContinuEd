<?php

defined('_JEXEC') or die('Restricted access');

class ContinuEdHelper {

	function SKUCheck($userid,$skunum) {
		//for naadac
		$db =& JFactory::getDBO();
		$chksout=false;
		$query =  "SELECT jo.date_purchased, jop.products_id, jop.products_model, DATEDIFF(NOW(),jo.date_purchased)"
		. "FROM #__osc_orders as jo, #__osc_orders_products as jop "
		. "WHERE customers_id=" . $userid . " "
		. "AND jo.orders_id = jop.orders_id "
		. "ORDER BY jo.date_purchased DESC";
		$db->setQuery($query);
		$res=$db->loadAssocList();
		if ($res) {
			foreach ($res as $r) {
				$f3 = $r['products_model'];
				if ($f3 == $skunum) {
					$chksout=true;
				}
			}
		} else { $chksout=false; }
		return $chksout;
	}

	function PurchaseCheck($userid,$courseid) {
		if (method_exists('AmbraSubsHelperCourse', 'hasPurchased')) {
			return AmbraSubsHelperCourse::hasPurchased($courseid,$userid);
		} else { return false; }
	}
	
	function getConfig() {
		$db =& JFactory::getDBO();
		$q = 'SELECT * FROM #__ce_config';
		$db->setQuery($q);
		global $cecfg;
		$cecfg = $db->loadObject();
		return $cecfg;
	}
	
	function trackViewed($what,$course,$token) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$q = 'INSERT INTO #__ce_track (user,course,step,sessionid,token,track_ipaddr) VALUES ("'.$userid.'","'.$course.'","'.$what.'","'.$sessionid.'","'.$token.'","'.$_SERVER['REMOTE_ADDR'].'")';
		$db->setQuery( $q );
		if ($db->query()) return 1;
		else return 0;
	}
	
	function checkViewed($what,$course,$token) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query = 'SELECT * FROM #__ce_track WHERE step="'.$what.'" && user="'.$userid.'" && sessionid="'.$sessionid.'" && token="'.$token.'" && course="'.$course.'"';
		$db->setQuery($query);
		$data = $db->loadAssoc();
		return count($data);
	}
	
	/**
	* Start CE Session.
	*
	* @param int $course Course id number
	* @param string $token CE Session token.
	*
	* @return boolean true if scucessfull, false if not.
	*
	* @since 1.20
	*/
	function startSession($course,$token) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$q1 = 'UPDATE #__ce_records SET rec_recent = 0 WHERE rec_user ="'.$userid.'" && rec_course = "'.$course.'"';
		$db->setQuery($q1);
		$db->query();
		$q = 'INSERT INTO #__ce_records (rec_user,rec_course,rec_start,rec_session,rec_token,rec_ipaddr,rec_recent,rec_pass) VALUES ("'.$userid.'","'.$course.'","'.date("Y-m-d H:i:s").'","'.$sessionid.'","'.$token.'","'.$_SERVER['REMOTE_ADDR'].'",1,"incomplete")';
		$db->setQuery( $q );
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
	* @param string $pass pass or fail based on score and pass percentage
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
}
