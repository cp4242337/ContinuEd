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
		global $cecfg, $mainframe;
		//$params = $mainframe->getPageParameters();
		$cat = JRequest::getVar('cat');
		//if (empty($cat))$cat=$params->get('cat');
		$fmv = JRequest::getVar('fmv');
		$showfm = JRequest::getVar('showfm');
		$model =& $this->getModel();
		$user =& JFactory::getUser();
		$cert=NULL;
		$username = $user->guest ? 'Guest' : $user->name;
		$userid = $user->id;
		if ($username == 'Guest') $guest = true; else $guest=false;
		if ($fmv == 'true') {
			$model->viewedFM($userid,$cat);
			//prevent resubmit of form of refresh
			$mainframe->redirect('index.php?option=com_continued&view=continued&Itemid='.JRequest::getVar( 'Itemid' ).'&cat='.$cat);
		}
		$catalog=$model->getCatalog($guest,$cat);
		if ($cat != 0) $catinfo = $model->getCatInfo($cat);
		if (!$guest) {
			$uinfo=$model->getUserInfo();
			//$cert=$model->getCertifAssoc($uinfo['cb_degree']);
			$clist = $model->getCompletedList();
		}
		$this->assignRef('catalog',$catalog);
		$this->assignRef('clist',$clist);
		$this->assignRef('guest',$guest);
		$this->assignRef('cert',$cert);
		$this->assignRef('model',$model);
		$this->assignRef('showfm',$showfm);
		$this->assignRef('cat',$cat);
		$this->assignRef('catinfo',$catinfo);
		$this->assignRef('user',$user);
		if ($catinfo['cat_hasfm']) {
			if ((strtotime($catinfo['cat_end']."+ 1 day") <= strtotime("now")) && ($catinfo['cat_end'] != '0000-00-00')) $expired=true; else $expired = false;
			if (!$model->hasViewedFM($userid,$cat) || $showfm) {
				$this->assignRef('expired',$expired);
				$dispfm=true;
			} else {
				$dispfm=false;
				$model->viewedMenu($userid,$cat);
			}
		} else {
			$model->viewedMenu($userid,$cat);
			$dispfm=false;
		}
		if ($catinfo['cat_free'] == 0) {
			$bought=ContinuEdHelperCourse::SKUCheck($userid,$catinfo['cat_sku']);
			if (!$bought) $dispfm=true;
		} else { $bought=true; }
		if ($dispfm) $model->viewedDetails($userid,$cat);
		$this->assignRef('bought',$bought);
		$this->assignRef('dispfm',$dispfm);
		parent::display($tpl);
	}
}
?>
