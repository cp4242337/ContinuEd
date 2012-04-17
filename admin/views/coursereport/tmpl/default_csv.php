<?php defined('_JEXEC') or die('Restricted access');
$path = JPATH_SITE.'/cache/';
$filename = 'ContinuEd_Course_'.$this->course.'_Report' . '-' . date("Y-m-d").'.csv';
$contents = "";


$contents .= '"'.JText::_( 'Name' ).'",'; 
$contents .= '"'.JText::_( 'Group' ).'",'; 
$contents .= '"'.JText::_( 'EMail' ).'",'; 
$contents .= '"'.JText::_( 'NABP ID' ).'",'; 
$contents .= '"'.JText::_( 'DOB' ).'",'; 
$contents .= '"'.JText::_( 'EMail' ).'",'; 
if (!$this->course) {
	$contents .= '"'.JText::_( 'Course' ).'",';
	$contents .= '"'.JText::_( 'Category' ).'",'; 
} 
$contents .= '"'.JText::_( 'Completion Status' ).'",'; 
$contents .= '"'.JText::_( 'Record Type' ).'",'; 
$contents .= '"'.JText::_( 'Last Step Completed' ).'",';
$contents .= '"'.JText::_( 'Start' ).'",'; 
$contents .= '"'.JText::_( 'End' ).'",'; 
$contents .= '"'.JText::_( 'Pre Score' ).'",'; 
$contents .= '"'.JText::_( 'Post Score' ).'",'; 

//Fill in questions if we are showing only 1 course
if ($this->course) {
	//pretest
	foreach ($this->qpre as $qu) {
		$contents .= '"Pre #'.$qu->ordering.' '.$qu->q_text.'",';
	}
	//inter
	foreach ($this->qinter as $qu) {
		$contents .= '"Inter #'.$qu->ordering.' '.$qu->q_text.'",';
	}
	//posttest
	foreach ($this->qpost as $qu) {
		$contents .= '"Post #'.$qu->ordering.' '.$qu->q_text.'",';
	}
}
$contents .= "\n";

