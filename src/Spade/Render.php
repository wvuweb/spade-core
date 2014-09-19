<?php

/*!
 * Render Class
 *
 * Copyright (c) 2014 Dave Olsen
 *
 * Initializes and renders various views
 *
 */

namespace Spade;

use \Spade\Config;
use \Spade\Console;
use \Spade\Error;
use \Spade\Timer;

class Render {
	
	protected static $instance;
	protected static $instanceString;
	
	/**
	* Set-up default vars
	*/
	public static function init() {
		
		// set-up config vars
		$templatesDir                       = Config::getOption("baseDir").Config::getOption("templatesDir");
		$partialsDir                        = Config::getOption("baseDir").Config::getOption("partialsDir");
		
		// set-up the file system loader
		$mustacheOptions                    = array();
		$mustacheOptions["loader"]          = new \Mustache_Loader_FilesystemLoader($templatesDir);
		$mustacheOptions["partials_loader"] = new \Mustache_Loader_FilesystemLoader($partialsDir);
		self::$instance                     = new \Mustache_Engine($mustacheOptions);
		
		// set-up the default instance
		self::$instanceString               = new \Mustache_Engine;
		
	}
	
	/**
	 * Render an error with Mustache
	 * @param  {String}       the error message
	 * @param  {Array}        any extra submitted data to be included when loading the template
	 */
	public static function error($errorMessage = "", $submittedData = array()) {
		
		if (php_sapi_name() == 'cli') {
			
			Console::writeError($errorMessage);
			
		} else {
			
			// populate the data to be used in the template
			$data = Error::getTemplateData($errorMessage, $submittedData);
			
			// render the template
			header('HTTP/1.0 500 Internal Server Error');
			print self::template("exceptions/error",$data);
			exit;
			
		}
		
	}
	
	/**
	 * Render an forbidden error with Mustache
	 * @param  {String}       the error message
	 * @param  {Array}        any extra submitted data to be included when loading the template
	 */
	public static function errorForbidden($errorMessage = "", $submittedData = array()) {
		
		// populate the data to be used in the template
		$data = Error::getTemplateData($errorMessage, $submittedData);
		
		// render the template
		header('HTTP/1.0 403 Forbidden');
		print self::render("exceptions/error",$data);
		exit;
		
	}
	
	/**
	 * Render a not found error with Mustache
	 * @param  {String}       the error message
	 * @param  {Array}        any extra submitted data to be included when loading the template
	 */
	public static function errorNotFound($errorMessage = "", $submittedData = array()) {
		
		// populate the data to be used in the template
		$data = Error::getTemplateData($errorMessage, $submittedData);
		
		// render the template
		header('HTTP/1.0 404 Not Found');
		print self::template("exceptions/error",$data);
		exit;
		
	}
	
	/*
	 * Render an info message
	 */
	public static function info($message) {
		
		if (php_sapi_name() == 'cli') {
			Console::writeInfo($message);
		} else {
			print $message."<br />";
		}
		
	}
	
	/*
	 * Render JSON
	 */
	public static function json($data) {
		
		$app = App::get();
		
		// see if there was a callback too
		$callback = $app->request->get('callback');
		
		header('Access-Control-Allow-Origin: *');
		$callback = ($callback) ? preg_replace('/[^a-z0-9$_]/si', '', $callback) : false;
		header('Content-Type: ' . ($callback ? 'application/javascript' : 'application/json') . ';charset=UTF-8');
		print ($callback ? $callback . '(' : '') . json_encode($data) . ($callback ? ')' : '');
		exit;
		
	}
	
	/*
	 * Render a line
	 */
	public static function line($message) {
		
		if (php_sapi_name() == 'cli') {
			Console::writeLine($message);
		} else {
			print $message."<br />";
		}
		
	}
	
	/*
	 * Render an object
	 */
	public static function object($object) {
		print "<pre>";
		print_r($object);
		print "</pre>";
	}
	
	/*
	 * Render a string
	 */
	public static function string($string,$data = array(),$print = true) {
		
		$r = self::$instanceString->render($string,$data);
		
		if ($print) {
			print $r;
		} else {
			return $r;
		}
		
	}
	
	/*
	 * Render a given template
	 */
	public static function template($template,$data = array(),$print = true) {
		
		$r = self::$instance->render($template,$data);
		
		if ($print) {
			print $r;
		} else {
			return $r;
		}
		
	}
	
	/*
	 * Render a warning message
	 */
	public static function warning($message) {
		
		if (php_sapi_name() == 'cli') {
			Console::writeWarning($message);
		} else {
			print $message."<br />";
		}
		
	}
	
}