<?php defined('SYSPATH') or die('No direct script access.');
/**
 * CrowdFlower HTTP Post Controller
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com> 
 * @package	   Ushahidi - http://source.ushahididev.com
 * @module	   CrowdFlower Controller	
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license	   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
*/

class S_Crowdflower_Controller extends Controller {
	private $crowdflower_apikey;
	private $crowdflower_jobid;
	
	public function __construct() {
		$settings = ORM::factory("crowdflower")->find(1);
		if ($settings->loaded) {
			$this->crowdflower_apikey = $settings->crowdflower_apikey;
			$this->crowdflower_jobid = $settings->crowdflower_jobid;
		}
	}

	public function index()
	{
		$page = 1;
		$have_results = TRUE;
		while($have_results == TRUE AND $page <=2)
		{
			$url = "https://api.crowdflower.com/v1/jobs/{$this->crowdflower_jobid}/units.json?key={$this->crowdflower_apikey}";

			$curl_handle = curl_init();
			curl_setopt($curl_handle,CURLOPT_URL,$url);
			curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,4); //Incase CF goes down set timeout to 4 secs
			curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1); //Set curl to store data in variable instead of print
			$buffer = curl_exec($curl_handle);
			curl_close($curl_handle);
					
			$have_results = $this->add_reports($buffer); //if FALSE, we will drop out of the loop

			$page++;
		}
	}	

	/**
	* Adds reports in JSON format to the database
	* @param string $data - CF JSON results
	*/
	private function add_reports($data)
	{
		$reports = json_decode($data, false);

		foreach($reports as $report)
		{
			//Save the Report location
			$location = new Location_Model();
			$location->longitude = $report->{'longitude'};
			$location->latitude = $report->{'latitude'};
			$location->location_name = $report->{'location_city'};
			$location->save();

			// Save CF result as Report
			$incident = new Incident_Model();
			$incident->location_id = $location->id;
			// $incident->id = $report->{'id'};
			$incident->incident_title = date("Y-m-d H:i:s",time());
			$incident->incident_description = $report->{'sms_text'};
			$incident->incident_date = date("Y-m-d H:i:s",time());
			$incident->incident_dateadd = date("Y-m-d H:i:s",time());
			$incident->incident_active = 1;
			$incident->incident_verified = 1;
			$incident->save();

			// Save Incident Category
			$categories = explode(",",$report->{'categories'});
			foreach($categories as $category)
			{
				$report_category_id = ORM::factory("category")
					->where("category_title", $category)
					->find();
					if ($report_category_id->loaded)
					{
						$incident_category = new Incident_Category_Model();
						$incident_category->incident_id = $incident->id;
						$incident_category->category_id = $report_category_id->id;
						$incident_category->save();
					}
			}
		}

	}

}
