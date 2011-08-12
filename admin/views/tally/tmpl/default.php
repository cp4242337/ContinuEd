<?php defined('_JEXEC') or die('Restricted access');
$order = JHTML::_('grid.order', $this->items);
$db =& JFactory::getDBO();

echo '<form action="" method="post" name="adminForm">';
echo '<p align="left">'.$this->cdata['cname'].'<br />';
echo 'Start: '.JHTML::_('calendar',$this->startdate,'startdate','startdate','%Y-%m-%d','onchange="submitform();"');
echo ' End: '.JHTML::_('calendar',$this->enddate,'enddate','enddate','%Y-%m-%d','onchange="submitform();"').'</p></form>';
		

$cpart = 0;
foreach ($this->qdata as $qdata) {
	if ($cpart != $qdata['qsec']) {
		echo '<h3>Part '.$qdata['qsec'];
		if ($qdata['part_name']) echo ' - '.$qdata['part_name'];
		echo '</h3>';
		$cpart = $qdata['qsec'];
	}
	switch ($qdata['qtype']) {
		case 'multi':
		case 'mcbox':
		case 'dropdown':
			echo '<p>';
			echo '<strong>';
			echo $qdata['ordering'].". ".$qdata['qtext'];
			echo '</strong></p>';
			echo '<table width="100%" border="0">';
			$qnum  = 'SELECT count(question) FROM #__ce_evalans WHERE question = '.$qdata['id'].' ';
			$qnum .= '&& date(anstime) BETWEEN "'.$this->startdate.'" AND "'.$this->enddate.'" ';
			$qnum .= 'GROUP BY question';
			$db->setQuery( $qnum );
			$qnums = $db->loadAssocList();
			$numr=$qnums[0]['count(question)'];
			$query  = 'SELECT o.* FROM #__ce_questions_opts as o ';
			$query .= 'WHERE o.question = '.$qdata['id'].' ORDER BY ordering ASC';
			$db->setQuery( $query );
			$qopts = $db->loadObjectList();
			foreach ($qopts as &$o) {
				$qa = 'SELECT count(*) FROM #__ce_evalans WHERE question = '.$qdata['id'].' && answer LIKE "%'.$o->id;
				$qa .= '%" && date(anstime) BETWEEN "'.$this->startdate.'" AND "'.$this->enddate.'" ';
				$qa .= ' GROUP BY question';
				$db->setQuery($qa);
				$o->anscount = $db->loadResult();
				if ($o->anscount == "") $o->anscount = 0;
			}
			$barc=1;
			$cbg = "#FFFFFF";
			foreach ($qopts as $opts) {
				if ($numr != 0) $per = $opts->anscount/$numr; else $per=1;
				echo '<tr bgcolor="'.$cbg.'"><td valign="center" align="left">';
				if ($qans == $opts->id && $opts->correct) {
					$anscor=true;
				}
				
				if ($opts->correct) echo '<b>'.$opts->opttxt.'</b>';
				else echo $opts->opttxt;
				echo '</td>';
				echo '<td valign="center" width="320"><img src="/media/com_continued/bar_'.$barc.'.jpg" height="15" width="'.($per*300).'" align="absmiddle"> ';
				echo '<b>'.$opts->anscount.'</b></td></tr>';
				$barc = $barc + 1;
				if ($barc==5) $barc=1;
				if ($gper < $per) { $gper = $per; $gperid = $opts->id; }
				if ($qans==$opts->id) {
					if ($qdata->q_expl) $expl=$qdata->q_expl;
					else $expl=$opts->optexpl;
				}
				if ($cbg == "#FFFFFF") $cbg="#DDDDDD";
				else $cbg="#FFFFFF";
			}
			echo '</table>';
			break;
		case 'yesno':
			echo '<p>';
			echo '<strong>';
			echo $qdata['ordering'].". ".$qdata['qtext'];
			echo '</strong></p>';
			echo '<table width="100%" border="0">';
			$qnum  = 'SELECT *,count(question) FROM #__ce_evalans WHERE question = '.$qdata['id'].' ';
			$qnum .= '&&date(anstime) BETWEEN "'.$this->startdate.'" AND "'.$this->enddate.'" ';
			$qnum .= 'GROUP BY question';
			$db->setQuery( $qnum );
			$qnums = $db->loadAssocList();
			$numr=$qnums[0]['count(question)'];
			$query  = 'SELECT *,COUNT(answer) as thesum  FROM #__ce_evalans as r ';
			$query .= 'WHERE question = '.$qdata['id'].' ';
			$query .= '&& ( date(anstime) BETWEEN "'.$this->startdate.'" AND "'.$this->enddate.'"  || "thesum" = 0 ) ';
			$query .= 'GROUP BY answer';
			$db->setQuery( $query );
			$qopts = $db->loadAssocList();
			$barc=1;
			$cbg = "#FFFFFF";
			foreach ($qopts as $opts) {
				if ($numr != 0) $per = $opts['thesum']/$numr; else $per=1;
				echo '<tr bgcolor="'.$cbg.'"><td valign="center" align="left"';
				echo '>'.$opts['answer'].'</td><td valign="center" width="320"><img src="../components/com_mpoll/images/bar_'.$barc.'.jpg" height="15" width="'.($per*300).'" align="absmiddle"> <b>'.$opts['thesum'].'</b></td></tr>';
				$barc = $barc + 1;
				if ($barc==5) $barc=1;
				if ($cbg == "#FFFFFF") $cbg="#DDDDDD";
				else $cbg="#FFFFFF";
			}
			echo '</table>';
			break;
	}
}

?>