<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$config = ContinuEdHelper::getConfig();

$user =& JFactory::getUser();

echo '<h2 class="componentheading">'.$this->cinfo->course_name.'</h2>';
echo $this->cinfo->course_purchaseinfo;
if (!$user->id) {
	echo '<p align="center"><span style="color:#800000;font-weight:bolder;">'.$config->LOGIN_MSG.'</span></p>';
} else {
	echo '<table width="100%" border="0"><tr>';
	$formtoken=JHTML::_( 'form.token' );
	if ($config->paypal && !$this->cinfo->course_purchaseco) {
		echo '<td align="center">';
		echo '<form action="" method="post" name="paypalcheckout" id="paypalcheckout">';
		echo '<input type="image" name="submit" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" />';
		echo '<input type="hidden" name="layout" value="ppsubpay" />';
		echo $formtoken;
		echo '</form>';
		echo '</td>';
	}
	if ($config->redemption) {
		echo '<td align="center">';
		echo '<form action="" method="post" name="redeemcodecheckout" id="redeemcodecheckout">';
		echo '<div>';
		echo '<input type="text" name="redeemcode" class="field_purchase required" /><br />';
		echo '<input type="submit" name="submit" value="Redeem Code" class="cebutton" />';
		echo '<input type="hidden" name="layout" value="redeem" />';
		echo $formtoken;
		echo '</form>';
		echo '</td>';
	}
	echo '</tr></table>';
}

?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery("#redeemcodecheckout").validate({
			errorClass:"uf_error"
	    });	
	});
</script>
</div>
