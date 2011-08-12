<?php
/**
 * Hello View for Hello World Component
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
 * Hello View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class ContinuEdViewAnswer extends JView
{
	/**
	 * display method of Hello view
	 * @return void
	 **/
	function display($tpl = null)
	{
		//get the hello
		$questionid = JRequest::getVar('question');
		$courseid = JRequest::getVar('course');
		$answer		=& $this->get('Data');
		$isNew		= ($answer->id < 1);
		if ($isNew) $answer->ordering = JRequest::getVar('nextnum');

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'ContinuEd Answer' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$this->assignRef('answer',		$answer);
		$this->assignRef('questionid',$questionid);
		$this->assignRef('courseid',$courseid);
		$editor =& JFactory::getEditor();
		$this->assignref('editor',$editor);
		parent::display($tpl);
	}
}
