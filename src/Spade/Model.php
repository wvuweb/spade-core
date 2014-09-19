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
		$name = Config::getOption("name");
		$host = Config::getOption("host");
		$user = Config::getOption("user");
		$pass = Config::getOption("pass");
		$port = Config::getOption("port");
		Pheasant::setup("mysql://".$user.":".$pass."@".$host.":".$port."/".$name);
		
	}
	
}