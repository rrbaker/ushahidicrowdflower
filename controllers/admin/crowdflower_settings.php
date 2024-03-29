<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Crowdflower Settings Controller
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module	   Crowdflower Settings Controller	
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
* 
*/

class Crowdflower_Settings_Controller extends Admin_Controller {
	public function index() 
	{
		$this->template->this_page = 'addons';
		
		// Standard Settings View
		$this->template->content = new View("admin/plugins_settings");
		$this->template->content->title = "CrowdFlower Settings";
		
		// Settings Form View
		$this->template->content->settings_form = new View("crowdflower/admin/crowdflower_settings");
		
		// setup and initialize form field names
		$form = array(
			'crowdflower_apikey' => '',
			'crowdflower_jobid' => ''
			);
			//	Copy the form as errors, so the errors will be stored with keys
			//  corresponding to the form field names
			$errors = $form;
			$form_error = FALSE;
			$form_saved = FALSE;

			// check, has the form been submitted, if so, setup validation
			if ($_POST) {			
				// Instantiate Validation, use $post, so we don't overwrite $_POST
				// fields with our own things
				$post = new Validation($_POST);

			// Add some filters
			$post->pre_filter('trim', TRUE);

			// Add some rules, the input field, followed by a list of checks, carried out in order
			$post->add_rules('crowdflower_apikey', 'length[0,100]');
			$post->add_rules('crowdflower_jobid', 'length[0,100]');

			// Test to see if things passed the rule checks
			if ($post->validate())
			{
				// Yes! everything is valid
				$settings = ORM::factory('crowdflower', 1);
				$settings->crowdflower_apikey = $post->crowdflower_apikey;
				$settings->crowdflower_jobid = $post->crowdflower_jobid;
				$settings->save();

				// Transform and roll out
				$form_saved = TRUE;

				// repopulate the form fields
				$form = arr::overwrite($form, $post->as_array());
			}

				// No! We have validation errors, we need to show the form again,
				// with the errors
				else
			{
				// repopulate the form fields
				$form = arr::overwrite($form, $post->as_array());

				// populate the error fields, if any
				$errors = arr::overwrite($errors, $post->errors('crowdflower'));
				$form_error = TRUE;
			}
		}
		else
		{
			// Retrieve Current Settings
			$settings = ORM::factory('crowdflower', 1);

			$form = array
			(
			'crowdflower_apikey' => $settings->crowdflower_apikey,
			'crowdflower_jobid' => $settings->crowdflower_jobid
			);
		}
		
		// Pass the $form on to the settings_form variable in the view
		$this->template->content->settings_form->form = $form;
		
		// Other variables
		$this->template->content->errors = $errors;
		$this->template->content->form_error = $form_error;
		$this->template->content->form_saved = $form_saved;
	}
}
