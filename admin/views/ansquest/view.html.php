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
class ContinuEdViewAnsQuest extends JView
{
	function display($tpl = null)
	{
		global $mainframe,$option;
		$qid = JRequest::getVar('question');
		$cid = JRequest::getVar('course');
		JToolBarHelper::title(   JText::_( 'ContinuEd Question Answers by Record' ), 'continued' );
		$tbar =& JToolBar::getInstance('toolbar');
		$tbar->appendButton('Link','archive','Export CSV','index.php?option=com_continued&controller=ansquest&task=csvme&question='.$qid.'&format=raw');
		JToolBarHelper::back('Questions','index.php?option=com_continued&view=questions&course='.$cid);
		// Get data from the model
		$model = $this->getModel('ansquest');
		$data = $model->getQInfo($qid);
		$qtype = $data->qtype;
		$items = $model->getResponses($qid,$qtype);

		$this->assignRef('data',		$data);
		$this->assignRef('items',		$items);
		parent::display($tpl);
	}
}
