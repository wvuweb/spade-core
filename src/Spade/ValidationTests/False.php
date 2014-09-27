<?php

/*!
 * False Test
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\ValidationTests;

use \Spade\Error;
use \Spade\ValidationTest;

class False extends ValidationTest {
	
	/**
	 * Check to see if the item is false
	 * Will return true: "0", "false", "off" and "no"
	 *
	 * yaml use:
	 *   ModelName:
	 *     itemName: 
	 *        - False: [error message]
	 *
	 * @param  {String}        the item to be checked
	 * @param  {String}        the name to be included in messaging
	 * @param  {Array}         any properties that might be used in the validation
	 *
	 * @return {Boolean}       whether it passed or not
	 */
	public function test($itemValue, $itemName = "", $props = array()) {
		
		$message = $props["string"];
		
		// check the given value
		$itemValue = filter_var($itemValue, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
		if (is_null($itemValue) || ($itemValue)) {
			$defaultMessage = "The value ".$itemName." should be false.";
			$message = (!empty($message)) ? str_replace("{{ itemName }}", $itemName, $message) : $defaultMessage;
			Error::set($message);
			return false;
		}
		
		return true;
		
	}
	
}
