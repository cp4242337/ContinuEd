<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cecfg = ContinuEdHelper::getConfig();
	?>
<div class="componentheading">User Profile</div>
<?php 
echo '<b>User Group:</b> '.$this->userinfo->userGroupName.'<br />';
echo '<b>Registered on:</b> '.$this->userinfo->registerDate.'<br />';
echo '<b>Last Updated:</b> '.$this->userinfo->lastUpdated.'<br /><br />';
foreach ($this->userfields as $f) {
	if ($f->uf_type != "password") {
		$field=$f->uf_sname;
		echo '<b>'.$f->uf_name.':</b> '.$this->userinfo->$field.'<br />';
	}
}
?>
</div>