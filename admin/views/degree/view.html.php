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
class ContinuEdViewDegree extends JView
{
	/**
	 * display method of Hello view
	 * @return void
	 **/
	function display($tpl = null)
	{
		global $mainframe;
		$model = $this->getModel('degree');
		$degree = JRequest::getVar('degree');
		$certifs = $model->getCertifs();
		$cert = $model->getCurrCertif($degree);
		$isNew = ($cert < 1);
		$certa = JRequest::getVar('certa');
		$text = $isNew ? JText::_( 'Assign' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'ContinuEd Certificate Degrees' ).': <small><small>[ ' . $text.' ]</small></small>' );
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$this->assignRef('degree',		$degree);
		$this->assignRef('cert',		$cert);
		$this->assignRef('certifs',		$certifs);
		if (JRequest::getVar('edit')) {
			$model->store($degree,$certa);
			$msg = 'Certificate Degree Changed';
			$link = 'index.php?option=com_continued&view=degrees';
			$mainframe->redirect($link, $msg);
		}
		else parent::display($tpl);
	}
}
