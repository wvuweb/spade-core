<?php

/*!
 * Database Class
 *
 * Copyright (c) 2014 Dave Olsen
 *
 * Just some sugar to slim down index.php
 *
 */

namespace Spade;

use \Pheasant;
use \Spade\Config;
use \Spade\Render;
use \Spade\Timer;

class Model {
	
	/**
	* Set-up the database connection
	*/
	public static function init() {
		
		// set-up the database connection info
		$database   = Config::getOption("database");
		$dbhost     = Config::getOption("dbhost");
		$dbuser     = Config::getOption("dbuser");
		$dbpassword = Config::getOption("dbpassword");
		Pheasant::setup("mysql://".$dbuser.":".$dbpassword."@".$dbhost.":3306/".$database);
		
	}
	
}