<?php

/*!
 * Session Class
 *
 * Copyright (c) 2014 Dave Olsen
 *
 * Session helpers
 *
 */

namespace Spade;

use \Spade\App;
use \Spade\Config;
use \Spade\Models\Session as SessionStore;
use \Spade\SessionHandlers;
use \Spade\Render;
use \Spade\Timer;

class Session {

	/*
	* Start up the session with some specific options for the session cookies
	* Also set-up the database as the session store
	*/
	public static function init() {

		// set-up a database session handler
		$databaseSession = new SessionHandlers\DatabaseHandler();

		// add db data
		$databaseSession->setDbDetails();
		$databaseSession->setDbTable('session_handler_table');
		session_set_save_handler(array($databaseSession, 'open'),
		                         array($databaseSession, 'close'),
		                         array($databaseSession, 'read'),
		                         array($databaseSession, 'write'),
		                         array($databaseSession, 'destroy'),
		                         array($databaseSession, 'gc'));

		// the following prevents unexpected effects when using objects as save handlers.
		register_shutdown_function('session_write_close');

		// figure out the environment
		$hostname = Config::getOption("hostname");
		$secure   = Config::getOption("enableHTTPS");
		$httponly = Config::getOption("enableHTTPS");

		session_set_cookie_params(0,"/",$hostname,$secure,$httponly);
		session_start();

	}

	/**
	* Check that the session has been started
	* @param  {Boolean}      should we check the 20 min. timeout?
	*/
	public static function check($skipTimeoutCheck = false) {

		$app = App::get();

		// get the session ID and look to see if the session username and ID return a record
		if (!isset($_SESSION["user_id"])) {
			$app->flash('error', 'your session expired');
			$app->redirect('/login/');
		}

		// make sure the user hasn't been logged in for more than 20 minutes without an action
		if (!$skipTimeoutCheck && ((time() - (20*60)) > $_SESSION["timestamp"])) {
			self::remove(App::get());
			$app->flash('error', 'your session expired');
			$app->redirect('/login/');
		}

		// make sure the user has an active session in the DB
		$userID    = $_SESSION["user_id"];
		$sessionID = session_id();

		$sessions = SessionStore::where('user_id','=',$user_id)->where('session_id', '=', $session_id)->get();
		if ($sessions->count() != 1) {
			$app->flash('error', 'something is wrong with your session');
			$app->redirect('/login/');
		}

		// update the session information
		$_SESSION["timestamp"] = time();

		return true;

	}

	/**
	* Get an option from the session
	*/
	public static function getOption() {

		if (empty($optionName)) {
			return false;
		}

		if (array_key_exists($optionName,$_SESSION)) {
			return $_SESSION[$optionName];
		}

		return false;

	}

	/**
	* Remove a session
	* @param  {Object}       the app object
	*/
	public static function removeDBSession() {

		$app = App::get();

		// set-up the default vars
		$user_id    = $_SESSION["user_id"];
		$session_id = session_id();

		// find the session to remove
		try {
			$session = SessionStore::where('user_id','=',$user_id)->where('session_id', '=', $session_id)->first()->get();

		} catch (\Exception $e) {
			$app->flash('error', 'something is wrong with your session');
			$app->redirect('/login/');
		}

		// remove the session from the db
		$session->delete();

	}

	/**
	* Remove the browser cookie related to the session
	*/
	public static function removeCookie() {

		// figure out the environment
		$hostname = Config::getOption("hostname");
		$secure   = Config::getOption("enableHTTPS");
		$httponly = Config::getOption("enableHTTPS");

		setcookie(session_name(),"",time()-3600,"/",$hostname,$secure,$httponly);

	}

}
