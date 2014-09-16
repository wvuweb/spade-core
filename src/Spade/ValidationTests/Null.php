<?php

/*!
 * Null Test
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\ValidationTests;

use \Spade\Error;
use \Spade\ValidationTest;

class Null extends ValidationTest {
	
	/**
	 * Check to see if the item is null
	 *
	 * yaml use:
	 *   ModelName:
	 *     itemName: 
	 *        - Null: [error message]
	 *
	 * @param  {String}        the item to be checked
	 * @param  {String}        the name to be included in messaging
	 * @param  {Array}         any properties that might be used in the validation
	 *
	 * @return {Boolean}       whether it passed or not
	 */
	public function test($itemValue, $itemName = "", $props = array()) {
		
		$message = $props["string"];
		
		if (!is_null($itemValue)) {
			$defaultMessage = "The value ".$itemName." should be null.";
			$message = (!empty($message)) ? str_replace("{{ itemName }}", $itemName, $message) : $defaultMessage;
			Error::setError($message);
			return false;
		}
		
		return true;
		
	}
	
}
