<?php

/*!
 * Email Test
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\ValidationTests;

use \Spade\Error;
use \Spade\Render;
use \Spade\ValidationTest;

class Email extends ValidationTest {
	
	/**
	 * Check to see if the item is a valid email address
	 *
	 * yaml use:
	 *   ModelName:
	 *     itemName: 
	 *        - Email: [error message]
	 *
	 * @param  {String}        the item to be checked
	 * @param  {String}        the name to be included in messaging
	 * @param  {Array}         any properties that might be used in the validation
	 *
	 * @return {Boolean}       whether it passed or not
	 */
	public function test($itemValue, $itemName = "", $props = array()) {
		
		// make sure this is a string
		if (!is_string($itemValue)) {
			Error::set("a non-string value for ".$itemName." was passed to the email test");
			return false;
		}
		
		// set-up the message
		$message = $props["string"];
		
		if (!filter_var($itemValue, FILTER_VALIDATE_EMAIL)) {
			$defaultMessage = "The value ".$itemValue." is not a valid email address.";
			$message = (!empty($message)) ? str_replace("{{ itemName }}", $itemName, $message) : $defaultMessage;
			Error::set($message);
			return false;
		}
		
		return true;
		
	}
	
}
