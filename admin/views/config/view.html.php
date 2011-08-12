<?php
/**
 * Hellos View for Hello World Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

/**
 * Hellos View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class ContinuEdViewConfig extends JView
{
	/**
	 * Hellos view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'ContinuEd Config' ), 'config.png' );
		JToolBarHelper::editListX();
		JToolBarHelper::custom('setDefault','restore','','Set Default',false);
		$model = $this->getModel('config');
		$cfg = $model->getConfig('edit','Edit Config');
		$this->assignRef('cfg',		$cfg);
		parent::display($tpl);
	}
}
