<div id="continued">
<?php // no direct access
$cecfg = ContinuEdHelper::getConfig();
$user = JFactory::getUser();
defined('_JEXEC') or die('Restricted access');
echo '<h2 class="componentheading">'.$this->mtext->course_name.'</h2>';
//Gve error message if they tryed to jummop over material staright to the eval
if ($this->jumpedover) echo '<div class="continued-error">Please complete all material before proceeding</div>';
//Show Material
if (count($this->matpages) == 1) {
//only 1 material page
	echo $this->matpages[0]->mat_content;//Media
	if ($this->matpages[0]->media && $cecfg->mams) {
		$mamscfg = MAMSHelper::getConfig();	
		echo '<div class="continued-material-media">';
		echo '<div align="center">';
		if ($this->matpages[0]->media[0]->med_type == 'vid' || $this->matpages[0]->media[0]->med_type == 'vids') { //Video Player
			//flash player
			echo '<div id="mediaspace"></div>'."\n";
			echo "<script type='text/javascript'>"."\n";
			echo "jwplayer('mediaspace').setup({"."\n";
			if ($this->matpages[0]->media[0]->med_type == "vid") {
				echo "'flashplayer': '".JURI::base( true )."/media/com_mams/vidplyr/player.swf',"."\n";
				echo "'file': '".JURI::base( true ).'/'.$this->matpages[0]->media[0]->med_file."',"."\n";
			}
			if ($this->matpages[0]->media[0]->med_type == "vids") {
				echo "'modes':[";
				echo "{ type: 'flash',\n";
				echo "'src': '".JURI::base( true )."/media/com_mams/vidplyr/player.swf',"."\n";
				echo "'config':{\n";
				echo "'provider': 'rtmp',"."\n";
				echo "'streamer': 'rtmp://".$mamscfg->vids_url.'/'.$mamscfg->vids_app.'/'."',"."\n";
				echo "'file':'mp4:".$this->matpages[0]->media[0]->med_file."',"."\n";
				echo "}},\n";
				echo "{ type: 'html5',\n";
				echo "'config':{\n";
				echo "'file':'http://".$mamscfg->vid5_url."/".$this->matpages[0]->media[0]->med_file."',"."\n";
				echo "}}\n";
				echo "],\n";
			}
			echo "'image': '".JURI::base( true ).'/'.$this->matpages[0]->media[0]->med_still."',"."\n";
			echo "'frontcolor': '000000',"."\n";
			echo "'lightcolor': 'cc9900',"."\n";
			echo "'screencolor': '000000',"."\n";
			echo "'skin': '".JURI::base( true )."/media/com_mams/vidplyr/glow.zip',"."\n";
			echo "'controlbar': 'bottom',"."\n";
			echo "'width': '".$mamscfg->vid_w."',"."\n";
			echo "'height': '".((int)$mamscfg->vid_h+30)."'";
			echo ",\n'plugins': {'".JURI::base( true )."/media/com_mams/vidplyr/mamstrack.js': {'itemid':".$this->matpages[0]->media[0]->med_id."}";
			if ($mamscfg->gapro)	echo ",'gapro-2': {}";
			echo "}"."\n";
			echo "});"."\n";
			echo "</script>"."\n";
			
			
		}
		if ($this->matpages[0]->media[0]->med_type == 'aud') { //Audio Player
			echo '<div id="mediaspace"></div>'."\n";
			echo '<script type="text/javascript">'."\n";
			echo "jwplayer('mediaspace').setup({"."\n";
			echo "'width': '".$mamscfg->aud_w."',"."\n";
			echo "'height': '".((int)$mamscfg->aud_h+30)."',"."\n";
			echo "'file': '".JURI::base( true ).'/'.$this->matpages[0]->media[0]->med_file."',"."\n";
			echo "'image': '".JURI::base( true ).'/'.$this->matpages[0]->media[0]->med_still."',"."\n";
			echo "'frontcolor': '000000',"."\n";
			echo "'lightcolor': 'cc9900',"."\n";
			echo "'screencolor': '000000',"."\n";
			echo "'skin': '".JURI::base( true )."/media/com_mams/vidplyr/glow.zip',"."\n";
			echo "'controlbar': 'bottom',"."\n";
			echo "'modes': [{type: 'flash', src: '".JURI::base( true )."/media/com_mams/vidplyr/player.swf'},{type: 'html5'},{type: 'download'}]"."\n";
			echo ",\n'plugins': {'".JURI::base( true )."/media/com_mams/vidplyr/mamstrack.js': {'itemid':".$this->matpages[0]->media[0]->med_id."}";
			if ($mamscfg->gapro)	echo ",'gapro-2': {}";
			echo "}"."\n";
			echo "});"."\n";
			echo "</script>"."\n";
		}
		echo '</div>';
		echo '</div>';
	}
	
	if ($this->matpages[0]->dloads && $cecfg->mams) {
		$mamscfg = MAMSHelper::getConfig();
		//Downloads
		echo '<div class="continued-material-downloads">';
		$dloads = Array();
		foreach ($this->matpages[0]->dloads as $d) {
			$dloads[]='<a href="'.JRoute::_("components/com_mams/dl.php?dlid=".$d->dl_id).'" class="continued-material-dllink cebutton" target="_blank">Download '.$d->dl_lname.'</a>';
		}
		echo implode(" ",$dloads);
		echo '</div>';
	}
	
} else if (count($this->matpages)) {
//multiple material pages
	foreach ($this->matpages as $mp) {
		echo '<b>'.$mp->mat_title.'</b><br />';
		if ($mp->mat_desc) echo $mp->mat_desc.'<br /><br />'; 
		echo '<a href="index.php?option=com_continued&view=matpage&token='.$this->token;
		if ($this->nocredit != 0) echo '&nocredit=1';
		echo '&Itemid='.JRequest::getVar('Itemid').'&matid='.$mp->mat_id.'&course='.$mp->mat_course.'" class="cebutton">';
		if ($this->mpdata[$mp->mat_id]->mu_status == 'complete' && $user->id) echo 'Review';
		else if ($this->mpdata[$mp->mat_id]->mu_status == 'incomplete' && $user->id) echo 'Resume';
		else echo 'Begin';
		echo '</a><br /><br />';
	}
}

