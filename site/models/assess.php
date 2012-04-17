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
		$query = 'SELECT course_id,course_cat,course_name,course_hascertif,course_cataloglink,course_defaultcertif,course_passmsg,course_failmsg,course_allowrate,course_evaltype,course_haspre,course_haseval FROM #__ce_courses WHERE course_id = '.$courseid;
		$db->setQuery( $query );
		$mtext = $db->loadObject();
		return $mtext;
	}
	function loadAnswers($courseid,$token,$etype,$haspre,$haseval)
	{
		$cecfg = ContinuEdHelper::getConfig();
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$pass = 'pass';

		//Grade PostTest
		$score = -1;
		if ($haseval) {
			$query  = 'SELECT DISTINCT q.*,a.*,o.opt_text,o.opt_expl,o.opt_correct ';
			$query .= 'FROM #__ce_questions as q ';
			$query .= 'LEFT JOIN #__ce_evalans as a ON q.q_id = a.question ';
			$query .= 'LEFT JOIN #__ce_questions_opts AS o ON a.answer = o.opt_id ';
			$query .= 'WHERE q.q_type != "message" && q.q_area = "post" && q.q_cat = "assess" && q.q_course = '.$courseid.' && a.userid="'.$userid.'" && a.tokenid = "'.$token.'" ';
			$query .= 'GROUP BY q.q_id ';
			$query .= 'ORDER BY q.q_part ASC , q.ordering ASC ';
			$db->setQuery( $query );
			$qdata = $db->loadObjectList();
			$numcorrect=0; $totq=0;
			foreach ($qdata as $qanda) {
				if ($qanda->q_type == 'multi') {
					if ($qanda->q_cat == 'assess' && $qanda->opt_correct == 1) { $numcorrect++; }
					$totq++;
				}
			}
			if ($totq != 0) $score = ($numcorrect / $totq) * 100;
			else $score=100;
			if ($score >= $cecfg->EVAL_PERCENT || $etype == 'unassess') $pass = 'pass'; else $pass = 'fail';
		}

		//Grade PreTest
		$prescore=-1;
		if ($haspre) {
			$query  = 'SELECT DISTINCT q.*,a.*,o.opt_text,o.opt_expl,o.opt_correct ';
			$query .= 'FROM #__ce_questions as q ';
			$query .= 'LEFT JOIN #__ce_evalans as a ON q.q_id = a.question ';
			$query .= 'LEFT JOIN #__ce_questions_opts AS o ON a.answer = o.opt_id ';
			$query .= 'WHERE q.q_type != "message" && q.q_area = "pre" && q.q_cat = "assess" && q.q_course = '.$courseid.' && a.userid="'.$userid.'" && a.tokenid = "'.$token.'" ';
			$query .= 'GROUP BY q.q_id ';
			$query .= 'ORDER BY q.q_part ASC , q.ordering ASC ';
			$db->setQuery( $query );
			$qdatap = $db->loadObjectList();
			$numcorrect=0; $totq=0;
			foreach ($qdatap as $qanda) {
				if ($qanda->q_type == 'multi') {
					if ($qanda->q_cat == 'assess' && $qanda->opt_correct == 1) { $numcorrect++; }
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
			ContinuedHelper::trackViewed("asm",$courseid,$token);
			ContinuedHelper::endSession($courseid,$token,$prescore,$score,$pass);
		}
	}

	function checkDegree($courseid) {
		$usergroup=$this->getUserGroup();
		$usercert=$this->getCertifAssoc($usergroup);
		$coursecerts=$this->getCourseCerts($courseid);
		$cando = in_array($usercert,$coursecerts);
		return $cando;
	}
	function getCourseCerts($courseid)
	{
		$db =& JFactory::getDBO();
		$q='SELECT cd_cert FROM #__ce_coursecerts WHERE cd_course = "'.$courseid.'"';
		$db->setQuery($q);
		$cn = $db->loadResultArray();
		return $cn;
	}
	function getCertifAssoc($group)
	{
		$db =& JFactory::getDBO();
		//determine which certif
		$q='SELECT gc_cert FROM #__ce_groupcerts WHERE gc_group = "'.$group.'"';
		$db->setQuery($q);
		$cn = $db->loadResult();
		return $cn;
	}
	function getUserGroup() {
		$user =& JFactory::getUser();
		$userid = $user->id;
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__ce_usergroup WHERE userg_user="'.$userid.'"';
		$db->setQuery( $query );
		$res = $db->loadObject();
		return $res->userg_group;
	}
	function getUserCertif($dc) {
		$cecfg = ContinuEdHelper::getConfig();
		$usergroup = $this->getUserGroup();
		$certype = $this->getCertifAssoc($usergroup);
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__ce_certifs WHERE crt_id="'.$certype.'"';
		$db->setQuery( $query );
		$res = $db->loadObject();
		return $res->crt_name;
	}
	function getCourseCertif($certype) {
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__ce_certifs WHERE crt_id="'.$certype.'"';
		$db->setQuery( $query );
		$res = $db->loadObject();
		return $res->crt_name;
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
