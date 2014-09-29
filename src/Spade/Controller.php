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
	protected $data;
	
	/**
	* Initialize the app var
	*/
	public function __construct() {
		
		$this->app  = App::get();
		$this->data = array();
		
	}
	
}