$k = 0;
$cq = 1;
for ($i=0, $n=count( $this->items ); $i < $n; $i++)
{
	$row = &$this->items[$i];
	$contents .= '"'.$this->userlist[$row->rec_user]->name.'",'; 
	$contents .= '"'.$this->userlist[$row->rec_user]->usergroup.'",'; 
	$contents .= '"'.$this->userlist[$row->rec_user]->email.'",'; 
	$contents .= '"'.$row->pharmid.'",';
	$contents .= '"'.$row->pharmdob.'",';
	if (!$this->course) {
		$contents .= '"'.$row->course_name.'",';
		$contents .= '"'.$row->cat_name.'",';
	} 
	switch ($row->rec_pass) {
		case 'pass': $contents .= '"Completed - Pass",'; break;
		case 'fail': $contents .= '"Completed - Fail",'; break;
		case 'incomplete': $contents .= '"Incomplete",'; break;
		case 'audit': $contents .= '"Completed - No Credit",'; break;
		case 'complete': $contents .= '"Completed",'; break;
	}
	switch ($row->rec_type) {
		case 'nonce': $contents .= '"No Credit",'; break;
		case 'ce': $contents .= '"CE",'; break;
		case 'review': $contents .= '"Review",'; break;
		case 'expired': $contents .= '"Expired",'; break;
		case 'viewed'	: $contents .= '"Viewed",'; break;
	} 
	switch ($row->rec_laststep) {
		case 'fm': $contents .= '"Front Matter",'; break;
		case 'mt': $contents .= '"Material",'; break;
		case 'qz': $contents .= '"Evaluation",'; break;
		case 'chk': $contents .= '"Check",'; break;
		case 'asm': $contents .= '"Assess (Grading)",'; break;
		case 'crt': $contents .= '"Certificate",'; break;
		case 'vo': $contents .= '"Material - Expired",'; break;
		case 'fmp': $contents .= '"FM - Passed",'; break;
		case 'mtp': $contents .= '"Material - Passed",'; break;
		case 'ans': $contents .= '"View Answers",'; break;
		case 'pre': $contents .= '"PreTest",'; break;
		case 'lnk': $contents .= '"Entry Link",'; break;
		case 'fme': $contents .= '"Front Matter - Exp",'; break;
		case 'fmn': $contents .= 'Entry Link - No Credit'; break;
		case 'mtn': $contents .= 'Material - No Credit'; break;
	}
	$contents .= '"'.$row->rec_start.'",';
	$contents .= '"'.$row->rec_end.'",'; 
	if ($row->rec_prescore == -1 || !$row->rec_user || $row->rec_type != 'ce') $contents .= '"N/A",'; else $contents .= '"'.$row->rec_prescore.'",'; 
	if ($row->rec_postscore == -1 || !$row->rec_user || $row->rec_type != 'ce') $contents .= '"N/A",'; else $contents .= '"'.$row->rec_postscore.'",'; 
	//Fill in answers if we are showing only 1 course
	if ($this->course) {
		//pretest
		foreach ($this->qpre as $qu) {
			$contents .= '"';
			$qnum = 'q'.$qu->q_id.'ans';
			if ($qu->q_type == 'multi' || $qu->q_type == 'dropdown') { 
				$contents .= $this->opts[$row->$qnum]->text; 
			}
			if ($qu->q_type == 'textbox') { $contents .= $row->$qnum; }
			if ($qu->q_type == 'textar') { $contents .= $row->$qnum; }
			if ($qu->q_type == 'cbox') { if ($row->$qnum == 'on') $contents .= 'Checked'; else $contents .= 'Unchecked'; }
			if ($qu->q_type == 'mcbox') {
				$answers = explode(' ',$row->$qnum);
				foreach ($answers as $ans) {
					$contents .= $this->opts[$ans]->text.' ';
				}
			}
			if ($qu->q_type == 'yesno') { $contents .= $row->$qnum; }
			$contents .= '",';
		}
		//inter
		foreach ($this->qinter as $qu) {
			$contents .= '"';
			$qnum = 'q'.$qu->q_id.'ans';
			if ($qu->q_type == 'multi' || $qu->q_type == 'dropdown') { 
				$contents .= $this->opts[$row->$qnum]->text; 
			}
			if ($qu->q_type == 'textbox') { $contents .= $row->$qnum; }
			if ($qu->q_type == 'textar') { $contents .= $row->$qnum; }
			if ($qu->q_type == 'cbox') { if ($row->$qnum == 'on') $contents .= 'Checked'; else $contents .= 'Unchecked'; }
			if ($qu->q_type == 'mcbox') {
				$answers = explode(' ',$row->$qnum);
				foreach ($answers as $ans) {
					$contents .= $this->opts[$ans]->text.' ';
				}
			}
			if ($qu->q_type == 'yesno') { $contents .= $row->$qnum; }
			$contents .= '",';
		}
		//posttest
		foreach ($this->qpost as $qu) {
			$contents .= '"';
			$qnum = 'q'.$qu->q_id.'ans';
			if ($qu->q_type == 'multi' || $qu->q_type == 'dropdown') {
				$contents .= $this->opts[$row->$qnum]->text; 
			}
			if ($qu->q_type == 'textbox') { $contents .= $row->$qnum; }
			if ($qu->q_type == 'textar') { $contents .= $row->$qnum; }
			if ($qu->q_type == 'cbox') { if ($row->$qnum == 'on') $contents .= 'Checked'; else $contents .= 'Unchecked'; }
			if ($qu->q_type == 'mcbox') {
				$answers = explode(' ',$row->$qnum);
				foreach ($answers as $ans) {
					$contents .= $this->opts[$ans]->text.' ';
				}
			}
			if ($qu->q_type == 'yesno') { $contents .= $row->$qnum; }
			$contents .= '",';
		}
	}

	$contents .= "\n";
	$k = 1 - $k;
	$cq = $row->disporder+1;
}
JFile::write($path.$filename,$contents);

 $app = JFactory::getApplication();
 $app->redirect('../cache/'.$filename);