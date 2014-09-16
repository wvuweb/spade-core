<?php

/*!
 * NotBlank Test
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\ValidationTests;

use \Spade\Error;
use \Spade\ValidationTest;

class NotBlank extends ValidationTest {
	
	/**
	 * Check to see if the item is not blank. Set an error if it is
	 *
	 * yaml use:
	 *   ModelName:
	 *     itemName: 
	 *        - NotBlank: [error message]
	 *
	 * @param  {String}        the item to be checked
	 * @param  {String}        the name to be included in messaging
	 * @param  {Array}         any properties that might be used in the validation
	 *
	 * @return {Boolean}       whether it passed or not
	 */
	public function test($itemValue, $itemName = "", $props = array()) {
		
		$message = $props["string"];
		
		if ($itemValue == "") {
			$defaultMessage = "The value ".$itemName." should not be blank.";
			$message = (!empty($message)) ? str_replace("{{ itemName }}", $itemName, $message) : $defaultMessage;
			Error::setError($message);
			return false;
		}
		
		return true;
		
	}
	
}
