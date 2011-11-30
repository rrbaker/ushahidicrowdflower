<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Install/uninstall methods for the Crowdflower Plugin
 *
 * @package    crowdflower
 * @author     Rob Baker
 * @copyright  Rob Baker
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */
class Ushahidicrowdflower_Install {
	
	/* Constructor to load the shared database library */
	public function __construct() {
		$this->db =  new Database();
	}

	/* Creates the required columns for the Crowdflower Plugin */
	public function run_install() 
	{
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".Kohana::config('database.default.table_prefix')."crowdflower`
			(
				id int(11) unsigned NOT NULL AUTO_INCREMENT,
				crowdflower_jobid int(10) DEFAULT NULL,
				crowdflower_apikey varchar(50) DEFAULT NULL,
				PRIMARY KEY (`id`)
			);");

		//remove existing entry from the scheduler
		ORM::factory('scheduler')
			->where('scheduler_name',"Crowdflower")
			->delete_all();

		// Create entry for the scheduler
		$scheduler = new Scheduler_Model();
		$scheduler->scheduler_name = "Crowdflower";             
		$scheduler->scheduler_controller = "s_crowdflower";
		$scheduler->save();     


	}


	/* Drops the CrowdFlower database table */
	public function uninstall() 
	{
		$this->db->query("
			DROP TABLE ".Kohana::config('database.default.table_prefix')."crowdflower;
		");


	}
}
