<?php

defined('_JEXEC') or die('Restricted access');

class ContinuEdHelperCourse {

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

}
