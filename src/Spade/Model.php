<?php

/*!
 * Database Class
 *
 * Copyright (c) 2014 Dave Olsen
 *
 * Initialize pheasant so we can connect to the database
 *
 */

namespace Spade;

use \Pheasant;
use \Spade\Config;
use \Spade\Timer;
use \Illuminate\Database\Capsule\Manager as Capsule;
use \Illuminate\Events\Dispatcher;
use \Illuminate\Container\Container;

class Model {

	/**
	* Set-up the database connection
	*/
	public static function init() {
	// Database connection
	$capsule = new Capsule;

	$defaultUser   = Config::getOption("user");
	$defaultPass   = Config::getOption("pass");
	$readwriteUser = Config::getOption("readwrite.user");
	$readwritePass = Config::getOption("readwrite.pass");

	$user = (!$readwriteUser || empty($readwriteUser)) ? $defaultUser : $readwriteUser;
	$pass = (!$readwritePass || empty($readwritePass)) ? $defaultPass : $readwritePass;

	$capsule->addConnection([
			'driver'    => 'mysql',
			'host'      => Config::getOption("host"),
			'database'  => Config::getOption("name"),
			'username'  => $user,
			'password'  => $pass,
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
	]);

	$capsule->setEventDispatcher(new Dispatcher(new Container));

	// Make this Capsule instance available globally via static methods... (optional)
	$capsule->setAsGlobal();

	// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
	$capsule->bootEloquent();
}

}
