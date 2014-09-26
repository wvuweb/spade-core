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
	function __construct($app) {
		
		// skip the timestamp check for this app
		Session::check($app,true);
		
	}
	
}