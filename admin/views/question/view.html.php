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
class ContinuEdViewQuestion extends JView
{
	/**
	 * display method of Hello view
	 * @return void
	 **/
	function display($tpl = null)
	{
		//get the hello
		$courseid = JRequest::getVar('course');
		$question		=& $this->get('Data');
		$isNew		= ($question->id < 1);
		if ($isNew) $question->ordering = JRequest::getVar('nextnum');
		$model = $this->getModel('question');
		$user =& JFactory::getUser();
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		if ($isNew) $question->q_area=JRequest::getVar('area');
		if (!$question->q_addedby) $question->q_addedby=$user->id;
		JToolBarHelper::title(   JText::_( 'ContinuEd Question' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$numparts = $model->getNumParts($courseid,$question->q_area);
		$this->assignRef('question',		$question);
		$this->assignRef('courseid',$courseid);
		$this->assignRef('numparts',$numparts);
		$editor =& JFactory::getEditor();
		$this->assignref('editor',$editor);
		parent::display($tpl);
	}
}
