<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cecfg = ContinuEdHelper::getConfig();
?>
<?php
if ($this->cat != 0) {
	echo '<h2 class="componentheading">'.$this->catinfo->cat_name.'</h2>';
}

//****************
// Catalog layout
//****************
if (!$this->dispfm && !$this->showfm && $this->cat != 0) {
	echo '<p>'.$this->catinfo->cat_desc.'<br><br></p><p align="right">';
	if ($this->catinfo->cat_hasfm && $this->catinfo->cat_fmlink) {
		echo '<a href="index.php?option=com_continued&view=continued&Itemid='.JRequest::getVar( 'Itemid' ).'&cat='.$this->cat.'&showfm=1">';
		echo '<img src="images/continued/btn_details.png" border="0" alt="Details"></a>';
	}
	if ($this->catinfo->cat_prev) {
		$clink  = '<a href="'.JURI::current().'?option=com_continued&cat='.$this->catinfo->cat_menu.'&Itemid='.JRequest::getVar( 'Itemid' ).'">';
		$clink  .= '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_prev.png" border="0" alt="Previous Menu">';
		$clink  .= '</a>';
		echo $clink;
	}
	echo '</p>';
	foreach ($this->catalog as $course) {
		//$coursecerts=$this->model->getCourseDegrees($course->id);
		//if ($this->user->idguest) $hascert=in_array($this->cert,$coursecerts); else $hascert=false;
		echo '<table width="100%" cellspacing="0" cellpadding="4" border="0"><tr>';
		if ($course->course_previmg != '' || $course->course_allowrate || $course->course_catrate) {
			echo '<td rowspan="4" valign="top" align="center">';
			if ($course->course_previmg != '') echo '<img src="images/continued/preview/'.$course->course_previmg.'" alt="'.$course->course_name.'" border="0"><br>';
			if ($course->course_allowrate && $course->course_rating != 0)
			echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/rating'.(int)$course->course_rating.'.png" alt="'.(int)$course->course_rating.'">';
			if ($course->course_catlink && $course->course_catrating != 0)
			echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/rating'.(int)$course->course_catrating.'.png" alt="'.(int)$course->course_catrating.' Stars">';
			echo '</td>';
		}
		echo '<td width="90%">';
		echo '<b>'.$course->course_name.'</b>';
		if ($course->course_subtitle) echo '<br>'.$course->course_subtitle;
		if ($cecfg->SHOW_FAC == '1') echo '<br><em>'.$course->course_faculty.'</em>';
		$courseurl = JURI::current().'?option=com_continued&view=course&course='.$course->course_id.'&Itemid='.JRequest::getVar( 'Itemid' );
		
		if ($course->course_purchase) {
			if ($course->course_purchasesku) $paid = ContinuEdHelperCourse::SKUCheck($user->id,$catinfo->course_purchasesku);
			else $paid = ContinuEdHelperCourse::PurchaseCheck($user->id,$course->id);
		}
		else $paid = true;
		if ($course->course_catlink) {
			//Catalog Page
			$clink  = '<a href="'.$courseurl.'">';
			if ($course->expired && $course->course_catexp)	$clink  .= '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_view.png" border="0" alt="View Only, CE Expired">';
			else $clink  .= '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_menu.png" border="0" alt="Program Menu">';
			$clink  .= '</a>';
		} else if ($course->course_extlink) {
			//External Link
			$clink  = '<a href="'.$courseurl.'">';
			$clink  .= '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_launch.png" border="0" alt="Begin">';
			$clink  .= '</a>';
		} else {
			if (($course->status == 'fail') && $course->cantake && !$course->expired) {
				// Failed, Can Take, Not Expired
				$clink  = '<a href="'.$courseurl.'">';
				$clink  .= '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_failed.png" border="0" alt="Take Again">';
				$clink  .= '</a>';
			} else if (($course->status == 'pass' || $course->status == 'complete') && $course->cantake && !$course->expired) {
				// Passed, Can Take, Not Expired
				$clink  = '<a href="'.$courseurl.'">';
				$clink  .= '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_review.png" border="0" alt="Completed, Review">';
				$clink  .= '</a>';
			} else if (!$course->status && $course->cantake && !$course->expired && $paid) {
				// Not Taken, Can Take, Not Expired, Paid
				$clink  = '<a href="'.$courseurl.'">';
				$clink  .= '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_launch.png" border="0" alt="Begin">';
				$clink  .= '</a>';
			} else if ($course->status == 'incomplete' && $course->cantake && !$course->expired && $paid) {
				// Not Completed, Can Take, Not Expired, Paid
				$clink  = '<a href="'.$courseurl.'">';
				$clink  .= '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_resume.png" border="0" alt="Resume">';
				$clink  .= '</a>';
			} else if (($course->status == 'incomplete' || !$course->status) && $course->cantake && !$course->expired && !$paid) {
				// Not Taken, Can Take, Not Expired, Not Paid
				$clink  = '<a href="'.$courseurl.'">';
				$clink  .= '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_purchase.png" border="0" alt="Purchase">';
				$clink  .= '</a>';
			} else if ($course->status == 'pass' && !$course->cantake && !$course->expired){
				// Passed, Cannot Take, Not Expired
				$clink  = '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_completed.png" border="0" alt="Completed">';
			} else if (($course->status == 'incomplete' || !$course->status) && !$course->cantake && !$course->expired && $paid) {
				// Not Taken, Cannot Take, Not Expired, Paid
				$clink  = '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_nolaunch.png" border="0" alt="Prerequsite not met">';
			} else if (($course->status == 'incomplete' || !$course->status) && !$course->cantake && !$course->expired && !$paid) {
				// Not Taken, Cannot Take, Not Expired, Not Paid
				$clink  = '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_nopurchase.png" border="0" alt="Purchase & Prerequsite not met">';
			} else if ($course->expired && $course->status != 'pass' && $course->cantake) {
				// Not Passed, Can Take, Expired
				$clink  = '<a href="'.$courseurl.'">';
				$clink  .= '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_view.png" border="0" alt="View Only, CE Expired">';
				$clink  .= '</a>';
			} else if ($course->expired && $course->status == 'pass' && $course->cantake) {
				// Passed Can Take, Expired
				$clink  = '<a href="'.$courseurl.'">';
				$clink  .= '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_review.png" border="0" alt="Completed, View Only">';
				$clink  .= '</a>';
			} else if ($course->expired && !$course->cantake) {
				// Any Status, Cannot Take, Expired
				$clink  = '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_expired.png" border="0" alt="Expired">';
			}
			if (($course->status == 'incomplete' || !$course->status) && $course->course_haseval && $course->course_viewans) {
				$clink  .= '<a href="'.JURI::current().'?option=com_continued&view=answers&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course->id.'">';
				$clink  .= '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_answers.png" border="0" alt="View Answers">';
				$clink  .= '</a>';
			}
		}
		echo '</td><td rowspan="3">';
		if ($cecfg->CAT_PROV) {
			if ($course->prov_logo != '') echo '<img src="images/continued/provider/'.$course->prov_logo.'" alt="Provided by: '.$course->prov_name.'">';
			else echo 'Provided By: '.$course->prov_name;
		}
		echo '</td></tr>';
		echo '<tr><td>';
		if ($course->course_startdate != '0000-00-00 00:00:00') {
			echo '<b>Release Date:</b> '.date("F d, Y", strtotime($course->course_startdate)).'<br />';
			echo '<b>Expiration Date:</b> '.date("F d, Y", strtotime($course->course_enddate));
		}
		echo '</td></tr><tr><td>';
		if (!empty($course->course_desc)) echo $course->course_desc;
		echo '</td></tr><tr><td colspan="2">'.$clink;
		if ($course->course_nocredit) {
			$urlnc = 'index.php?option=com_continued&view=nocredit&course='.$course->course_id;
			if ($this->user->id) {
				echo '<a href="'.$urlnc.'">';
				echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_nocredit.png" border="0" alt="View Only, No Credit"></a>';
			} else {
				$urlnc='index.php?option=com_continued&view=login&layout=login&tmpl=component&return='.base64_encode($urlnc);
				echo '<a href="#" onclick="open'.$course->course_id.'();">';
				echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_nocredit.png" border="0" alt="View Only, No Credit"></a>';
				echo '<script type="text/javascript">';
				echo 'function open'.$course->course_id.'() {';
				echo 'var src = "'.$urlnc.'"; ';
				echo 'jceq.modal(\'<iframe src="\' + src + \'" height="400" width="680" frameBorder="0" style="border:0">\', {';
				echo ' closeHTML:"",containerCss:{backgroundColor:"#fff",borderColor:"#fff",height:420,	padding:0,width:700},overlayClose:true,opacity:80,overlayCss: {backgroundColor:"#000"}});';
				echo ' }';
				echo '</script>';
			}
				
		}
		if ($this->user->id && $course->status == 'pass' && $course->course_hascertif) {
			echo '<a href="index.php?option=com_continued&view=certif&course='.$course->course_id.'&tmpl=component" target="_blank">';
			echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_certif.png" border="0" alt="Get Certificate"></a>';
		} else if ($this->user->id && $course->course_hascertif && !$course->expired) {
			echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_nocertif.png" border="0" alt="Certificate Not Yet Awarded">';
		}


		echo '</td></tr>';
		echo '<tr><td colspan="2">&nbsp;</td></tr></table>';
	}
	//********
	// Cat FM
	//********
} else if ($this->dispfm && !$this->showfm && $this->cat != 0){
	if (!$this->user->id) {
		echo '<p align="center"><span style="color:#800000;font-weight:bolder;">'.$cecfg->LOGIN_MSG.'</span></p>';
	}

	if ($this->catinfo->cat_start != '0000-00-00') {
		echo '<p><b>Release Date:</b> '.date("F d, Y", strtotime($this->catinfo->cat_start)).'<br />';
		echo '<b>Expiration Date:</b> '.date("F d, Y", strtotime($this->catinfo->cat_end)).'</p>';
	}
	echo $this->catinfo->cat_fm;
	if ($this->user->id) {
		if ($this->bought) {
			echo '<form name="form1" method="post" action=""';
			if (!$this->expired) echo 'onsubmit="return isChecked(this.fmv);"';
			echo '>';
			echo '<div align="center">';
			if (!$this->expired) {
				echo '<table id="agreet" style="border: medium none ; padding: 5px;" border="0" cellpadding="0" cellspacing="0" width="500" align="center">';
				echo '<tbody><tr><td colspan="2" align="center">';
				echo '<div id="cbError" style="color: rgb(136, 0, 0); font-size: 10pt; font-weight: bold; display: none; position: relative;">';
				echo 'You must agree to the following statement:<br><br></div></td></tr><tr>';
				echo '<td align="left" valign="top" width="30"><input name="fmv" id="fmv" value="true" type="checkbox"></td>';
				echo '<td valign="top" width="470"><span class="style2">'.$cecfg->FM_TEXT.'</span></td></tr>';
				echo '<tr><td colspan="2" align="center"><br>';
			} else {
				echo '<input name="fmv" id="fmv" value="true" type="hidden">';
			}
			echo '<input name="Submit" id="Continue to Educational Activity" value="Continue to Educational Activity" type="image" src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_continue.png"></td></tr>';
			if (!$this->expired) echo '</tbody></table><br>';
			echo '</div></form>';
			echo '<script type="text/javascript">
				<!--
				function isChecked(elem) {
					var lyr = document.getElementById(\'cbError\');
					var tbl = document.getElementById(\'agreet\');
					if (elem.checked) {
						lyr.style.display=\'none\'; 
						tbl.style.border=\'none\';
						return true;
					} else { 
						lyr.style.display=\'inline\'; 
						tbl.style.border=\'thick #880000 solid\';
						elem.focus();
						return false; 
					}
				}
				
				//-->
				</script>';
		} else {
			//not bought
			echo '<p align="center"><span style="color:#800000;font-weight:bolder;">You need to purchase this program to continue.<br>';
			if ($this->catinfo->cat_skulink) echo 'Please <a href="'.$this->catinfo->cat_skulink.'" target="_blank">Click Here</a> to purchase this program.</span></p>';
		}
	} else {
		//not logged in
		echo '<p align="center"><span style="color:#800000;font-weight:bolder;">'.$cecfg->LOGIN_MSG.'</span></p>'; }
} else if ($this->showfm && $this->cat != 0) {
	echo $this->catinfo->cat_fm;
	echo '<p align="center"><a href="index.php?option=com_continued&view=continued&Itemid='.JRequest::getVar( 'Itemid' ).'&cat='.$this->cat.'">';
	echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_return.png" border="0" alt="Return"></a></p>';
}


?>
</div>