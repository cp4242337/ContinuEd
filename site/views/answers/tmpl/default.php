<?php // no direct access
defined('_JEXEC') or die('Restricted access');
global $cecfg;
$db =& JFactory::getDBO();
echo '<h2 class="componentheading">'.$this->cinfo['cname'].'</h2>';
$cpart = 0;
echo '<div align="center"><table align="center" width="90%"><tr><td align="left"><b>Question</b></td><td align="right"><b>Your Answer</b></td></tr>';
foreach ($this->qanda as $qanda) {
	if ($qanda['qsec'] != $cpart) {
		$cpart = $qanda['qsec'];
		echo '<tr><td colspan="2"><hr size="1" /></td></tr>';
		echo '<tr><td align="left" colspan="2"><b>Part '.$cpart;
		if ($qanda['part_name']) echo ' - '.$qanda['part_name'];
		echo '</b></td>';
		echo '</tr>';
	}
	echo '<tr><td valign="top" align="left">';
	if ($qanda['qcat'] == 'assess') { if ($cecfg['EVAL_ASSD']) echo '<img src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/'.$cecfg['EVAL_ASSI'].'" alt="required">';}
	if ($qanda['qreq']) { if ($cecfg['EVAL_REQD']) echo '<img src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/'.$cecfg['EVAL_REQI'].'" alt="required"> ';}
	echo $qanda['ordering'].'. '.$qanda['qtext'];
	echo '</td><td align="right"><b>';
	if ($qanda['qtype'] == 'multi') {
		if ($qanda['qcat'] == 'assess' && $qanda['correct'] == 1) echo '<span style="color:#008000">';
		if ($qanda['qcat'] == 'assess' && $qanda['correct'] == 0) echo '<span style="color:#800000">';
		if ($qanda['qcat'] == 'eval') echo '<span>';
		echo $qanda['opttxt'];
		echo '</span>';
	}
	if ($qanda['qtype'] == 'textbox') echo $qanda['answer'];
	if ($qanda['qtype'] == 'textar') echo $qanda['answer'];
	if ($qanda['qtype'] == 'cbox') {
		if ($qanda['answer'] == 'on') echo 'Checked';
		else echo 'Unchecked';
	}
	if ($qanda['qtype'] == 'mcbox') {
		$query = 'SELECT * FROM #__ce_questions_opts WHERE question = '.$qanda['ques_id'].' ORDER BY ordering ASC';
		$db->setQuery( $query );
		$qopts = $db->loadAssocList();
		$answers = explode(' ',$qanda['answer']);
		foreach ($qopts as $opts) {
			if (in_array($opts['id'],$answers)) { echo $opts['opttxt'].'<br />'; }
		}
	}

	echo '</b></td></tr>';
}
?>
<tr>
	<td colspan="2">
	<hr size="1" />
	</td>
</tr>
<tr>
	<td colspan="2"><?php if ($cecfg['EVAL_REQD']) { ?><img
		src="<?php echo $cecfg['EVAL_REQI']; ?>" alt="required"> - Required <?php } if ($cecfg['EVAL_ASSD'])  { ?><img
		src="<?php echo $cecfg['EVAL_ASSI']; ?>" alt="Assess"> - Assessment
	Question<?php } ?></td>
</tr>
</table>
</div>
<?php
echo '<p align="center"><a href="'.$this->cinfo['cataloglink'].'" class="cebutton">';
echo 'Return</a></p>';

?>