if ($this->mtext->course_material) {
	//material in course info 
	echo $this->mtext->course_material;
}


//Show Q&A Entry box
if ($this->mtext->course_qanda == "submit") {
	?>
		<script type="text/javascript">
		  /* attach a submit handler to the form */
		  window.onload = (function(){
			try{jQuery("#qandaform").submit(function(event) {
		
		    /* stop form from submitting normally */
		    event.preventDefault(); 
		        
		    /* Send the data using post and put the results in a div */
		    jQuery.post( "components/com_continued/qanda.php", jQuery("#qandaform").serialize(),
		      function( data ) {
		          jQuery( "#qabox-msg" ).empty().append( data );
		          jQuery(':input','#qandaform').not(':button, :submit, :reset, :hidden').val('');
		         	          
		      }
		    );
		  });
		}catch(e){}});
		</script>
	
	
		<div id="qabox-outer">
			<div id="qabox-top"></div>
			<div id="qabox-inner">
				<div id="qabox-title">Submit your Questions</div>
				<div id="qabox-question">
					<form id="qandaform" name="qandaform" action="components/com_continued/qanda.php" method="post">
						<textarea  name="qtext" id="qatext" rows="3" cols="70" class="required"></textarea><br />
						<input type="hidden" name="courseid" value="<?php echo $this->mtext->course_id; ?>" />
						<input type="submit" name="button" id="submitter" class="qabox-btn cebutton" />
						<span style="clear:both"><!-- spanner --></span>
					</form>
				</div>
				<div id="qabox-msg"></div>
			</div>
			<div id="qabox-bot"></div>
		</div>
		<?php 
} ?> 


//Expired or Passed/Completed or NoCredit, show return Button
if ($this->expired || $this->passed || $this->nocredit != 0) {
	echo '<div align="center">';
	echo '<form name="continued_material" id="continued_material" method="post" action="">';
	echo '<input type="hidden" name="gte" value="return">';
	echo '<input name="Submit" id="Return" value="Return"  type="submit" class="cebutton">';
	echo '<input type="hidden" name="token" value="'.$this->token.'">';
	if ($this->nocredit != 0) echo '<input type="hidden" name="nocredit" value="1">';
	if ($this->mtext->course_hasinter) {
		foreach ($this->reqids as $r) {
			echo '<input type="hidden" name="req'.$r.'d" value="1">';
		}
	}
	echo '</form></div>';
} else {
//Not done and Valid, show Q&A and continue button
	
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery.metadata.setType("attr", "validate");
		jQuery("#continued_material").validate({
			ignore: [],
			errorPlacement: function(error, element) {
				error.appendTo("#continued-mat-verify-error");
			},
			highlight: function(element, errorClass, validClass) {
				jQuery("#continued-mat-verify").addClass("continued-verify-errorstate");
			},
			unhighlight: function(element, errorClass, validClass){
				jQuery("#continued-mat-verify").removeClass("continued-verify-errorstate");
			}
				
		});
	
	});
	
	
	</script><?php 
	echo '<form name="continued_material" id="continued_material" method="post" action="">';
	//continue button form
	?>
	<input type="hidden" name="token" value="<?php echo $this->token; ?>">
	<input type="hidden" name="courseid" value="<?php echo $this->mtext->course_id; ?>" validate="{remote: { url: '<?php echo JURI::base( true ); ?>/components/com_continued/helpers/chkmat.php', type: 'post'}, messages:{remote:'Please complete the material above before continuing'}}">
	
<div id="continued-mat-verify">
	<div id="continued-mat-verify-error"></div>	
	<div id="continued-mat-verify-hidden">
		<div style="width:5%;display:block;float:left;text-align:right;"><?php
				if ($this->mtext->course_haseval) {
					//continue to eval
					echo '<input type="hidden" name="gte" value="eval">';
				} else if ($this->mtext->course_haspre) {
					//continue to check page if no eval and pretest
					echo '<input type="hidden" name="gte" value="eval">';
				} else {
					//return to menu, no eval, no pretest, or done/exp/nonce
					echo '<input type="hidden" name="gte" value="return">';
				}
				?></div>
		<div style="width:5%;display:block;float:left;"></div>
		<div style="clear:both"></div>
	</div>
	<div id="continued-mat-verify-submit"><?php
				if ($this->mtext->course_haseval) {
					//continue to eval
					echo '<input name="Submit" id="Continue to Evaluation" value="Continue to Assessment"  type="submit"  class="cebutton">';
				} else if ($this->mtext->course_haspre) {
					//continue to check page if no eval and pretest
					echo '<input name="Submit" id="Continue to Evaluation" value="Continue"  type="submit"  class="cebutton">';
				} else {
					//return to menu, no eval, no pretest, or done/exp/nonce
					echo '<input name="Submit" id="Return" value="Return"  type="submit" class="cebutton">';
				}
				?></div>
</div>
				
	</form>
	
	<?php 
} 
?>
</div>