<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');

?>
<script type="text/javascript">
	jceq(document).ready(function() {
		jceq.metadata.setType("attr", "validate");
		jceq("#regform").validate({errorClass:"uf_error"});

	});


</script>
<h2 class="componentheading">User Registration</h2>
<?php 
echo $cecfg->REG_PAGE_CONTENT;
echo '<form action="" method="post" name="regfrom" id="regform" class="">';
$first = true;
echo '<fieldset>';
foreach ($this->groups as $g) {
	echo '<label for="groupid_'.$g->ug_id.'">';
	echo '<input type="radio" name="groupid" id="groupid_'.$g->ug_id.'" value="'.$g->ug_id.'"';
	if ($first) { echo ' validate="required:true"'; $first=false; }
	echo '>';
	echo ''.$g->ug_name.'</label><br />'; 	
}
echo '<label for="groupid" class="uf_error">Please select your group</label>';
echo '</fieldset>';




echo '<br /><input type="image" value="Begin Registration" src="media/com_continued/template/'.$cecfg->TEMPLATE.'/'.'btn_beginreg.png" border="0" name="submit">';
echo '<input type="hidden" name="option" value="com_continued">';
echo '<input type="hidden" name="view" value="userreg">';
echo '<input type="hidden" name="layout" value="regform">';
echo '</form>';
?>
</div>



