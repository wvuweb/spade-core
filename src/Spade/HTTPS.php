<?php

/*!
 * HTTPS Class
 *
 * Copyright (c) 2014 Dave Olsen
 *
 * Basically just makes sure we're serving everything over HTTPS if we don't do this check in Apache
 *
 */

namespace Spade;

use \Spade\Config;
use \Spade\Render;
use \Spade\Timer;

class HTTPS {
	
	/**
	* Check to see if the forwarded protocol is https
	*/
	public static function check() {
		
		if (Config::getOption("enableHTTPS")) {
			if ($_SERVER['HTTP_X_FORWARDED_PROTO'] != "https") {
				header('location:https://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]); exit;
			}
			
		}
		
	}
	
}
