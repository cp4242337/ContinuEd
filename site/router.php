<?php
/**
 * @package		ContinuEd.Site
 * @subpackage	com_continued
 * @copyright	Copyright (C) 2012Corona Productions, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Function to build a ContinuEd URL route.
 *
 * @param	array	The array of query string values for which to build a route.
 * @return	array	The URL route with segments represented as an array.
 * @since	1.20
 */
function ContinuEdBuildRoute(&$query)
{
	// Declare static variables.
	static $items;
	static $default;
	static $userreg;
	static $profile;
	static $proedit;
	static $cerecords;
	static $login;
	static $logout;
	static $lost;
	static $continued;

	// Initialise variables.
	$segments = array();

	// Get the relevant menu items if not loaded.
	if (empty($items)) {
		// Get all relevant menu items.
		$app	= JFactory::getApplication();
		$menu	= $app->getMenu();
		$items	= $menu->getItems('component', 'com_continued');

		// Build an array of serialized query strings to menu item id mappings.
		for ($i = 0, $n = count($items); $i < $n; $i++) {
			// Check to see if we have found the registration menu item.
			if (empty($userreg) && !empty($items[$i]->query['view']) && ($items[$i]->query['view'] == 'userreg')) {
				$userreg = $items[$i]->id;
			}

			// Check to see if we have found the log in/out menu item.
			if (empty($login) && !empty($items[$i]->query['view']) && ($items[$i]->query['view'] == 'login') && !empty($items[$i]->query['layout']) && ($items[$i]->query['layout'] == 'login')) {
				$login = $items[$i]->id;
			}
			if (empty($logout) && !empty($items[$i]->query['view']) && ($items[$i]->query['view'] == 'login') && !empty($items[$i]->query['layout']) && ($items[$i]->query['layout'] == 'logout')) {
				$logout = $items[$i]->id;
			}
			
			// Check to see if we have found the profile,profile edit, and records menu item.
			if (empty($profile) && !empty($items[$i]->query['view']) && ($items[$i]->query['view'] == 'user') && !empty($items[$i]->query['layout']) && ($items[$i]->query['layout'] == 'profile')) {
				$profile = $items[$i]->id;
			}
			if (empty($proedit) && !empty($items[$i]->query['view']) && ($items[$i]->query['view'] == 'user') && !empty($items[$i]->query['layout']) && ($items[$i]->query['layout'] == 'proedit')) {
				$peoedit = $items[$i]->id;
			}
			if (empty($cerecords) && !empty($items[$i]->query['view']) && ($items[$i]->query['view'] == 'user') && !empty($items[$i]->query['layout']) && ($items[$i]->query['layout'] == 'cerecords')) {
				$cerecords = $items[$i]->id;
			}
			
			// Check to see if we have found the lost info menu item.
			if (empty($lost) && !empty($items[$i]->query['view']) && ($items[$i]->query['view'] == 'lost')) {
				$lost = $items[$i]->id;
			}
			
			// Check for catalog page
			if (empty($continued) && !empty($items[$i]->query['view']) && ($items[$i]->query['view'] == 'continued') && !empty($items[$i]->query['cat']) && ($items[$i]->query['cat'] == $query['cat'])) {
				$continued = $items[$i]->id;
			}

			
			
		}

		/* Set the default menu item to use for com_continued if possible.
		if ($cat) {
			$default = $cat;
		} elseif ($userreg) {
			$default = $userreg;
		} elseif ($login) {
			$default = $login;
		} elseif ($logout) {
			$default = $logout;
		} elseif ($profile) {
			$default = $profile;
		} elseif ($proedit) {
			$default = $proedit;
		} elseif ($cerecords) {
			$default = $cerecords;
		} elseif ($lost) {
			$default = $lost;
		}*/
	}

	if (!empty($query['view'])) {
		switch ($query['view']) {
			case 'continued':
				if ($query['Itemid'] = $continued) {
					unset ($query['view']);
					unset ($query['cat']);
				} else {
					$query['Itemid'] = $default;
				}
				break;

			case 'userreg':
				if ($query['Itemid'] = $userreg) {
					unset ($query['view']);
				} else {
					$query['Itemid'] = $default;
				}
				break;

			case 'login':
				switch ($query['layout']) {
					case 'login':
						if ($query['Itemid'] = $login) {
							unset ($query['view']);
							unset ($query['layout']);
						} else {
							$query['Itemid'] = $default;
						}
						break;

					case 'logout':
						if ($query['Itemid'] = $logout) {
							unset ($query['view']);
							unset ($query['layout']);
						} else {
							$query['Itemid'] = $default;
						}
						break;
				}
				break;

			case 'user':
				switch ($query['layout']) {
					case 'profile':
						if ($query['Itemid'] = $profile) {
							unset ($query['view']);
							unset ($query['layout']);
						} else {
							$query['Itemid'] = $default;
						}
						break;
		
					case 'proedit':
						if ($query['Itemid'] = $proedit) {
							unset ($query['view']);
							unset ($query['layout']);
						} else {
							$query['Itemid'] = $default;
						}
						break;
		
					case 'cerecords':
						if ($query['Itemid'] = $cerecords) {
							unset ($query['view']);
							unset ($query['layout']);
						} else {
							$query['Itemid'] = $default;
						}
						break;
				}
				break;

			case 'lost':
				if ($query['Itemid'] = $lost) {
					unset ($query['view']);
				} else {
					$query['Itemid'] = $default;
				}
				break;

			default:
				break;
		}
	}

	return $segments;
}

/**
 * Function to parse a ContinuEd URL route.
 *
 * @param	array	The URL route with segments represented as an array.
 * @return	array	The array of variables to set in the request.
 * @since	1.20
 */
function ContinuEdParseRoute($segments)
{
	// Initialise variables.
	$vars = array();

	// Only run routine if there are segments to parse.
	if (count($segments) < 1) {
		return;
	}
/*
	// Get the package from the route segments.
	$userId = array_pop($segments);

	if (!is_numeric($userId)) {
		$vars['view'] = 'profile';
		return $vars;
	}

	if (is_numeric($userId)) {
		// Get the package id from the packages table by alias.
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT '.$db->quoteName('id') .
			' FROM '.$db->quoteName('#__users') .
			' WHERE '.$db->quoteName('id').' = '.(int) $userId
		);
		$userId = $db->loadResult();
	}

	// Set the package id if present.
	if ($userId) {
		// Set the package id.
		$vars['user_id'] = (int)$userId;

		// Set the view to package if not already set.
		if (empty($vars['view'])) {
			$vars['view'] = 'profile';
		}
	} else {
		JError::raiseError(404, JText::_('JGLOBAL_RESOURCE_NOT_FOUND'));
	}*/

	return $vars;
}
