<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
/**
 * @version		$Id: matdload.php 2012-05-24 $
 * @package		Continued.Admin
 * @subpackage	matdload
 * @copyright	Copyright (C) 2012 Corona Productions.
 * @license		GNU General Public License version 2
 */

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * Continued Material Download Edit Controller
 *
 * @static
 * @package		Continued.Admin
 * @subpackage	matdload
 * @since		1.0
 */
class ContinuEdControllerMatDload extends JControllerForm
{
	protected $text_prefix = "COM_CONTINUED_MATDLOAD";
}
