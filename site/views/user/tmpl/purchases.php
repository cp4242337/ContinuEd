<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cecfg = ContinuEdHelper::getConfig();
if (!$this->print) {
	?>
<h2 class="componentheading"><a
	href="index.php?option=com_continued&view=userce&print=1&tmpl=component"
	target="_blank"><img src="components/com_continued/printButton.png"
	border="0" align="right"></a><?php echo "CE Purchases"; ?></h2>
<?php 
} else {
	echo '<div class="componentheading">CE Purchases<a href="javascript:print()"><img src="components/com_continued/printButton.png" border="0"> Print</a> </div>';
}

echo '<p>Here are the accredited activities you\'ve purchased.</p>';

if ($this->catalog) {
	echo '<table width="100%" class="zebra">';
	echo '<thead><tr><th>Program</th><th>Date</th><th>Method</th><th>Status</th><th>Cost</th></tr></thead><tbody>';
	foreach ($this->catalog as $course) {
		echo '<tr><td valign="top"><b>';
		echo $course->course_certifname;
		echo '</b></td><td valign="top"> '.date("F d, Y", strtotime($course->purchase_time)).'</td><td>';
		switch ($course->purchase_type) {
			case "paypal": echo "PayPal"; break;
			case "redeem": echo "Code"; break;
			case "admin": echo "Admin"; break;
			case "google": echo "Google"; break;
		}
		echo '</td><td>';
		switch ($course->purchase_status) {
			case "notyetstarted": echo "Not Yet Started"; break;
			case "verified": echo "Assessment"; break;
			case "canceled": echo "Canceled"; break;
			case "accepted": echo "Accepted"; break;
			case "pending": echo "Pending"; break;
			case "started": echo "Started"; break;
			case "denied": echo "Denied"; break;
			case "refunded": echo "Refunded"; break;
			case "failed": echo "Failed"; break;
			case "pending": echo "Pending"; break;
			case "reversed": echo "Reversed"; break;
			case "canceled_reversal": echo "Cancelled Dispute"; break;
			case "expired": echo "Expired"; break;
			case "voided": echo "Voided"; break;
			case "completed": echo "Completed"; break;
			case "dispute": echo "Dispute"; break;
		}
		echo '</td><td>';
		echo $course->course_purchaseprice;
		echo '</td></tr>';
	}
	echo '</tbody></table>';
} else echo '<p>At this time, you have not purchased any CE programs.</p>';
?>
	</div>