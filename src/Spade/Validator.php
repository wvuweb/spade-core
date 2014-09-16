<?php

/*!
 * Validator Class
 *
 * Copyright (c) 2014 Dave Olsen
 *
 * Handle system-wide validation of elements
 *
 */

namespace Spade;

use \Pheasant;
use \Spade\App;
use \Spade\Config;
use \Spade\Render;
use \Spade\Timer;
use \Spade\ValidationTests;

class Validator {
	
	/**
	* Check for errors
	*/
	public static function checkForErrors($page = "") {
		$app = App::get();
		if (Error::hasErrors()) {
			$errors = Error::getErrors();
			$flashMessage = "You have the following errors: <ul>";
			foreach ($errors as $message) {
				$flashMessage .= "<li> ".$message."</li>";
			}
			$flashMessage .= "</ul>";
			$app->flash('error', $flashMessage);
			$app->redirect('/'.$page.'/');
		}
	}
	
	/**
	 * Before create let's make sure we validate the data and populate the session id
	 * @param  {String}        the value of the field to be tested
	 * @param  {String}        the name of the field to be tested
	 * @param  {String}        the model name of the field to be tested
	 */
	public static function validateItem($testValue, $fieldName, $modelName) {
		
		$tests = Config::getOption("validations.".$modelName.".".$fieldName);
		if ((array_key_exists("NotBlank",$tests[0]) && empty($testValue)) || !empty($testValue)) {
			foreach ($tests[0] as $testName => $testProps) {
				$validationTestName = "\\Spade\\ValidationTests\\".$testName;
				$validationTest     = new $validationTestName;
				if (is_string($testProps)) {
					$testProps = array("string" => $testProps);
				}
				$validationTest->test($testValue, $fieldName, $testProps);
			}
		}
		
	}
	
	/**
	 * Before create let's make sure we validate the data and populate the session id
	 * @param  {Object}        the session object
	 * @param  {Array}         an item:props key value pair
	 */
	public static function validateObject($object, $modelName) {
		
		$fields  = array();
		$options = Config::getOptions();
		foreach ($options as $option => $values) {
			if (strpos($option,"validations.".$modelName) !== false) {
				$bits = explode(".",$option);
				$fields[] = $bits[2];
			}
		}
		
		foreach ($fields as $fieldName) {
			self::validateItem($object->$fieldName, $fieldName, $modelName);
		}
		
	}
	
}