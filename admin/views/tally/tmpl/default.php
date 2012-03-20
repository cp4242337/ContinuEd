<?php defined('_JEXEC') or die('Restricted access');
$order = JHTML::_('grid.order', $this->items);
$db =& JFactory::getDBO();

echo '<form action="" method="post" name="adminForm">';
echo '<p align="left">'.$this->cdata['course_name'].'<br />';
echo 'Start: '.JHTML::_('calendar',$this->startdate,'startdate','startdate','%Y-%m-%d','onchange="submitform();"');
echo ' End: '.JHTML::_('calendar',$this->enddate,'enddate','enddate','%Y-%m-%d','onchange="submitform();"').'</p></form>';

$sections = Array('preqdata','interqdata','postqdata');

foreach ($sections as $sec) {
	$cpart = 0;
	foreach ($this->$sec as $qdata) {
		switch ($sec) {
			case 'preqdata': $secname="Pre"; break;
			case 'interqdata': $secname="Inter"; break;
			case 'postqdata': $secname="Post"; break;
		}
		if ($cpart != $qdata['q_part']) {
			echo '<h3>'.$secname.' Part '.$qdata['q_part'];
			if ($qdata['part_name']) echo ' - '.$qdata['part_name'];
			echo '</h3>';
			$cpart = $qdata['q_part'];
		}
		echo '<div class="continued-question">';
		echo '<div class="continued-question-text">'.$qdata['ordering'].'. '.$qdata['q_text'].'</div>';
		switch ($qdata['q_type']) {
			case 'multi':
			case 'mcbox':
			case 'dropdown':
				$qnum  = 'SELECT count(question) FROM #__ce_evalans WHERE question = '.$qdata['q_id'].' ';
				$qnum .= '&& date(anstime) BETWEEN "'.$this->startdate.'" AND "'.$this->enddate.'" ';
				$qnum .= 'GROUP BY question';
				$db->setQuery( $qnum );
				$qnums = $db->loadAssocList();
				$numr=$qnums[0]['count(question)'];
				$query  = 'SELECT o.* FROM #__ce_questions_opts as o ';
				$query .= 'WHERE o.opt_question = '.$qdata['q_id'].' ORDER BY ordering ASC';
				$db->setQuery( $query );
				$qopts = $db->loadObjectList();
				foreach ($qopts as &$o) {
					$qa = 'SELECT count(*) FROM #__ce_evalans WHERE question = '.$qdata['q_id'].' && answer LIKE "'.$o->opt_id;
					$qa .= '" && date(anstime) BETWEEN "'.$this->startdate.'" AND "'.$this->enddate.'" ';
					$qa .= ' GROUP BY question';
					$db->setQuery($qa);
					$o->anscount = $db->loadResult();
					if ($o->anscount == "") $o->anscount = 0;
				}
				$barc=1;
				foreach ($qopts as $opts) {
					if ($numr != 0) $per = $opts->anscount/$numr; else $per=1;
					if ($qans == $opts->opt_id && $opts->opt_correct) {
						$anscor=true;
					}
					echo '<div class="continued-opt">';
	
					echo '<div class="continued-opt-text">';
					if ($opts->opt_correct) echo '<span class="continued-opt-correct"><b>'.$opts->opt_text.'</b></span>';
					else echo $opts->opt_text;
					echo '</div>';
					echo '<div class="continued-opt-count">';
					echo ($opts->anscount);
					echo '</div>';
					echo '<div class="continued-opt-bar-box"><div class="continued-opt-bar-bar bc'.$barc.'" style="width:'.($per*100).'%"></div></div>';
					echo '</div>';
					$barc = $barc + 1;
					if ($barc==4) $barc=1;
					
				}
				break;
			case 'yesno':
				$qnum  = 'SELECT *,count(question) FROM #__ce_evalans WHERE question = '.$qdata['q_id'].' ';
				$qnum .= '&&date(anstime) BETWEEN "'.$this->startdate.'" AND "'.$this->enddate.'" ';
				$qnum .= 'GROUP BY question';
				$db->setQuery( $qnum );
				$qnums = $db->loadAssocList();
				$numr=$qnums[0]['count(question)'];
				$query  = 'SELECT *,COUNT(answer) as thesum  FROM #__ce_evalans as r ';
				$query .= 'WHERE question = '.$qdata['q_id'].' ';
				$query .= '&& ( date(anstime) BETWEEN "'.$this->startdate.'" AND "'.$this->enddate.'"  || "thesum" = 0 ) ';
				$query .= 'GROUP BY answer';
				$db->setQuery( $query );
				$qopts = $db->loadAssocList();
				$barc=1;
				$cbg = "#FFFFFF";
				foreach ($qopts as $opts) {
					if ($numr != 0) $per = $opts['thesum']/$numr; else $per=1;
					
					echo '<div class="continued-opt">';
	
					echo '<div class="continued-opt-text">';
					echo $opts['answer'];
					echo '</div>';
					echo '<div class="continued-opt-count">';
					echo $opts['thesum'];
					echo '</div>';
					echo '<div class="continued-opt-bar-box"><div class="continued-opt-bar-bar bc'.$barc.'" style="width:'.($per*100).'%"></div></div>';
					echo '</div>';
					$barc = $barc + 1;
				}
				break;
		}
		echo '</div>';
	}
}


?>