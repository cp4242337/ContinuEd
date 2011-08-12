<?php
/**
 * Hello World table class
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


/**
 * Hello Table class
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class TableCourse extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;

	/**
	 * @var string
	 */
	var $cname = null;
	var $certifname = null;
	var $provider = null;
	var $cdesc = null;
	var $keywords = null;
	var $frontmatter = null;
	var $material = null;
	var $startdate = null;
	var $enddate = null;
	var $ccat = null;
	var $evalparts = null;
	var $published = null;
	var $credits = null;
	var $faculty = null;
	var $actdate = null;
	var $cneprognum = null;
	var $cpeprognum = null;
	var $previmg = null;
	var $prereq = null;
	var $ordering = null;
	var $hascertif = null;
	var $cataloglink = null;
	var $hasfm = null;
	var $hasmat = null;
	var $defaultcertif = null;
	var $haseval = null;
	var $access = null;
	var $course_catlink = null;
	var $course_catmenu = null;
	var $course_catexp = null;
	var $course_compcourse = null;
	var $course_viewans = null;
	var $course_failmsg = null;
	var $course_passmsg = null;
	var $course_allowrate = null;
	var $course_rating = null;
	var $course_catrate = null;
	var $course_subtitle = null;
	var $course_searchable = null;
	var $course_evaltype = null;
	var $course_extlink = null;
	var $course_exturl = null;
	var $course_haspre = null;
	var $course_preparts = null;
	var $course_changepre = null;
	var $course_purchase = null;
	var $course_purchaselink = null;
	var $course_purchasesku = null;
	var $course_learntype = null;
	var $course_hasinter = null;
	var $course_qanda = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableCourse(& $db) {
		parent::__construct('#__ce_courses', 'id', $db);
	}
}
?>
