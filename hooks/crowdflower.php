<?php defined('SYSPATH') or die('No direct script access.');
/**
 * CloudVox Hook
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module	   Mobile Hoook
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class crowdflower {
	
	/**
	 * Registers the main event add method
	 */
	public function __construct()
	{
		// Hook into routing right after controller object is created
		Event::add('system.post_controller', array($this, 'add'));
	}
	
	/* Adds all the events to the main Ushahidi application */
	public function add() {	
		// Add an Admin Sub-Nav Link
		Event::add('ushahidi_action.nav_admin_settings', array($this, '_settings_link'));

		// Add the events on the main controller only
		if (Router::$controller == 'crowdflower_settings') {
			switch (Router::$method) {
				// Hook into the main dashboard
				case 'index':
					plugin::add_stylesheet('crowdflower/media/css/crowdflower');
					break;
			}
		}


	}

	
	// Settings section subpage link
	public function _settings_link() {
		$this_sub_page = Event::$data;
		echo ($this_sub_page == "contacts") ? Kohana::lang('ui_admin.contacts') : 
			"<a href=\"".url::site()."admin/crowdflower_settings\">"."CrowdFlower"."</a>";
	}

	public function _get_reports() {
		include Kohana::find_file('libraries/phpflickr','phpFlickr');
		
		$f = new phpFlickr(Kohana::config('flickrwijit.flick_api_key'));
		//enable caching
		return $f;
	}
	
	public function _create_report() {
		$message_id = "";
		// Get Message ID from Querystring
		if (isset($_GET['mid']) AND !empty($_GET['mid'])) {
			$message_id = $_GET['mid'];
		}
		// Get Message ID from previously saved report
		else {
			$incident_id = Event::$data;
			if ($incident_id) {
				$message = ORM::factory('message')
					->where('incident_id', $incident_id)
					->find();
				if ($message->loaded) {
					$message_id = $message->id;
				}
			}
		}
		
		if ($message_id) {
			$crowdflower_message = ORM::factory("crowdflower_message")
				->where("message_id", $message_id)
				->find();
			if ($crowdflower_message->loaded) {
				$play = View::factory('crowdflower/play_report');
				$play->message_id = $message_id;
				$play->caller_id = $crowdflower_message->caller_id;
				$play->location_wav = urlencode(url::base()."media/uploads/crowdflower/messages/".$crowdflower_message->message_location);
				$play->report_wav = urlencode(url::base()."media/uploads/crowdflower/messages/".$crowdflower_message->message_report);
				$play->render(TRUE);
			}
		}
	}
	
	
	public function _view_report()
	{
		$message_id = "";
		$incident_id = Event::$data;
		if ($incident_id)
		{
			$message = ORM::factory('message')
				->where('incident_id', $incident_id)
				->find();
			if ($message->loaded)
			{
				$message_id = $message->id;
			}
		}
		
		if ($message_id)
		{
			$crowdflower_message = ORM::factory("crowdflower_message")
				->where("message_id", $message_id)
				->find();
			if ($crowdflower_message->loaded)
			{
				$play = View::factory('crowdflower/play');
				$play->message_id = $message_id;
				$play->caller_id = $crowdflower_message->caller_id;
				$play->location_wav = urlencode(url::base()."media/uploads/crowdflower/messages/".$crowdflower_message->message_location);
				$play->report_wav = urlencode(url::base()."media/uploads/crowdflower/messages/".$crowdflower_message->message_report);
				$play->render(TRUE);
			}
		}
	}
}

new crowdflower;
