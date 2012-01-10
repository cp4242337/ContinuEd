<?php
/**
 * @version		$Id: view.html.php 2012-01-09 $
 * @package		ContinuEd.Site
 * @subpackage	assess
 * @copyright	Copyright (C) 2008 - 2012 Corona Productions.
 * @license		GNU General Public License version 2
 */

jimport( 'joomla.application.component.view');

/**
 * ContinuEd Assessment/Completed Page View
 *
 * @static
 * @package		ContinuEd.Site
 * @subpackage	assess
 * @since		always
 */
class ContinuEdViewAssess extends JView
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$model =& $this->getModel();
		$courseid = JRequest::getVar( 'course' );
		$token = JRequest::getVar('token');
		$rating = JRequest::getVar('addrating');
		$user =& JFactory::getUser();
		
		//Check proper access
		$should=ContinuEdHelper::checkViewed('chk',$courseid,$token);
		
		//Logged it, Check page complete
		if ($user->id && $should) {
			
			//Valid course
			if ($courseid) {
				
				//Get course info
				$cinfo=$model->getCourse($courseid);
				
				//Get Q&A Data
				$qanda=$model->loadAnswers($courseid,$token,$cinfo->course_evaltype,$cinfo->course_haspre,$cinfo->course_haseval);
				
				//Get degree/cert info
				$cando = $model->checkDegree($courseid);
				$usercertif=$model->getUserCertif($cinfo->course_defaultcertif);
				$defaultcertif=$model->getCourseCertif($cinfo->course_defaultcertif);
				
				//Get Rating Details. Save rating
				if ($cinfo->course_allowrate) {
					if ($model->checkRated($courseid)) { $carate=false; }
					else {
						$canrate=true;
						if ($rating) {
							$model->addRating($courseid,$rating);
							$canrate=false;
						}

					}
				} else $canrate=false;
				
				//Set return button URL
				$redirurl = $cinfo->course_cataloglink;
				if (!$redirurl) $redirurl = 'index.php?option=com_continued&view=continued&Itemid='.JRequest::getVar( 'Itemid' ).'&cat='.$cinfo->course_cat;
				
				//Assign Vars
				$this->assignRef('qanda',$qanda);
				$this->assignRef('cinfo',$cinfo);
				$this->assignRef('courseid',$courseid);
				$this->assignRef('token',$token);
				$this->assignRef('cando',$cando);
				$this->assignRef('usercertif',$usercertif);
				$this->assignRef('defaultcertif',$defaultcertif);
				$this->assignRef('course_canrate',$canrate);
				$this->assignRef('redirurl',$redirurl);
				
				//Display
				parent::display($tpl);
			}
		} else {
		//Redirect to beginning if not properly stepped 
			$app->redirect('index.php?option=com_continued&view=course&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid); 
		}
	}
}
?>
