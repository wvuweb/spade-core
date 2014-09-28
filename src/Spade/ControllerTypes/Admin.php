<?php

/*!
 * Basic Admin Methods
 *
 * Copyright (c) 2014 Dave Olsen
 *
 * Just some sugar for "admin" pages
 *
 */

namespace Spade\ControllerTypes;

use \Spade\ControllerTypes\Page;
use \Spade\CSRF;
use \Spade\Session;

class Admin extends Page {
	
	/**
	* Class start-up functions used by all admin pages
	*/
	function __construct() {
		
		parent::__construct();
		
		// skip the timestamp check for this app
		Session::check(true);
		$this->data["showActions"] = true;
		$this->data["csrfToken"]   = CSRF::generateToken();
		
	}
	
}