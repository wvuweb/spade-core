<?php

/*!
 * Email Test
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\ValidationTests;

use \Egulias\EmailValidator\EmailValidator;
use \Spade\Error;
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
			Error::setError("a non-string value for ".$itemName." was passed to the email test");
			Error::render();
		}
		
		// set-up the message
		$message = $props["string"];
		
		// validate the address
		$emailValidator = new EmailValidator;
		if (!$emailValidator->isValid($itemValue)) {
			$defaultMessage = "The value ".$itemName." is not a valid email address.";
			$message = (!empty($message)) ? str_replace("{{ itemName }}", $itemName, $message) : $defaultMessage;
			Error::setError($message);
			return false;
		}
		
		return true;
		
	}
	
}
