<?php

/*!
 * WVU Email Test
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\ValidationTests;

use \Egulias\EmailValidator\EmailValidator;
use \Spade\Error;
use \Spade\ValidationTest;

class WvuEmail extends ValidationTest {
	
	/**
	 * Check to see if the item is a valid email address
	 *
	 * yaml use:
	 *   ModelName:
	 *     itemName: 
	 *        - WvuEmail: [error message]
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
			$defaultMessage = $itemName." is not a valid email address";
			$message = (!empty($message)) ? str_replace("{{ itemName }}", $itemName, $message) : $defaultMessage;
			Error::setError($message);
			return false;
		}
		
		// check the domain against valid WVU domains
		$domain     = $emailValidator->parser->getParsedDomainPart();
		$wvuDomains = array("mail.wvu.edu","mix.wvu.edu","systems.wvu.edu","wvu.edu");
		if (in_array($domain,$wvuDomains)) {
			$defaultMessage = $itemName." is not a valid WVU email address";
			$message = (!empty($message)) ? str_replace("{{ itemName }}", $itemName, $message) : $defaultMessage;
			Error::setError($message);
			return false;
		}
		
		return true;
		
	}
	
}
