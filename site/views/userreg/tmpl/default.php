<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cecfg = ContinuEdHelper::getConfig();
	?>
<h2 class="componentheading">User Registration</h2>
<?php 
echo $cecfg->REG_PAGE_CONTENT;
echo '<form action="" method="post">';
foreach ($this->groups as $g) {
	echo '<label><input type="radio" name="groupid" id="groupid'.$g->ug_id.'" value="'.$g->ug_id.'">';
	echo ''.$g->ug_name.'</label><br />'; 	
}
echo '<br /><input type="submit" name="submit" value="Begin Registration">';
echo '<input type="hidden" name="option" value="com_continued">';
echo '<input type="hidden" name="view" value="userreg">';
echo '<input type="hidden" name="layout" value="regform">';
echo '</form>';
?>
</div>