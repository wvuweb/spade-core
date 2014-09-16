<?php

/*!
 * Routes Class
 *
 * Copyright (c) 2014 Dave Olsen
 *
 * The app routes
 *
 */

namespace Spade;

use \Slim\Slim;
use \Spade\App;
use \Spade\Render;
use \Spade\Timer;

class Routes {
	
	/**
	* Set-up the routes for the pages
	*/
	public static function init() {
		
		// make sure a namespace was set-up
		$namespace = Config::getOption("namespace");
		if (!$namespace) {
			Render::error("you must provide a namespace when invoking routes");
		}
		
		$app = new Slim();
		
		// get the routes and iterate on them
		$routes = Config::getOption("routes");
		foreach ($routes as $route) {
			
			// parse the route
			preg_match("/(get|post)[\s]{1,2}(/(.*))( as (.*)(\:(.*))?)?/i",$route,$matches);
			
			// figure out the parts
			$httpMethod = $match[1];
			$route      = $match[2];
			$controller = (isset($match[5])) ? $match[5] : strtoupper(str_replace("/","",$route));
			$action     = (isset($match[7])) ? $match[7] : strtolower($httpMethod);
			
			// setup the routes
			$app->$httpMethod($route, '\\'.$namespace.'\Controllers\\'.$controller.':'.$action);
			
		}
		
		// set the app so it's available anywhere
		App::set($app);
		
	}
	
}
