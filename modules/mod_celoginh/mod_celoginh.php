<?php
/**
 * @package		Continued.Site
 * @subpackage	mod_celogin
 * @copyright	Copyright (C) 2012 Corona Productions & Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

// Load ContinuEd helper
require_once('components'.DS.'com_continued'.DS.'helpers'.DS.'continued.php');

// Load StyleSheet for template, based on config
$cecfg = ContinuEdHelper::getConfig();
$doc = &JFactory::getDocument();
if ($cecfg->use_component_css) $doc->addStyleSheet('components'.DS.'com_continued'.DS.'continued.css');


$params->def('greeting', 1);

$type	= modCELoginhHelper::getType();
$return	= modCELoginhHelper::getReturnURL($params, $type);
$user	= JFactory::getUser();

require JModuleHelper::getLayoutPath('mod_celoginh', $params->get('layout', 'default'));
