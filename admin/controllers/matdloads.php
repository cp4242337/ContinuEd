<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
/**
 * @version		$Id: matdloads.php 2012-03-12 $
 * @package		ContinuEd.Admin
 * @subpackage	matdloads
 * @copyright	Copyright (C) 2012 Corona Productions.
 * @license		GNU General Public License version 2
 */

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * ContinuEd Material Downloads Controller
 *
 * @static
 * @package		ContinuEd.Admin
 * @subpackage	artdloads
 * @since		1.0
 */
class ContinuEdControllerMatDloads extends JControllerAdmin
{

	protected $text_prefix = "COM_CONTINUED_MATDLOAD";
	
	public function getModel($name = 'MatDload', $prefix = 'ContinuEdModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}