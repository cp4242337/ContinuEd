<?php
/**
 * MAMS Video plugin for ContinuEd
 * @license http://www.gnu.org/licenses/gpl.html GNU/GPL.
 * @by Mike Amundsen
 * @Copyright (C) 2012 
  */
defined( '_JEXEC' ) or die( 'Restricted access' );

class  plgContinuedMVid extends JPlugin
{

	public function onContinuEdPrepare(&$text) {
		$cecfg = ContinuEdHelper::getConfig();
		if ($cecfg->mams) {
			$regex = "#{mvid}(.*?){/mvid}#s";
			$plugin =&JPluginHelper::getPlugin('continued', 'MVid');
			if (!$plugin->published){ 
				//plugin not published 
			}else  { 
				//plugin published 
			}
			$matched = preg_match_all( $regex, $text, $matches, PREG_SET_ORDER );
			if ($matches) {
				foreach ($matches as $match) {
					
					$matcheslist =  explode(',',$match[1]);
			
					$vid = trim($matcheslist[0]);
				
					$newvid=$this->MVidReplacer($vid);
					$text = preg_replace("|$match[0]|", $newvid, $text, 1);	
				}
			}
		}
	}
	
	function MVidReplacer ( $vid ) {
		$cecfg = ContinuEdHelper::getConfig();
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		
		$qm=$db->getQuery(true);
		$qm->select('*');
		$qm->from('#__mams_media');
		$qm->where('published >= 1');
		$qm->where('access IN ('.implode(",",$user->getAuthorisedViewLevels()).')');
		$qm->where('med_id = '.$vid);
		$db->setQuery($qm);
		$media=$db->loadObject();
		
		$output = "";
		if ($media) {
			$mamscfg = MAMSHelper::getConfig();
			$output .= '<div class="continued-material-media">';
			$output .= '<div align="center">';
			if ($media->med_type == 'vid' || $media->med_type == 'vids') { //Video Player
				$detect_iDevice = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone") || strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
				if ($detect_iDevice) {
					//html 5 video, only for iDevices
					if ($media->med_type == "vid") $output .= '<video src="'.JURI::base( true ).'/'.$media->med_file.'" poster="'.JURI::base( true ).'/'.$media->med_still.'" width="'.$mamscfg->vid_w.'" height="'.$mamscfg->vid_h.'" controls preload></video>';
					if ($media->med_type == "vids") $output .= '<video src="http://'.$mamscfg->vids_url.':1935/'.$mamscfg->vids_app.'/'.'/mp4:'.urlencode($media->med_file).'/playlist.m3u8" poster="'.JURI::base( true ).'/'.$media->med_still.'" width="'.$mamscfg->vid_w.'" height="'.$mamscfg->vid_h.'" controls preload></video>';
				} else {
					//flash player
					$output .= '<div id="mediaspace"></div>'."\n";
					$output .= "<script type='text/javascript'>"."\n";
					$output .= "jwplayer('mediaspace').setup({"."\n";
					$output .= "'flashplayer': '".JURI::base( true )."/media/com_mams/vidplyr/player.swf',"."\n";
					if ($media->med_type == "vid") $output .= "'file': '".JURI::base( true ).'/'.$media->med_file."',"."\n";
					if ($media->med_type == "vids") {
						$output .= "'provider': 'rtmp',"."\n";
						$output .= "'streamer': 'rtmp://".$mamscfg->vids_url.'/'.$mamscfg->vids_app.'/'."',"."\n";
						$output .= "'file':'mp4:".$media->med_file."',"."\n";
					}
					$output .= "'image': '".JURI::base( true ).'/'.$media->med_still."',"."\n";
					$output .= "'frontcolor': '000000',"."\n";
					$output .= "'lightcolor': 'cc9900',"."\n";
					$output .= "'screencolor': '000000',"."\n";
					$output .= "'skin': '".JURI::base( true )."/media/com_mams/vidplyr/glow.zip',"."\n";
					$output .= "'controlbar': 'bottom',"."\n";
					$output .= "'width': '".$mamscfg->vid_w."',"."\n";
					$output .= "'height': '".((int)$mamscfg->vid_h+30)."'";
					$output .= ",\n'plugins': {'".JURI::base( true )."/media/com_mams/vidplyr/mamstrack.js': {'itemid':".$media->med_id."}";
					if ($mamscfg->gapro)	$output .= ",'gapro-2': {}";
					$output .= "}"."\n";
					$output .= "});"."\n";
					$output .= "</script>"."\n";
				}
			}
			if ($media->med_type == 'aud') { //Audio Player
				$output .= '<div id="mediaspace"></div>'."\n";
				$output .= '<script type="text/javascript">'."\n";
				$output .= "jwplayer('mediaspace').setup({"."\n";
				$output .= "'width': '".$mamscfg->aud_w."',"."\n";
				$output .= "'height': '".((int)$mamscfg->aud_h+30)."',"."\n";
				$output .= "'file': '".JURI::base( true ).'/'.$media->med_file."',"."\n";
				$output .= "'image': '".JURI::base( true ).'/'.$media->med_still."',"."\n";
				$output .= "'frontcolor': '000000',"."\n";
				$output .= "'lightcolor': 'cc9900',"."\n";
				$output .= "'screencolor': '000000',"."\n";
				$output .= "'skin': '".JURI::base( true )."/media/com_mams/vidplyr/glow.zip',"."\n";
				$output .= "'controlbar': 'bottom',"."\n";
				$output .= "'modes': [{type: 'flash', src: '".JURI::base( true )."/media/com_mams/vidplyr/player.swf'},{type: 'html5'},{type: 'download'}]"."\n";
				$output .= ",\n'plugins': {'".JURI::base( true )."/media/com_mams/vidplyr/mamstrack.js': {'itemid':".$media->med_id."}";
				if ($mamscfg->gapro)	$output .= ",'gapro-2': {}";
				$output .= "}"."\n";
				$output .= "});"."\n";
				$output .= "</script>"."\n";
			}
			$output .= '</div>';
			$output .= '</div>';
		}
		
		return $output;
	}
}




?>
