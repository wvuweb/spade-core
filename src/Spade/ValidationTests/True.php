<?php

/*!
 * True Test
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\ValidationTests;

use \Spade\Error;
use \Spade\ValidationTest;

class True extends ValidationTest {
	
	/**
	 * Check to see if the item is true
	 * Will return true: "1", "true", "on" and "yes"
	 *
	 * yaml use:
	 *   ModelName:
	 *     itemName: 
	 *        - True: [error message]
	 *
	 * @param  {String}        the item to be checked
	 * @param  {String}        the name to be included in messaging
	 * @param  {Array}         any properties that might be used in the validation
	 *
	 * @return {Boolean}       whether it passed or not
	 */
	public function test($itemValue, $itemName = "", $props = array()) {
		
		$message = $props["string"];
		
		$itemValue = filter_var($itemValue, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
		
		if (is_null($itemValue) || !$itemValue) {
			$defaultMessage = "The value ".$itemName." should be true.";
			$message = (!empty($message)) ? str_replace("{{ itemName }}", $itemName, $message) : $defaultMessage;
			Error::setError($message);
			return false;
		}
		
		return true;
		
	}
	
}
