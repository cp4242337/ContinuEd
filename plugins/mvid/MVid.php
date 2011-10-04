<?php
/**
 * MVid plugin for Joomla! 1.5
 * @license http://www.gnu.org/licenses/gpl.html GNU/GPL.
 * @by Mike Amundsen
 * @Copyright (C) 2008 
  */
defined( '_JEXEC' ) or die( 'Restricted access' );

class  plgContinuedMVid extends JPlugin
{

	public function onContentPrepare(&$text) {
		$regex = "#{mvid}(.*?){/mvid}#s";
		$plugin =&JPluginHelper::getPlugin('continued', 'MVid');
		if (!$plugin->published){ 
			//plugin not published 
		}else  { 
			//plugin published 
		}
		$text = preg_match_all( $regex, $text, $matches, PREG_SET_ORDER );
		if ($matches) {
			foreach ($matches as $match) {
				
				$matcheslist =  explode(',',$match[1]);
		
				$vid = trim($matcheslist[0]);
			
				$newvid=$this->MVidReplacer($vid);
				$text = preg_replace("|$match[0]|", $newvid, $text, 1);	
			}
		}
	}
	
	function MVidReplacer ( $vid ) {
		$plugin =&JPluginHelper::getPlugin('content', 'MVid');
		$pluginParams = new JParameter( $plugin->params );
		
		$output = "
	<!-- MVid Begins --><div align=\"center\">
		<script type=\"text/javascript\">
				var flashvars = {};
				flashvars.id = \"".$vid."\";
				var params = {};
				params.wmode = \"opaque\";
				params.bgcolor = \"#000000\";
				params.allowfullscreen = \"true\";
				var attributes = {};
				swfobject.embedSWF(\"espplyrCE.swf\", \"MVidContainer\", \"448\", \"332\", \"9.0.45\", false, flashvars, params, attributes);
			</script>
			<div align=\"center\" id=\"MVidContainer\"><a href=\"http://www.adobe.com/go/getflashplayer\">
					<img src=\"http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif\" alt=\"Get Adobe Flash player\" />
				</a></div></div>
	<!-- MVid Ends -->
	";
		$user = &JFactory::getUser();
		$session = &JFactory::getSession();
		$db =& JFactory::getDBO();
		$query = 'INSERT INTO #__mvid_viewed (mstat_user,mstat_vid,mstat_session) VALUES ("'.$user->id.'","'.$vid.'","'.$session->getId().'")';
		$db->setQuery( $query ); 
		$db->query();
		return $output;
	}
}




?>
