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

class Config {

	protected static $v           = "0.0.1";
	protected static $options     = array();
	protected static $cleanValues = array();

	/**
	* Get the value associated with an option from the Config
	* @param  {String}       the name of the option to be checked
	*
	* @return {String/Boolean} the value of the get or false if it wasn't found
	*/
	public static function getOption($optionName = "") {

		if (empty($optionName)) {
			return false;
		}

		if (self::has(self::$options,$optionName)){
			return self::get(self::$options,$optionName);
		} else {
			return false;
		}

	}

	/**
	* Get the options set in the config
	*
	* @return {Array}        the options from the config
	*/
	public static function getOptions() {
		return self::$options;
	}

	/**
	* Adds the config options to a var to be accessed from the rest of the system
	* @param  {String}        the base directory for the applications
	*/
	public static function init($baseDir = "") {

		// make sure a base dir was supplied
		if (empty($baseDir)) {
			Error::write("need a base directory to initialize the config class...");
		}

		// normalize the baseDir
		$baseDir = FileUtil::normalizePath($baseDir);

		// double-check the default config file exists
		if (!is_dir($baseDir)) {
			Error::write("make sure ".$baseDir." exists...");
		}

		// set the baseDir option
		self::$options["baseDir"] = ($baseDir[strlen($baseDir)-1] == DIRECTORY_SEPARATOR) ? $baseDir : $baseDir.DIRECTORY_SEPARATOR;

		// set the version number for spade
		self::$options["v"] = self::$v;

		// finalize the config path
		$configPath = self::$options["baseDir"]."configs/";

		// double-check the default config file exists
		if (!file_exists($configPath)) {
			Error::write("make sure {baseDir}/configs/ exists...");
		}

		// iterate over the config dir and load all .yml files
		foreach (glob($configPath."*.yml") as $filename){

			$data = Yaml::parse(file_get_contents($filename));
			self::loadOptions($data);
		}

	}

	/**
	* Check to see if the given array is an associative array
	* @param  {Array}        the array to be checked
	* @return {Boolean}      whether it's an associative array
	*/
	protected static function isAssoc($array) {
		return (bool) count(array_filter(array_keys($array), 'is_string'));
	}

	/**
	* Load the options into self::$options
	* @param  {Array}        the data to be added
	* @param  {String}       any addition that may need to be added to the option key
	*/
	protected static function loadOptions($data,$parentKey = "") {

		foreach ($data as $key => $value) {

			$key = $parentKey.trim($key);

			if ($key == "defaults"){
				self::$options[$key] = $value;
			}
			else if (is_array($value) && self::isAssoc($value) && ($key == "environments.".Config::getOption("environment"))) {
				self::loadOptions($value);
			} else if (is_array($value) && self::isAssoc($value)) {
				self::loadOptions($value,$key.".");
			} else if (is_array($value) && !self::isAssoc($value)) {
				self::$options[$key] = $value;
			} else {
				self::$options[$key] = trim($value);
			}

		}

	}

	/**
	* Add an option and associated value to the base Config
	* @param  {String}       the name of the option to be added
	* @param  {String}       the value of the option to be added
	*
	* @return {Boolean}      whether the set was successful
	*/
	public static function setOption($optionName = "", $optionValue = "") {

		if (empty($optionName) || empty($optionValue)) {
			return false;
		}

		if (!array_key_exists($optionName,self::$options)) {
			self::$options[$optionName] = $optionValue;
			return true;
		}

		return false;

	}

	/**
	* Update an option and associated value to the base Config
	* @param  {String}       the name of the option to be updated
	* @param  {String}       the value of the option to be updated
	*
	* @return {Boolean}      whether the update was successful
	*/
	public static function updateOption($optionName = "", $optionValue = "") {

		if (empty($optionName) || empty($optionValue)) {
			return false;
		}

		if (array_key_exists($optionName,self::$options)) {
			self::$options[$optionName] = $optionValue;
			return true;
		}

		return false;

	}

	/**
	* Check if an item exists in an array using "dot" notation.
	* @param  {Array}   $array
	* @param  {String}  $key
	* @return {Boolean}
	*/
	public static function has($array, $key) {
		if (empty($array) || is_null($key)) {
			return false;
		}

		if (array_key_exists($key, $array)) {
			return true;
		}

		foreach (explode('.', $key) as $segment) {
			if ( ! is_array($array) || ! array_key_exists($segment, $array)) {
				return false;
			}

			$array = $array[$segment];
		}
		return true;
	}

	/**
	* Get an item from an array using "dot" notation.
	* @param  {Array }  $array
	* @param  {String}  $key
	* @param  {Mixed}   $default
	* @return {Mixed}
	*/
	public static function get($array, $key, $default = null) {
		if (is_null($key)){
			return $array;
		}

		if (isset($array[$key])){
			return $array[$key];
		}

		foreach (explode('.', $key) as $segment) {
			if ( ! is_array($array) || ! array_key_exists($segment, $array)) {
				return value($default);
			}
			$array = $array[$segment];
		}
		return $array;
	}

}
