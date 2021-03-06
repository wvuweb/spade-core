<?php

/*!
 * Url Test
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\ValidationTests;

use \Spade\Error;
use \Spade\ValidationTest;

class Url extends ValidationTest {
	
	/**
	 * Check to see if the item is a valid URL
	 *
	 * yaml use:
	 *   ModelName:
	 *     itemName: 
	 *        - Url: [error message]
	 *
	 * @param  {String}        the item to be checked
	 * @param  {String}        the name to be included in messaging
	 * @param  {Array}         any properties that might be used in the validation
	 *
	 * @return {Boolean}       whether it passed or not
	 */
	public function test($itemValue, $itemName = "", $props = array()) {
		
		$message = $props["string"];
		
		if (!filter_var($itemValue, FILTER_VALIDATE_URL)) {
			$defaultMessage = "The value ".$itemName." is not a valid URL.";
			$message = (!empty($message)) ? str_replace("{{ itemName }}", $itemName, $message) : $defaultMessage;
			Error::set($message);
			return false;
		}
		
		return true;
		
	}
	
}
