<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$config = ContinuEdHelper::getConfig();

$session=JFactory::getSession();
$user =& JFactory::getUser();
echo '<h2 class="componentheading">'.$this->cinfo->course_name.'</h2>';
?>
<form action="<?php echo JRoute::_('index.php?option=com_continued&view=purchase&layout=ppverify&purchaseid='.$this->pid.'&course='.$this->cinfo->course_id); ?>" method="POST">
			<p style="padding-left:10px;">Your payment has been verified, please press <b>Complete Payment</b> to finalize your payment and charge your account.<br /><br /><b>Order Total: $</b>
			<?php  echo $session->get('currencyCodeType').$session->get('TotalAmount'); ?>
			<br /><br />
			<input type="submit" value="Complete Payment" class="cebutton"  /><br /><br /></p>
		</form>

</div>
