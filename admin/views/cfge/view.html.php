<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class ContinuEdViewCfgE extends JView
{
	function display($tpl = null)
	{
		$answer		=& $this->get('Data');
		$text = JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'ContinuEd Configuration' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		$this->assignRef('answer',		$answer);
		$editor =& JFactory::getEditor();
		$this->assignref('editor',$editor);
		parent::display($tpl);
	}
}
