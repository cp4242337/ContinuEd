<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cecfg = ContinuEdHelper::getConfig();
echo '<h2 class="componentheading">'.$this->cinfo->course_name.'</h2>';
echo $this->cinfo->course_purchaseinfo;
echo '<form action="" method="post">';
echo '<div align="center">';
echo '<input type="image" name="submit" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" />';
echo '<input type="hidden" name="layout" value="subpay" />';
echo JHTML::_( 'form.token' );
echo '</div>';
echo '</form>';
?>
</div>
