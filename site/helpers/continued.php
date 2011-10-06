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
		$q = 'INSERT INTO #__ce_track	(user,course,step,sessionid,token,track_ipaddr) VALUES ("'.$userid.'","'.$course.'","'.$what.'","'.$sessionid.'","'.$token.'","'.$_SERVER['REMOTE_ADDR'].'")';
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
		$query = 'SELECT * FROM #__ce_track WHERE step="'.$what.'" && user="'.$userid.'" && sessionid="'.$sessionid.'" && token="'.$token.'" && course="'.$courseid.'"';
		$db->setQuery($query);
		$data = $db->loadAssoc();
		return count($data);
	}
}
