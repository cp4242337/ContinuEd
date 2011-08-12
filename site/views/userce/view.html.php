<?php
/**
 * Catalog View for ContinuEd Component
 *
 */

jimport( 'joomla.application.component.view');

class ContinuEdViewUserCE extends JView
{
	function display($tpl = null)
	{
		global $cecfg;
		$print = JRequest::getVar('print');
		$model =& $this->getModel();
		$user =& JFactory::getUser();
		$cert=NULL;
		$userid = $user->id;
		$catalog=$model->getCatalog($userid);
		if ($userid != 0) {
			$this->assignRef('print',$print);
			$this->assignRef('catalog',$catalog);
			$this->assignRef('userid',$userid);
			parent::display($tpl);
		}
	}
}
?>
