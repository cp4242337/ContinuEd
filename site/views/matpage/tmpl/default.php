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
		//flash player
		echo '<div id="mediaspace"></div>'."\n";
		echo "<script type='text/javascript'>"."\n";
		echo "jwplayer('mediaspace').setup({"."\n";
		if ($this->matpage->media[0]->med_type == "vid") {
			echo "'flashplayer': '".JURI::base( true )."/media/com_mams/vidplyr/player.swf',"."\n";
			echo "'file': '".JURI::base( true ).'/'.$this->matpage->media[0]->med_file."',"."\n";
		}
		if ($this->matpage->media[0]->med_type == "vids") {
			echo "'modes':[";
			echo "{ type: 'flash',\n";
			echo "'src': '".JURI::base( true )."/media/com_mams/vidplyr/player.swf',"."\n";
			echo "'config':{\n";
			echo "'provider': 'rtmp',"."\n";
			echo "'streamer': 'rtmp://".$mamscfg->vids_url.'/'.$mamscfg->vids_app.'/'."',"."\n";
			echo "'file':'mp4:".$this->matpage->media[0]->med_file."',"."\n";
			echo "}},\n";
			echo "{ type: 'html5',\n";
			echo "'config':{\n";
			echo "'file':'http://".$mamscfg->vid5_url."/".$this->matpage->media[0]->med_file."',"."\n";
			echo "}}\n";
			echo "],\n";
		}
		echo "'image': '".JURI::base( true ).'/'.$this->matpage->media[0]->med_still."',"."\n";
		echo "'frontcolor': '000000',"."\n";
		echo "'lightcolor': 'cc9900',"."\n";
		echo "'screencolor': '000000',"."\n";
		echo "'skin': '".JURI::base( true )."/media/com_mams/vidplyr/glow.zip',"."\n";
		echo "'controlbar': 'bottom',"."\n";
		echo "'width': '".$mamscfg->vid_w."',"."\n";
		echo "'height': '".((int)$mamscfg->vid_h+30)."'";
		echo ",\n'plugins': {'".JURI::base( true )."/media/com_mams/vidplyr/mamstrack.js': {'itemid':".$this->matpage->media[0]->med_id."}";
		if ($mamscfg->gapro)	echo ",'gapro-2': {}";
		echo "}"."\n";
		echo "});"."\n";
		echo "</script>"."\n";
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
		echo ",\n'plugins': {'".JURI::base( true )."/media/com_mams/vidplyr/mamstrack.js': {'itemid':".$this->matpage->media[0]->med_id."}";
		if ($mamscfg->gapro)	echo ",'gapro-2': {}";
		echo "}"."\n";
		echo "});"."\n";
		echo "</script>"."\n";
	}
	echo '</div>';
	echo '</div>';
	
	if ($this->matpage->dloads && $cecfg->mams) {
		$mamscfg = MAMSHelper::getConfig();
		//Downloads
		echo '<div class="continued-material-downloads">';
		$dloads = Array();
		foreach ($this->matpage->dloads as $d) {
			$dloads[]='<a href="'.JRoute::_("components/com_mams/dl.php?dlid=".$d->dl_id).'" class="continued-material-dllink cebutton" target="_blank">Download '.$d->dl_lname.'</a>';
		}
		echo implode(" ",$dloads);
		echo '</div>';
	}
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