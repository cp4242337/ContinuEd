<?php
/**
 * @version		$Id: view.html.php 2012-01-09 $
 * @package		ContinuEd.Site
 * @subpackage	frontmatter
 * @copyright	Copyright (C) 2008 - 2012 Corona Productions.
 * @license		GNU General Public License version 2
 */

jimport( 'joomla.application.component.view');


/**
 * ContinuEd FrontMatter Page View
 *
 * @static
 * @package		ContinuEd.Site
 * @subpackage	frontmatter
 * @since		always
 */
class ContinuEdViewFrontMatter extends JView
{
	var $cinfo = null;
	var $catinfo = null;
	var $passed = false;
	var $expired = false;
	var $paid = true;
	var $eligable = true;
	var $token = null;
	var $redirurl = null;
	var $incomplete = false;
	var $fmagree = false;
	var $nocredit = 0;
	var $rectype = '';
	
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$courseid = JRequest::getVar( 'course' );
		$this->fmagree = JRequest::getVar( 'fmagree' );
		$this->token = JRequest::getVar('token','');
		$this->nocredit = JRequest::getVar('nocredit','0');
		$user =& JFactory::getUser();
		$model =& $this->getModel();
		$cecfg = ContinuEdHelper::getConfig();
				
		//Get Course Info
		$this->cinfo=$model->getFrontMatter($courseid);
		
		//Set redirct URL if needed
		$this->redirurl = $cinfo->course_cataloglink;
		if (!$this->redirurl) $this->redirurl = 'index.php?option=com_continued&view=continued&Itemid='.JRequest::getVar( 'Itemid' ).'&cat='.$this->cinfo->course_cat;
				
		//Get Category Info
		$this->catinfo=$model->getCatInfo($this->cinfo->course_cat);
		
		//Check Cat Purchased
		if ($this->catinfo->cat_free == 0) {
			$bought=ContinuEdHelper::SKUCheck($user->id,$this->catinfo->cat_sku);
		} else { $bought=true; }
		if (!$bought) { $app->redirect($this->redirurl,"Purchase Required"); }

		//Course Purchase Check
		if ($this->cinfo->course_purchase && $cecfg->purchase) {
			//if ($this->cinfo->course_purchasesku) $this->paid = ContinuEdHelper::SKUCheck($user->id,$this->cinfo->course_purchasesku);
			//else 
			$this->paid = ContinuEdHelper::PurchaseCheck($this->cinfo->course_id);
		}
		else $this->paid = true;
		
		//Check Validity
		if ((strtotime($this->cinfo->course_enddate."+ 1 day") <= strtotime("now")) && ($this->cinfo->course_enddate != '0000-00-00 00:00:00')) {
			$this->expired=true; 
		} else {
			$this->expired = false;
		}
		
		$hasfm = $this->cinfo->course_hasfm;
		
		//Generate Token
		$ctd = date(Ymdhis).$user->id;
		if (empty($this->token)) $this->token=md5($ctd);
		
		//Check Pre Requisite
		if ($this->cinfo->course_prereq) $this->eligable = $model->checkPreReq($this->cinfo->course_id);
		else $this->eligable = true;
		
		//Check Access
		$aid = $user->getAuthorisedViewLevels();
		if (!in_array($this->cinfo->access,$aid) || $this->cinfo->published < 1) { $this->eligable=false; }
		if (!$this->eligable) $app->redirect($this->redirurl,"You are ineligable for this course".$user->getAuthorisedViewLevels());
		
		//Check Passed
		$this->passed = ContinuEdHelper::passedCourse($courseid);
		
		//Check Incomplete, if not passed
		if ($user->id) {
			if (!$this->passed) $this->incomplete = ContinuEdHelper::incompleteCourse($courseid);
			if ($this->incomplete) $this->token = $this->incomplete;
		}
		
