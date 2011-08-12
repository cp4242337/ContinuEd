<?php // no direct access
defined('_JEXEC') or die('Restricted access');
echo '<div class="componentheading">'.$this->mtext['cname'].'</div>';
$res = $mainframe->triggerEvent('onInsertVid',array(& $this->mtext['material']));
$res = $mainframe->triggerEvent('onInsertQuestion',array(& $res[0]));
echo $res[0];
global $cecfg;
if ($this->mtext['course_qanda'] == "submit") {
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
					<input type="hidden" name="courseid" value="<?php echo $this->mtext['id']; ?>" />
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
?>
<form name="continued_material" id="continued_material" method="post" action="" onsubmit="return isDone();">
<?php 
if ($this->mtext['course_hasinter']) {
	foreach ($this->reqids as $r) {
		echo '<input type="hidden" name="req'.$r.'d" value="';
		if (in_array($r,$this->reqans)) echo '1'; 
		else echo '0';
		echo '">';
	}
}

?>
<input type="hidden" name="gte" value="true">
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
			if ($this->mtext['haseval']) {
				echo '<input name="Submit" id="Continue to Evaluation" value="Continue to Assessment"  type="image" src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/btn_continueeval.png">';
			} else if ($this->mtext['course_haspre']) {
				echo '<input name="Submit" id="Continue to Evaluation" value="Continue"  type="image" src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/btn_continue.png">';
			} else {
				echo '<input name="Submit" id="Return" value="Return"  type="image" src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/btn_return.png">';
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
