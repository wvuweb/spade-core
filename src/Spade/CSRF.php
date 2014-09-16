<?php

/*!
 * CSRF Class
 *
 * Copyright (c) 2014 Dave Olsen
 *
 * Syntactic sugar for NoCSRF
 *
 */

namespace Spade;

use \NoCSRF;
use \Spade\Render;
use \Spade\Timer;

class CSRF {
	
	/**
	* Generate the CSRF token for the page view
	*/
	public static function generateToken() {
		return NoCSRF::generate('csrf_token');
	}
	
	/**
	* Check the CSRF token
	*/
	public static function checkToken($checkPostData = true) {
		$checkData = ($checkPostData) ? $_POST : $_GET;
		if (!NoCSRF::check('csrf_token', $checkData, false, 60*20, false)) {
			header('HTTP/1.0 403 Forbidden');
			print "forbidden";
			exit;
		}
	}
	
}
