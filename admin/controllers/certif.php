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
class ContinuEdControllerCertif extends ContinuEdController
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
	 * display the edit form
	 * @return void
	 */
	function edit()
	{
		JRequest::setVar( 'view', 'certif' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$model = $this->getModel('certif');

		if ($model->store($post)) {
			$msg = JText::_( 'Certificate Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Certificate' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_continued&view=certifs';
		$this->setRedirect($link, $msg);
	}

	/**
	 * remove record(s)
	 * @return void

	 function remove()
	 {
		$model = $this->getModel('prov');
		if(!$model->delete()) {
		$msg = JText::_( 'Error: One or More Providers Could not be Deleted' );
		} else {
		$msg = JText::_( 'Provider(s) Deleted' );
		}

		$link = 'index.php?option=com_continued&view=provs';
		$this->setRedirect($link, $msg);
		}
		*/
	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled, All Members Deleted' );
		$link = 'index.php?option=com_continued&view=certifs';
		$this->setRedirect($link, $msg);
	}



}
?>
