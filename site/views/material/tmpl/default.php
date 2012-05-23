<div id="continued">
<?php // no direct access
$cecfg = ContinuEdHelper::getConfig();
defined('_JEXEC') or die('Restricted access');
echo '<h2 class="componentheading">'.$this->mtext->course_name.'</h2>';

//Gve error message if they tryed to jummop over material staright to the eval
if ($this->jumpedover) echo '<div class="continued-error">Please complete all material before proceeding</div>';
//Show Material
if ($this->mtext->course_material) {
//material in course info -- LEGACY
	echo $this->mtext->course_material;
} else if (count($this->matpages) == 1) {
//only 1 material page
	echo $this->matpages[0]->mat_content;
} else {
//multiple material pages
	foreach ($this->matpages as $mp) {
		echo '<b>'.$mp->mat_title.'</b><br />';
		if ($mp->mat_desc) echo $mp->mat_desc.'<br /><br />'; 
		echo '<a href="index.php?option=com_continued&view=matpage&token='.$this->token;
		if ($this->nocredit != 0) echo '&nocredit=1';
		echo '&Itemid='.JRequest::getVar('Itemid').'&matid='.$mp->mat_id.'&course='.$mp->mat_course.'" class="cebutton">';
		if ($this->mpdata[$mp->mat_id]->mu_status == 'complete') echo 'Review';
		else if ($this->mpdata[$mp->mat_id]->mu_status == 'incomplete') echo 'Resume';
		else echo 'Begin';
		echo '</a><br /><br />';
	}
}

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
	//Show Q&A Entry box
	if ($this->mtext->course_qanda == "submit") {
		?>
		<script type="text/javascript" src="media/com_continued/scripts/jquery.js"></script>
		<script type="text/javascript">jQuery.noConflict();</script>
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
						<input type="submit" name="button" id="submitter" class="qabox-btn" />
						<span style="clear:both"><!-- spanner --></span>
					</form>
				</div>
				<div id="qabox-msg"></div>
			</div>
			<div id="qabox-bot"></div>
		</div>
		<?php 
	}
	echo '<form name="continued_material" id="continued_material" method="post" action="" onsubmit="return isDone();">';
	//set up hidden variable for inter required question status
	if ($this->mtext->course_hasinter) {
		foreach ($this->reqids as $r) {
			echo '<input type="hidden" name="req'.$r.'d" value="';
			if (in_array($r,$this->reqans)) echo '1'; 
			else echo '0';
			echo '">';
		}
	}
	
	//continue button form
	?>
	<input type="hidden" name="token" value="<?php echo $this->token; ?>">
	<div align="center">
	<table id="agreet" style="border: medium none; padding: 5px;" border="0"
		cellpadding="0" cellspacing="0" width="500" align="center">
		<tbody>
			<tr>
				<td colspan="2" align="center">
				<div id="cbError"
					style="color: rgb(136, 0, 0); font-size: 10pt; font-weight: bold; display: none; position: relative;">Please complete the material above before continuing<br>
				<br>
				</div>
				</td>
	
			</tr>
			<tr>
				<td colspan="2" align="center"><br>
	
				<?php
				if ($this->mtext->haseval) {
					//continue to eval
					echo '<input type="hidden" name="gte" value="eval">';
					echo '<input name="Submit" id="Continue to Evaluation" value="Continue to Assessment"  type="submit"  class="cebutton">';
				} else if ($this->mtext->course_haspre) {
					//continue to check page if no eval and pretest
					echo '<input type="hidden" name="gte" value="eval">';
					echo '<input name="Submit" id="Continue to Evaluation" value="Continue"  type="submit"  class="cebutton">';
				} else {
					//return to menu, no eval, no pretest, or done/exp/nonce
					echo '<input type="hidden" name="gte" value="return">';
					echo '<input name="Submit" id="Return" value="Return"  type="submit" class="cebutton">';
				}
				?></td>
			</tr>
	
		</tbody>
	</table>
	<br>
	</div>
	</form>
	<script type="text/javascript">  
				var numtohave = <?php echo $this->numreq; ?>;
				var numans=0;
				function getCheckedValue(radioObj) {
					if(!radioObj)
						return "";
					var radioLength = radioObj.length;
					if(radioLength == undefined)
						if(radioObj.checked)
							return radioObj.value;
						else
							return "";
					for(var i = 0; i < radioLength; i++) {
						if(radioObj[i].checked) {
							return radioObj[i].value;
						}
					}
					return "";
				}
				
				function isNCheckedR(elem, helperMsg,cnt,msgl){
					var isit = false;
					for (var i=0; i<cnt; i++) {
						if(elem[i].checked){ isit = true; }
					}
					if (isit == false) {
						document.getElementById(msgl).innerHTML = helperMsg;
						elem[0].focus(); // set the focus to this input
						return true;
					}
					document.getElementById(msgl).innerHTML = '';
						return false;
				}
				function isDone() {
					var lyr = document.getElementById('cbError');
					var tbl = document.getElementById('agreet');
					if (numans>=numtohave) {
						lyr.style.display='none'; 
						tbl.style.border='none';
						return true;
					} else { 
						lyr.style.display='inline'; 
						tbl.style.border='thick #880000 solid';
						return false; 
					}
				}
				
	
	 
			</script> 
	<?php 
} 
?>
</div>