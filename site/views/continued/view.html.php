<?php
/**
 * @version		$Id: view.html.php 2012-01-09 $
 * @package		ContinuEd.Site
 * @subpackage	continued
 * @copyright	Copyright (C) 2008 - 2012 Corona Productions.
 * @license		GNU General Public License version 2
 */

jimport( 'joomla.application.component.view');

/**
 * ContinuEd Catalog Page View
 *
 * @static
 * @package		ContinuEd.Site
 * @subpackage	continued
 * @since		always
 */
class ContinuEdViewContinuEd extends JView
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$dispatcher	= JDispatcher::getInstance();
		$model =& $this->getModel();
		$user =& JFactory::getUser();
		
		//Get vars
		$cat = JRequest::getVar('cat');
		$fmv = JRequest::getVar('fmv');
		$showfm = JRequest::getVar('showfm',false);
		
		//Set FM Agreed to for Cat based Course
		if ($fmv == 'true') {
			$model->viewedFM($user->id,$cat);
			//prevent resubmit of form of refresh
			$app->redirect('index.php?option=com_continued&view=continued&Itemid='.JRequest::getVar( 'Itemid' ).'&cat='.$cat);
		}
		
		//Get Catalog
		$catalog=$model->getCatalog($cat);
		
		//Get Category Info
		if ($cat != 0) {
			$catinfo = $model->getCatInfo($cat);
			JPluginHelper::importPlugin('contined');
			$results = $dispatcher->trigger('onContentPrepare', array(&$catinfo->cat_desc));
		}
		
		//Get User Info 
		if ($user->id) {
			$uinfo=$model->getUserInfo();
		}
		
		
		//Check Category Validity & FM
		if ($catinfo->cat_hasfm) {
			
			//Validity Check
			if ((strtotime($catinfo->cat_end."+ 1 day") <= strtotime("now")) && ($catinfo->cat_end != '0000-00-00')) $expired=true; else $expired = false;
			
			//Check if FM agreed
			if (!$model->hasViewedFM($user->id,$cat) || $showfm) {
				$dispfm=true;
			} else {
				$dispfm=false;
				$model->viewedMenu($user->id,$cat);
			}
		} else {
		//No cat FM
			$model->viewedMenu($user->id,$cat);
			$dispfm=false;
		}
		
		//Check Cat Purchase
		if ($catinfo->cat_free == 0) {
			$bought=ContinuEdHelper::SKUCheck($user->id,$catinfo->cat_sku);
			if (!$bought) $dispfm=true;
		} else { $bought=true; }
		
		//Mark FM viewed, not agreed to
		if ($dispfm) $model->viewedDetails($user->id,$cat);
		
		//Assign Vars
		$this->catalog=$catalog;
		$this->model=$model;
		$this->showfm=$showfm;
		$this->cat=$cat;
		$this->catinfo=$catinfo;
		$this->user=$user;
		$this->bought=$bought;
		$this->dispfm=$dispfm;
		
		//Display
		parent::display($tpl);
	}
}
?>
