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

use \Spade\Controller;
use \Spade\Session;

class Admin extends Controller {
	
	/**
	* Class start-up functions used by all of the steps
	*/
	function __construct() {
		
		// skip the timestamp check for this app
		$this->data["showActions"] = true;
		Session::check(true);
		
	}
	
}