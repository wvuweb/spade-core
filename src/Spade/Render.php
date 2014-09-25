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
		$templateEngine                     = Config::getOption("templateEngine");
		$templatesDir                       = Config::getOption("baseDir").Config::getOption("templatesDir");
		$partialsDir                        = Config::getOption("baseDir").Config::getOption("partialsDir");
		
		// set-up the file system instance & string instance based on selected template engine
		if ($templateEngine == "mustache") {
			
			$mustacheOptions                    = array();
			$mustacheOptions["loader"]          = new \Mustache_Loader_FilesystemLoader($templatesDir);
			$mustacheOptions["partials_loader"] = new \Mustache_Loader_FilesystemLoader($partialsDir);
			self::$instance                     = new \Mustache_Engine($mustacheOptions);
			
			self::$instanceString               = new \Mustache_Engine;
			
		} else if ($templateEngine == "twig") {
			
			$twigLoader = new \Twig_Loader_Filesystem(array($templatesDir,$partialsDir));
			self::$instance new \Twig_Environment($twigLoader);
			
			$twigLoader = new \Twig_Loader_String();
			self::$instanceString = new \Twig_Environment($twigLoader);
			
		} else if ($templateEngine == "haml") {
			
			$twigLoader     = new \Twig_Loader_Filesystem(array($templatesDir,$partialsDir));
			$twigInstance   = new \Twig_Environment($twigLoader);
			$haml           = new \MtHaml\Environment('twig', array('enable_escaper' => false));
			$hamlLoader     = new \MtHaml\Support\Twig\Loader($haml, $twigInstance->getLoader());
			$twigInstance->setLoader($hamlLoader);
			$twigInstance->addExtension(new \MtHaml\Support\Twig\Extension());
			self::$instance = $twigInstance;
			
			$twigLoader     = new \Twig_Loader_String();
			$twigInstance   = new \Twig_Environment($twigLoader);
			$haml           = new \MtHaml\Environment('twig', array('enable_escaper' => false));
			$hamlLoader     = new \MtHaml\Support\Twig\Loader($haml, $twigInstance->getLoader());
			$twigInstance->setLoader($hamlLoader);
			$twigInstance->addExtension(new \MtHaml\Support\Twig\Extension());
			self::$instanceString = $twigInstance;
			
		} else {
			
			print "the templateEngine option ".$templateEngine." is invalid...";
			exit;
			
		}
		
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