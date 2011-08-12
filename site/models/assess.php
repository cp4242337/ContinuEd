<?php
/**
 *
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 *
 */
class ContinuEdModelAssess extends JModel
{

	function getCourse($courseid)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT id,cname,hascertif,cataloglink,defaultcertif,course_passmsg,course_failmsg,course_allowrate,course_evaltype,course_haspre,haseval FROM #__ce_courses WHERE id = '.$courseid;
		$db->setQuery( $query );
		$mtext = $db->loadAssoc();
		return $mtext;
	}
	function loadAnswers($courseid,$token,$etype,$haspre,$haseval)
	{
		global $cecfg;
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$pass = 'pass';

		//Grade PostTest
		$score = -1;
		if ($haseval) {
			$query  = 'SELECT DISTINCT q.*,a.*,o.opttxt,o.optexpl,o.correct ';
			$query .= 'FROM #__ce_questions as q ';
			$query .= 'LEFT JOIN #__ce_evalans as a ON q.id = a.question ';
			$query .= 'LEFT JOIN #__ce_questions_opts AS o ON a.answer = o.id ';
			$query .= 'WHERE q.q_area = "post" && q.qcat = "assess" && q.course = '.$courseid.' && a.userid="'.$userid.'" && a.sessionid = "'.$sessionid.'" ';
			$query .= 'GROUP BY q.id ';
			$query .= 'ORDER BY q.qsec ASC , q.ordering ASC ';
			$db->setQuery( $query );
			$qdata = $db->loadAssocList();
			$numcorrect=0; $totq=0;
			foreach ($qdata as $qanda) {
				if ($qanda['qtype'] == 'multi') {
					if ($qanda['qcat'] == 'assess' && $qanda['correct'] == 1) { $numcorrect++; }
					$totq++;
				}
			}
			if ($totq != 0) $score = ($numcorrect / $totq) * 100;
			else $score=100;
			if ($score >= $cecfg['EVAL_PERCENT'] || $etype == 'unassess') $pass = 'pass'; else $pass = 'fail';
		}

		//Grade PreTest
		$prescore=-1;
		if ($haspre) {
			$query  = 'SELECT DISTINCT q.*,a.*,o.opttxt,o.optexpl,o.correct ';
			$query .= 'FROM #__ce_questions as q ';
			$query .= 'LEFT JOIN #__ce_evalans as a ON q.id = a.question ';
			$query .= 'LEFT JOIN #__ce_questions_opts AS o ON a.answer = o.id ';
			$query .= 'WHERE q.q_area = "pre" && q.qcat = "assess" && q.course = '.$courseid.' && a.userid="'.$userid.'" && a.sessionid = "'.$sessionid.'" ';
			$query .= 'GROUP BY q.id ';
			$query .= 'ORDER BY q.qsec ASC , q.ordering ASC ';
			$db->setQuery( $query );
			$qdatap = $db->loadAssocList();
			$numcorrect=0; $totq=0;
			foreach ($qdatap as $qanda) {
				if ($qanda['qtype'] == 'multi') {
					if ($qanda['qcat'] == 'assess' && $qanda['correct'] == 1) { $numcorrect++; }
					$totq++;
				}
			}
			if ($totq != 0) $prescore = ($numcorrect / $totq) * 100;
			else $prescore=100;
		}
		$this->addCompleted($pass,$courseid,$score,$userid,$token,$prescore);
		return $qdata;
	}
	function addCompleted($pass,$courseid,$score,$userid,$token,$prescore) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$qa = 'SELECT * FROM  #__ce_track WHERE step="asm" && user="'.$userid.'" && sessionid="'.$sessionid.'" && token="'.$token.'" && course="'.$courseid.'"';
		$db->setQuery( $qa );
		$modata = $db->loadAssoc();
		if (!$modata) {
			$q1 = 'UPDATE #__ce_completed SET crecent = 0 WHERE user ="'.$userid.'" && course = "'.$courseid.'"';
			$db->setQuery($q1);
			$db->query();
			$q  = 'INSERT INTO #__ce_completed (user,cpercent,cpass,course,fsessionid,cmpl_prescore) ';
			$q .= 'VALUES ("'.$userid.'","'.$score.'","'.$pass.'","'.$courseid.'","'.$sessionid.'","'.$prescore.'")';
			$db->setQuery( $q );
			$db->query();
			$q3  = 'INSERT INTO #__ce_track (user,course,step,sessionid,token,track_ipaddr) ';
			$q3 .= 'VALUES ("'.$userid.'","'.$courseid.'","asm","'.$sessionid.'","'.$token.'","'.$_SERVER['REMOTE_ADDR'].'")';
			$db->setQuery( $q3 );
			$db->query();
		}
	}

	function checkSteped($courseid,$token) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query = 'SELECT * FROM #__ce_track WHERE step="chk" && user="'.$userid.'" && sessionid="'.$sessionid.'" && token="'.$token.'" && course="'.$courseid.'"';
		$db->setQuery($query);
		$data = $db->loadAssoc();
		return count($data);
	}
	function checkDegree($courseid) {
		global $cecfg;
		if ($cecfg['NEEDS_DEGREE']) {
			$userdegree=$this->getUserDegree();
			$usercert=$this->getCertifAssoc($userdegree);
			$coursecerts=$this->getCourseDegrees($courseid);
			$cando = in_array($usercert,$coursecerts);
			return $cando;
		} else {
			return true;
		}
	}
	function getCourseDegrees($courseid)
	{
		$db =& JFactory::getDBO();
		$q='SELECT cert_id FROM #__ce_coursecerts WHERE course_id = "'.$courseid.'"';
		$db->setQuery($q);
		$cn = $db->loadResultArray();
		return $cn;
	}
	function getCertifAssoc($degree)
	{
		$db =& JFactory::getDBO();
		//determine which certif
		$q='SELECT cert FROM #__ce_degreecert WHERE degree = "'.$degree.'"';
		$db->setQuery($q);
		$cn = $db->loadResult();
		return $cn;
	}
	function getUserDegree() {
		$user =& JFactory::getUser();
		$userid = $user->id;
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__comprofiler WHERE user_id="'.$userid.'"';
		$db->setQuery( $query );
		$futext = $db->loadAssoc();
		return $futext['cb_degree'];
	}
	function getUserCertif($dc) {
		global $cecfg;
		if ($cecfg['NEEDS_DEGREE']) {
			$userdeg = $this->getUserDegree();
			$certype = $this->getCertifAssoc($userdeg);
		} else {
			$certype=$dc;
		}
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__ce_certifs WHERE crt_id="'.$certype.'"';
		$db->setQuery( $query );
		$futext = $db->loadAssoc();
		return $futext['crt_name'];
	}
	function getCourseCertif($certype) {
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__ce_certifs WHERE crt_id="'.$certype.'"';
		$db->setQuery( $query );
		$futext = $db->loadAssoc();
		return $futext['crt_name'];
	}
	function addRating($courseid,$rating) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$q1 = 'INSERT INTO #__ce_track (user,course,step,sessionid,token,track_ipaddr) VALUES ("'.$userid.'","'.$courseid.'","rate","'.$sessionid.'","'.$rating.'","'.$_SERVER['REMOTE_ADDR'].'")';
		$db->setQuery( $q1 );
		$db->query();
		//average rating form ce_track
		$q2 = 'SELECT AVG(token) FROM #__ce_track WHERE course = "'.$courseid.'" && step="rate" GROUP BY course';
		$db->setQuery( $q2 );
		$newrate = $db->loadResult();
		//update rating in ceourse
		$q3 = 'UPDATE #__ce_courses SET course_rating = "'.$newrate.'" WHERE id = "'.$courseid.'"';
		$db->setQuery( $q3 );
		$db->query();

	}


	function checkRated($courseid) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query = 'SELECT * FROM #__ce_track WHERE step="rate" && user="'.$userid.'" && course="'.$courseid.'"';
		$db->setQuery($query);
		$data = $db->loadAssoc();
		return count($data);
	}

}
