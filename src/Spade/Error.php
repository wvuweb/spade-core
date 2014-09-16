<?php

/*!
 * Error Class
 *
 * Copyright (c) 2014 Dave Olsen
 *
 * Track any errors and write out messages as appropriate
 *
 */

namespace Spade;

use \Spade\Config;
use \Spade\Render;
use \Spade\Timer;

class Error {
	
	protected static $errors = array();
	
	/**
	 * Get a single error supplied by a key
	 * @param  {Integer}      the key to return the error for
	 * 
	 * @return {String}       the message associated with the given key
	 */
	public static function getError($key = 0) {
		return (isset(self::$errors[$key])) ? self::$errors[$key] : false;
	}
	
	/**
	 * Get all of the errors
	 *
	 * @return {Array}        the current list of errors
	 */
	public static function getErrors() {
		return self::$errors;
	}
	
	/**
	 * Get the data that will be included in the error template
	 * @param  {String}       the error message
	 * @param  {Array}        any extra submitted data to be included when loading the template
	 */
	public static function getTemplateData($errorMessage = "", $submittedData = array()) {
		
		// create the default data set and merge any submitted data
		$data = array();
		if (is_array($submittedData) && (count($submittedData) > 0)) {
			$data = array_merge($data, $submittedData);
		}
		
		// gather the error messages
		$data["errorMessages"] = array();
		$data["errorMessages"] = array_merge($data["errorMessages"], Error::getErrors());
		if (!empty($errorMessage)) {
			$data["errorMessages"][] = $errorMessage;
		}
		
		return $data;
		
	}
	
	/**
	 * Return if the errors array is empty
	 * 
	 * @return {Boolean}      the state of the errors array
	 */
	public static function hasErrors() {
		return (empty(self::$errors)) ? false : true;
	}
	
	/**
	 * Render an forbidden error with Mustache
	 * @param  {Array}        the data to be used when rendering the error
	 */
	public static function forbidden($data = array()) {
		
		// make sure it's an array
		if (!is_array($data)) {
			$data = array();
		}
		
		$data = array("hideActions" => true);
		
		if (!isset($data["errorMessages"])) {
			$data["errorMessages"] = self::getErrors();
		} else {
			$data["errorMessages"] = array_merge($data["errorMessages"], self::getErrors());
		}
		
		header('HTTP/1.0 403 Forbidden');
		print Render::template("exceptions/error",$data);
		exit;
		
	}
	
	/**
	 * Render a not found error with Mustache
	 * @param  {Array}        the data to be used when rendering the error
	 */
	public static function notFound($data = array()) {
		
		// make sure it's an array
		if (!is_array($data)) {
			$data = array();
		}
		
		$data = array("hideActions" => true);
		
		if (!isset($data["errorMessages"])) {
			$data["errorMessages"] = self::getErrors();
		} else {
			$data["errorMessages"] = array_merge($data["errorMessages"], self::getErrors());
		}
		
		header('HTTP/1.0 404 Not Found');
		print Render::template("exceptions/error",$data);
		exit;
		
	}
	
	/**
	 * Set the error message to the errors array
	 * @param  {String}       the error message
	 */
	public static function set($message = "",$key = "") {
		
		if (empty($message)) {
			self::write("you need to provide an error message when setting an error");
		}
		
		if (empty($key)) {
			self::$errors[] = $message;
		} else {
			self::$errors[$key] = $message;
		}
		
	}
	
	/**
	 * Write a error sans any sort of styling
	 * @param  {String}       the error message to be written out
	 */
	public static function write($string = "") {
		
		if (empty($string)) {
			$string = "there was a hiccup somewhere in the app";
		}
		
		header('HTTP/1.0 500 Internal Server Error');
		print $string;
		exit;
		
	}
	
}
