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
}
new crowdflower;
