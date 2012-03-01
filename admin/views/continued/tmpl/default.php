<?php defined('_JEXEC') or die('Restricted access');
?>
<ul>
	<li><a href="index.php?option=com_continued&amp;view=courses">Courses</a>
	- Edit courses, questions & answers, get reports for entire course</li>
	<li><a href="index.php?option=com_continued&amp;view=reports">Reports</a>
	- Get completition reports for all courese</li>
	<li><a href="index.php?option=com_continued&amp;view=cats">Categories</a>
	- Edit availble catagories/programs</li>
	<li><a href="index.php?option=com_continued&amp;view=provs">Providers</a>
	- Edit list of providers</li>
	<li><a class="active"
		href="index.php?option=com_continued&amp;view=certifs">Certificate
	Templates</a> - Edit certificate templates</li>
	<li><a class="active"
		href="index.php?option=com_continued&amp;view=certtypes">Certificate
	Types</a> - Edit list of certificate types</li>
	<li><a class="active"
		href="index.php?option=com_continued&amp;view=degrees">Certificate
	Degrees</a> - Edit certificate degree associations</li>

</ul>
This component requires Community Builder. CB must have a field named
cb_degree in order for the certificate function to work properly, which
degree goes with which certificate cna be setup under Certificate
Degrees. CB must also have a field named cb_licnum for the license
number to appear.
