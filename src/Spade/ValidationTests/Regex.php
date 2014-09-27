<?php

/*!
 * Regex Test
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\ValidationTests;

use \Spade\Error;
use \Spade\ValidationTest;

class Regex extends ValidationTest {
	
	/**
	 * Check to see if the item is matches the given regex
	 *
	 * yaml use:
	 *   ModelName:
	 *     itemName: 
	 *        - Regex: [pattern]
	 * or
	 *   ModelName:
	 *     itemName: 
	 *        - Regex: 
	 *            pattern: [pattern]
	 *            match: false
	 *            message: [error message]
	 *
	 * @param  {String}        the item to be checked
	 * @param  {String}        the name to be included in messaging
	 * @param  {Array}         any properties that might be used in the validation
	 *
	 * @return {Boolean}       whether it passed or not
	 */
	public function test($itemValue, $itemName = "", $props = array()) {
		
		$regex = (isset($props["pattern"])) ? $props["pattern"] : $props["string"];
		$match = (!isset($props["match"])) ? true : $props["match"];
		
		if (!filter_var($regex, FILTER_VALIDATE_REGEXP)) {
			Error::set($regex." is an invalid regex");
			return false;
		}
		
		$value = preg_match($regex,$itemValue);
		
		if ($value !== $match) {
			$defaultMessage = "The value ".$itemName." is not valid.";
			$message = (isset($props["message"]) && ($props["message"] != "~")) ? str_replace("{{ itemName }}", $itemName, $props["message"]) : $defaultMessage;
			Error::set($message);
			return false;
		}
		
		return true;
		
	}
	
}
