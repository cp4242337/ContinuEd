<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cecfg = ContinuEdHelper::getConfig();
	?>
<h2 class="componentheading">User Profile</h2>
<?php 
echo '<p><a href='.JRoute::_("index.php?option=com_continued&view=user&layout=proedit").'">';
echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/'.'btn_editprofile.png" alt="Edit Profile"></a>';
//echo '<a href='.JRoute::_("index.php?option=com_continued&view=login&layout=logout").'">';
//echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/'.'btn_logout.png" alt="Logout"></a></p>';

echo '<div id="continued-user-info">';
echo '<div class="continued-user-info-row"><div class="continued-user-info-label">User Group</div><div class="continued-user-info-hdr">'.$this->userinfo->userGroupName.'</div></div>';
echo '<div class="continued-user-info-row"><div class="continued-user-info-label">Registered on</div><div class="continued-user-info-value">'.$this->userinfo->registerDate.'</div></div>';
echo '<div class="continued-user-info-row"><div class="continued-user-info-label">Last Updated</div><div class="continued-user-info-value">'.$this->userinfo->lastUpdated.'</div></div>';
foreach ($this->userfields as $f) {
	if ($f->uf_type != "password" && $f->uf_profile) {
		$field=$f->uf_sname;
		echo '<div class="continued-user-info-row">';
		echo '<div class="continued-user-info-label">'.$f->uf_name.'</div>';
		echo '<div class="continued-user-info-value">'.$this->userinfo->$field.'</div>';
		echo '</div>';
	}
}
echo '<div style="clear:both;"></div>';
echo '</div>';
?>
</div>