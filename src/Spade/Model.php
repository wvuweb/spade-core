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
		
		// get various user info
		$defaultUser   = Config::getOption("user");
		$defaultPass   = Config::getOption("pass");
		$readwriteUser = Config::getOption("readwrite.user");
		$readwritePass = Config::getOption("readwrite.user");
		
		// set-up the database connection info
		$name = Config::getOption("name");
		$host = Config::getOption("host");
		$user = (!$readwriteUser || empty($readwriteUser)) ? $defaultUser : $readwriteUser;
		$pass = (!$readwritePass || empty($readwritePass)) ? $defaultPass : $readwritePass;
		$port = Config::getOption("port");
		Pheasant::setup("mysql://".$user.":".$pass."@".$host.":".$port."/".$name);
		
	}
	
}