<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');

// Get Config
$cecfg = ContinuEdHelper::getConfig();

// Display Title
echo '<div class="componentheading">'.$this->matpage->mat_title.'</div>';

// Display content

// Text/HTML
if ($this->matpage->mat_type=="text") {
	echo $this->matpage->mat_content;
} 

// Return Button
echo '<div align="center">';
echo '<form name="continued_matpage" id="continued_matpage" method="post" action="">';
echo '<input type="hidden" name="ret" value="return">';
echo '<input name="Submit" id="Return" value="Return"  type="image" src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_return.png">';
echo '<input type="hidden" name="token" value="'.$this->token.'">';
echo '</form></div>';

?>
</div>