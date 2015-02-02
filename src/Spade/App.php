<?php

/*!
 * Config Class
 *
 * Copyright (c) 2014 Dave Olsen
 *
 * Reads in a YAML configuration file and makes the attributes available to the project 
 *
 */

namespace Spade;

use \Spade\Error;
use \Spade\FileUtil;
use \Spade\Render;
use \Spade\Timer;
use \Symfony\Component\Yaml\Yaml;

class App {
	
	protected static $instance;
	
	/**
	* Returns the app instance for use elsewhere in the project
	*
	* @return {Object}        instance of app
	*/
	public static function get() {
		
		if (is_null(self::$instance)) {
			Render::error("the app instance hasn't been set");
		}
		
		return self::$instance;
		
	}
	
	/**
	* Runs the app
	*/
	public static function run($options = array()) {
		
		self::$instance->run($options);
		
	}
	
	/**
	* Sets the app object to be used elsewhere
	* @param {Object}        instance of app
	*/
	public static function set($app) {
		
		self::$instance = $app;
		
	}
	
}
