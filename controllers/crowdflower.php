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

class Crowdflower_Controller extends Controller {
	private $crowdflower_apikey;
	private $crowdflower_jobid;
	
	public function __construct() {
		$settings = ORM::factory("crowdflower_settings")->find(1);
		if ($settings->loaded) {
			$this->crowdflower_apikey = $settings->crowdflower_apikey;
			$this->crowdflower_jobid = $settings->crowdflower_jobid;
		}
	}

	$url = "https://api.crowdflower.com/v1/jobs/$crowdflower_jobid/units/.json?=$crowdflower_apikey";

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_UPLOAD, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: $content_type"));
	curl_setopt($ch, CURLOPT_INFILE, fopen("/tmp/crowd.csv",'r'));
	curl_setopt($ch, CURLOPT_READFUNCTION, create_function('$ch, $fd, $size', 'return fread($fd,$size);'));
	curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file));
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch,CURLOPT_URL,$url);
	$response = curl_exec($ch);
	$info = curl_getinfo($ch);
}