		if ($this->nocredit != 0) {
			$this->noCredit();
		} else if ($this->fmagree) {
			$this->moveOn();
		} else if ($this->incomplete && !$this->expired && $this->eligable) {
			$this->resume();
		} else if (!$this->cinfo->course_hasfm) {
			$this->noFM();
		} else if ($this->passed) {
			$this->passed();
		} else if ($this->expired) {
			$this->expired();
		} else if ($this->eligable) {
			$this->eligable();
		} else {
			$this->moveBack();
		}
		
	}
	
	//Resume incomplete session
	function resume() {
		//Resume Session
		ContinuedHelper::resumeSession($this->token);
		/*$this->assignRef('cinfo',$this->cinfo);
		$this->assignRef('token',$this->token);
		$this->assignRef('paid',$this->paid);
		$this->assignRef('expired',$this->expired);
		$this->assignRef('haspassed',$this->passed);
		parent::display($tpl);*/
		$this->moveOn(true);
	}
	
	//Eligable, Not Expired, Not Passed, Credit
	function eligable() {
		$this->rectype='ce';
		$this->assignRef('cinfo',$this->cinfo);
		$this->assignRef('token',$this->token);
		$this->assignRef('paid',$this->paid);
		$this->assignRef('expired',$this->expired);
		$this->assignRef('haspassed',$this->passed);
		parent::display($tpl);
	}
	
	//Eligable, Not Expired, Not Passed, No Credit
	function noCredit() {
		$app =& JFactory::getApplication();
		
		//Start Session
		ContinuedHelper::startSession($this->cinfo->course_id,$this->token,"nonce");
		$proceed=ContinuEdHelper::trackViewed('fmn',$this->cinfo->course_id,$this->token);
		
		$nextstep = 'material';
		
		//Proceed to next step
		if ($proceed) $app->redirect('index.php?option=com_continued&view='.$nextstep.'&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$this->cinfo->course_id.'&nocredit=1&token='.$this->token);
		else echo 'A database error has occured and you cannot continue. Please contact a site admin.';
	}
	
	
	//Eligable, Passed
	function passed() {
		//Start Session
		$this->rectype='review';
		$this->assignRef('cinfo',$this->cinfo);
		$this->assignRef('token',$this->token);
		$this->assignRef('paid',$this->paid);
		$this->assignRef('expired',$this->expired);
		$this->assignRef('haspassed',$this->passed);
		parent::display($tpl);
		
	}
	
	//Eligable, Expired, Not Passed
	function expired() {
		//Start Session
		$this->rectype='expired';
		$this->assignRef('cinfo',$this->cinfo);
		$this->assignRef('token',$this->token);
		$this->assignRef('paid',$this->paid);
		$this->assignRef('expired',$this->expired);
		$this->assignRef('haspassed',$this->passed);
		parent::display($tpl);
		
	}
	
	//Move to next Step
	function moveOn($resume=false) {
		$app =& JFactory::getApplication();
		$user =& JFactory::getUser();
		
		if (!$this->paid) {
			$app->redirect('index.php?option=com_continued&view=purchase&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$this->cinfo->course_id);
		}
		//Track FM Viewed
		if ($this->passed) {
			$what = "fmp";
			$this->rectype='review';
		} else if ($this->expired) {
			$what = "fme";
			$this->rectype='expired';
		} else if ($this->eligable) {
			$what = "fm";
			$this->rectype='ce';
		}
		
		if (!$resume) {
			//Start Session
			ContinuedHelper::startSession($this->cinfo->course_id,$this->token,$this->rectype);
		}
		$proceed=ContinuEdHelper::trackViewed($what,$this->cinfo->course_id,$this->token);
		
		//Course has Pretest
		if ($this->cinfo->course_haspre && !$this->passed && !$this->expired) {
			$nextstep='pretest';
			if (!$user->id) {
				$app->redirect($this->redirurl,'You must login first');
				$proceed=false;
			}
		}
		//No Pretest
		else $nextstep = 'material';
		
		//Proceed to next step
		if ($proceed) $app->redirect('index.php?option=com_continued&view='.$nextstep.'&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$this->cinfo->course_id.'&token='.$this->token);
		else echo 'A database error has occured and you cannot continue. Please contact a site admin.';
	}

	//No FM
	function noFM() {
		if ($this->cinfo->course_haspre || $this->cinfo->course_haseval) { $type = 'ce'; }
		else if (!$this->passed) { $type = 'viewed'; }
		else { $type="review"; }
		
		//Start Session
		ContinuedHelper::startSession($this->cinfo->course_id,$this->token,$type);
		
		$app =& JFactory::getApplication();
		$user =& JFactory::getUser();
		
		//Track FM Viewed
		if ($this->passed) {
			$what = "fmp";
		} else if ($this->expired) {
			$what = "fme";
		} else if ($this->eligable) {
			$what = "fm";
		}
		$proceed=ContinuEdHelper::trackViewed($what,$this->cinfo->course_id,$this->token);
		
		//Course has Pretest
		if ($this->cinfo->course_haspre && !$this->passed && !$this->expired) {
			$nextstep='pretest';
			if (!$user->id) {
				$app->redirect($this->redirurl,'You must login first');
				$proceed=false;
			}
		}
		//No Pretest
		else $nextstep = 'material';
		
		//Proceed to next step
		if ($proceed) $app->redirect('index.php?option=com_continued&view='.$nextstep.'&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$this->cinfo->course_id.'&token='.$this->token);
		else echo 'A database error has occured and you cannot continue. Please contact a site admin.';
	}
	
	
	function moveBack() {
		$app =& JFactory::getApplication();
		$app->redirect($this->redirurl,'You are not eligable for this course');
		
	}
	
}
?>
