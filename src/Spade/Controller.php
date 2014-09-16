<?php

/*!
 * Controller Class
 *
 * Copyright (c) 2014 Dave Olsen
 *
 * Provides the base logic for the controllers
 *
 */

namespace Spade;

use \Spade\App;
use \Spade\Models\Status;
use \Spade\Render;
use \Spade\Timer;

class Controller {
	
	protected $app;
	
	/**
	* Initialize the app var
	*/
	public function __construct() {
		$this->app = App::get();
	}
	
	/**
	* Get the flash message from the session
	*/
	public function getFlash($flash) {
		
		return (isset($_SESSION["slim.flash"]) && isset($_SESSION["slim.flash"][$flash])) ? $_SESSION["slim.flash"][$flash] : false;
		
	}
	
}
