<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cecfg = ContinuEdHelper::getConfig();

?>
<script type="text/javascript">
	jceq(document).ready(function() {
		jceq.metadata.setType("attr", "validate");
		jceq("#regform").validate({
			errorClass:"uf_pickerror",
			errorPlacement: function(error, element) {
		    	error.appendTo( element.parent("div").next("div") );
		    }
	    });
		jceq(".continued-user-pick-item").click(function(){
			var parent = jceq(this).parents('.continued-user-pick-row');
			jceq('.continued-user-pick-item',parent).removeClass('selected');
			jceq(this).addClass('selected');
		});
	
	});
</script>
<h2 class="componentheading">User Registration</h2>
<?php 
echo $cecfg->REG_PAGE_CONTENT;
echo '<div id="continued-user-pick">';
echo '<form action="" method="post" name="regfrom" id="regform" class="">';
$first = true;
echo '<div class="continued-user-pick-hdr">';
echo 'Select your group below';
echo '</div>';
echo '<div class="continued-user-pick-row">';
foreach ($this->groups as $g) {
	echo '<input type="radio" name="groupid" id="groupid_'.$g->ug_id.'" value="'.$g->ug_id.'"';
	if ($first) { echo ' validate="{required:true, messages:{required:\'Please select a group\'}}"'; $first=false; }
	echo ' class="continued-user-pick-radio">';
	echo '<label class="continued-user-pick-item" for="groupid_'.$g->ug_id.'"><span><b>';
	echo ''.$g->ug_name.'</b><br />'.$g->ug_desc.'</span></label>'; 	
}
echo '</div>';
echo '<div class="continued-user-pick-submit">';
echo '</div>';
echo '<div class="continued-user-pick-submit">';
echo '<input type="submit" value="Begin Registration" class="cebutton" border="0" name="submit">';
echo '</div>';
echo '<input type="hidden" name="option" value="com_continued">';
echo '<input type="hidden" name="view" value="userreg">';
echo '<input type="hidden" name="layout" value="groupuser">';
echo JHtml::_('form.token');
echo '</form>';
echo '<div style="clear:both;"></div>';
echo '</div>';
?>
</div>



