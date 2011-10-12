<?php
/**
 * Catalog View for ContinuEd Component
 *
 */

jimport( 'joomla.application.component.view');

class ContinuEdViewContinuEd extends JView
{
	function display($tpl = null)
	{
		global $cecfg;
		$app		= JFactory::getApplication();
		$dispatcher	= JDispatcher::getInstance();
		//$params = $app->getPageParameters();
		$cat = JRequest::getVar('cat');
		//if (empty($cat))$cat=$params->get('cat');
		$fmv = JRequest::getVar('fmv');
		$showfm = JRequest::getVar('showfm');
		$model =& $this->getModel();
		$user =& JFactory::getUser();
		$username = $user->guest ? 'Guest' : $user->name;
		$userid = $user->id;
		if ($username == 'Guest') $guest = true; else $guest=false;
		if ($fmv == 'true') {
			$model->viewedFM($userid,$cat);
			//prevent resubmit of form of refresh
			$app->redirect('index.php?option=com_continued&view=continued&Itemid='.JRequest::getVar( 'Itemid' ).'&cat='.$cat);
		}
		$catalog=$model->getCatalog($guest,$cat);
		if ($cat != 0) {
			$catinfo = $model->getCatInfo($cat);
			JPluginHelper::importPlugin('contined');
			$results = $dispatcher->trigger('onContentPrepare', array(&$catinfo->cat_desc));
		}
		if (!$guest) {
			$uinfo=$model->getUserInfo();
			
			$clist = $model->getCompletedList();
		}
		$this->catalog=$catalog;
		$this->clist=$clist;
		$this->guest=$guest;
		$this->model=$model;
		$this->showfm=$showfm;
		$this->cat=$cat;
		$this->catinfo=$catinfo;
		$this->user=$user;
		if ($catinfo->cat_hasfm) {
			if ((strtotime($catinfo->cat_end."+ 1 day") <= strtotime("now")) && ($catinfo->cat_end != '0000-00-00')) $expired=true; else $expired = false;
			if (!$model->hasViewedFM($userid,$cat) || $showfm) {
				$this->expired=$expired;
				$dispfm=true;
			} else {
				$dispfm=false;
				$model->viewedMenu($userid,$cat);
			}
		} else {
			$model->viewedMenu($userid,$cat);
			$dispfm=false;
		}
		if ($catinfo->cat_free == 0) {
			$bought=ContinuEdHelper::SKUCheck($userid,$catinfo->cat_sku);
			if (!$bought) $dispfm=true;
		} else { $bought=true; }
		if ($dispfm) $model->viewedDetails($userid,$cat);
		$this->bought=$bought;
		$this->dispfm=$dispfm;
		parent::display($tpl);
	}
}
?>
