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
class ContinuEdViewCourse extends JView
{
	/**
	 * display method of Hello view
	 * @return void
	 **/
	function display($tpl = null)
	{
		//get the hello
		$continued		=& $this->get('Data');
		$model =& $this->getModel();
		$prolist		= $model->getProList();
		$catlist		= $model->getCatList();
		$certlist = $model->getCertList();
		$courselist = $model->getCourseList();
		$isNew		= ($continued->id < 1);

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'ContinuEd' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$this->assignRef('continued',		$continued);
		$this->assignRef('prolist',		$prolist);
		$this->assignRef('catlist',		$catlist);
		$this->assignRef('certlist',		$certlist);
		$this->assignRef('courselist',		$courselist);
		$editor =& JFactory::getEditor();
		$this->assignref('editor',$editor);
		parent::display($tpl);
	}
}
