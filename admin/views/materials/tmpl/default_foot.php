<?php
$cecfg=ContinuEdHelper::getConfig();
$colcounth=6;
if ($cecfg->mams) $colcounth=7;
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<tr>
	<td colspan="<?php echo $colcounth; ?>"><?php echo $this->pagination->getListFooter(); ?></td>
</tr>
