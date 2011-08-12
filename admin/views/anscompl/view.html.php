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
class ContinuEdViewAnsCompl extends JView
{
	function display($tpl = null)
	{
		global $mainframe,$option;
		JToolBarHelper::title(   JText::_( 'ContinuEd Question Answers by Record' ), 'continued' );
		JToolBarHelper::back('Reports','index.php?option=com_continued&view=reports');
		$fid = JRequest::getVar('fid');
		$course = JRequest::getVar('course');
		// Get data from the model
		$model = $this->getModel('anscompl');
		$cinfo = $model->getCourseInfo($course); $this->assignRef('cinfo',$cinfo);
		if ($cinfo->haseval) { $postq = $model->getQuestionsPost($fid); $this->assignRef('postq',$postq); }
		if ($cinfo->course_haspre) { $preq = $model->getQuestionsPre($fid);	$this->assignRef('preq',$preq); }
		parent::display($tpl);
	}
}
