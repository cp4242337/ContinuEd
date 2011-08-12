<?php
/**
 * Hello Controller for Hello World Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Hello Hello Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class ContinuEdControllerCopyQ extends ContinuEdController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
	}

	/**
	 * display the copy page
	 * @return void
	 */
	function copy()
	{
		$model = $this->getModel('copyq');
		if ($model->copyQ()) {
			$msg = JText::_( 'Question(s) copied!' );
		} else {
			$msg = JText::_( "Don't Copy that Floppy" );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$courseid = JRequest::getVar('course');
		$link = 'index.php?option=com_continued&view=questions&course='.$courseid;
		$this->setRedirect($link, $msg);
	}
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$courseid = JRequest::getVar('course');
		$link = 'index.php?option=com_continued&view=questions&course='.$courseid;
		$this->setRedirect($link, $msg);
	}




}
?>
