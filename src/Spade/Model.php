<?php

/*!
 * Database Class
 *
 * Copyright (c) 2014 Dave Olsen
 *
 * Initialize pheasant so we can connect to the database
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
		$user = (empty(Config::getOption("readwrite.user")) ? Config::getOption("user") : Config::getOption("readwrite.user");
		$pass = (empty(Config::getOption("readwrite.pass")) ? Config::getOption("pass") : Config::getOption("readwrite.pass");
		$port = Config::getOption("port");
		Pheasant::setup("mysql://".$user.":".$pass."@".$host.":".$port."/".$name);
		
	}
	
}