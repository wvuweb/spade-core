<?php

/*!
 * Basic Page Methods
 *
 * Copyright (c) 2014 Dave Olsen
 *
 * Just some sugar for "page" pages
 *
 */

namespace Spade\ControllerTypes;

use \Spade\Controller;

class Page extends Controller {
	
	/**
	* Get the flash message from the session
	*/
	public function getFlash($flash) {
		
		return (isset($_SESSION["slim.flash"]) && isset($_SESSION["slim.flash"][$flash])) ? $_SESSION["slim.flash"][$flash] : false;
		
	}
	
}