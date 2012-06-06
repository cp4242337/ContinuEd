<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');

// Get Config
$cecfg = ContinuEdHelper::getConfig();

// Display Title
echo '<h2 class="componentheading">'.$this->matpage->mat_title.'</h2>';

//Media
if ($this->matpage->media && $cecfg->mams) {
	$mamscfg = MAMSHelper::getConfig();
	echo '<div class="continued-material-media">';
	echo '<div align="center">';
	if ($this->matpage->media[0]->med_type == 'vid' || $this->matpage->media[0]->med_type == 'vids') { //Video Player
		$detect_iDevice = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone") || strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
		if ($detect_iDevice) {
			//html 5 video, only for iDevices
			if ($this->matpage->media[0]->med_type == "vid") echo '<video src="'.JURI::base( true ).'/'.$this->matpage->media[0]->med_file.'" poster="'.JURI::base( true ).'/'.$this->matpage->media[0]->med_still.'" width="'.$mamscfg->vid_w.'" height="'.$mamscfg->vid_h.'" controls preload></video>';
			if ($this->matpage->media[0]->med_type == "vids") echo '<video src="http://'.$mamscfg->vids_url.':1935/'.$mamscfg->vids_app.'/'.'/mp4:'.urlencode($this->matpage->media[0]->med_file).'/playlist.m3u8" poster="'.JURI::base( true ).'/'.$this->matpage->media[0]->med_still.'" width="'.$mamscfg->vid_w.'" height="'.$mamscfg->vid_h.'" controls preload></video>';
		} else {
			//flash player
			echo '<div id="mediaspace"></div>'."\n";
			echo "<script type='text/javascript'>"."\n";
			echo "jwplayer('mediaspace').setup({"."\n";
			echo "'flashplayer': '".JURI::base( true )."/media/com_mams/vidplyr/player.swf',"."\n";
			if ($this->matpage->media[0]->med_type == "vid") echo "'file': '".JURI::base( true ).'/'.$this->matpage->media[0]->med_file."',"."\n";
			if ($this->matpage->media[0]->med_type == "vids") {
				echo "'provider': 'rtmp',"."\n";
				echo "'streamer': 'rtmp://".$mamscfg->vids_url.'/'.$mamscfg->vids_app.'/'."',"."\n";
				echo "'file':'mp4:".$this->matpage->media[0]->med_file."',"."\n";
			}
			echo "'image': '".JURI::base( true ).'/'.$this->matpage->media[0]->med_still."',"."\n";
			echo "'frontcolor': '000000',"."\n";
			echo "'lightcolor': 'cc9900',"."\n";
			echo "'screencolor': '000000',"."\n";
			echo "'skin': '".JURI::base( true )."/media/com_mams/vidplyr/glow.zip',"."\n";
			echo "'controlbar': 'bottom',"."\n";
			echo "'width': '".$mamscfg->vid_w."',"."\n";
			echo "'height': '".((int)$mamscfg->vid_h+30)."'";
			if ($mamscfg->gapro)	echo ",\n'plugins': {'gapro-2': {}}"."\n";
			echo "});"."\n";
			echo "</script>"."\n";
		}
	}
	if ($this->matpage->media[0]->med_type == 'aud') { //Audio Player
		echo '<div id="mediaspace"></div>'."\n";
		echo '<script type="text/javascript">'."\n";
		echo "jwplayer('mediaspace').setup({"."\n";
		echo "'width': '".$mamscfg->aud_w."',"."\n";
		echo "'height': '".((int)$mamscfg->aud_h+30)."',"."\n";
		echo "'file': '".JURI::base( true ).'/'.$this->matpage->media[0]->med_file."',"."\n";
		echo "'image': '".JURI::base( true ).'/'.$this->matpage->media[0]->med_still."',"."\n";
		echo "'frontcolor': '000000',"."\n";
		echo "'lightcolor': 'cc9900',"."\n";
		echo "'screencolor': '000000',"."\n";
		echo "'skin': '".JURI::base( true )."/media/com_mams/vidplyr/glow.zip',"."\n";
		echo "'controlbar': 'bottom',"."\n";
		echo "'modes': [{type: 'flash', src: '".JURI::base( true )."/media/com_mams/vidplyr/player.swf'},{type: 'html5'},{type: 'download'}]"."\n";
		if ($mamscfg->gapro)	echo ",\n'plugins': {'gapro-2': {}}"."\n";
		echo "});"."\n";
		echo "</script>"."\n";
	}
	echo '</div>';
	echo '</div>';
}

// Text/HTML
if ($this->matpage->mat_type=="text") {
	echo $this->matpage->mat_content;
} 

// Return Button
echo '<div align="center">';
echo '<form name="continued_matpage" id="continued_matpage" method="post" action="">';
echo '<input type="hidden" name="ret" value="return">';
echo '<input name="Submit" id="Return" value="Return"  type="submit" class="cebutton">';
echo '<input type="hidden" name="token" value="'.$this->token.'">';
if ($this->nocredit != 0) echo '<input type="hidden" name="nocredit" value="1">';
echo '</form></div>';

?>
</div>