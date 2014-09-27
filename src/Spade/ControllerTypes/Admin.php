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
use \Spade\Session;

class Admin extends Page {
	
	/**
	* Class start-up functions used by all admin pages
	*/
	function __construct() {
		
		parent::__construct();
		
		// skip the timestamp check for this app
		$this->data["showActions"] = true;
		Session::check(true);
		
	}
	
}