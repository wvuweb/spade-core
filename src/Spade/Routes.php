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
			preg_match("/(get|post)[\s]{1,2}([\S]{1,})( as ([A-z0-9]{1,})(\:(.*))?)?/i",$route,$match);
			
			// figure out the parts
			$httpMethod = $match[1];
			$route      = $match[2];
			$controller = (isset($match[4])) ? $match[4] : ucfirst(str_replace("/","",$route));
			$action     = (isset($match[6])) ? $match[6] : strtolower($httpMethod);
			
			// setup the routes
			// example: $app->get('/', 'Pages:home');
			$method = "\\".$namespace."\Controllers\\".$controller.":".$action;
			$app->$httpMethod($route, $method);
			
		}
		
		// set the app so it's available anywhere
		App::set($app);
		
	}
	
}
