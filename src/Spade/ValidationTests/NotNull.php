<?php

/*!
 * NotNull Test
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\ValidationTests;

use \Spade\Error;
use \Spade\ValidationTest;

class NotNull extends ValidationTest {
	
	/**
	 * Check to see if the item is not null
	 *
	 * yaml use:
	 *   ModelName:
	 *     itemName: 
	 *        - NotNull: [error message]
	 *
	 * @param  {String}        the item to be checked
	 * @param  {String}        the name to be included in messaging
	 * @param  {Array}         any properties that might be used in the validation
	 *
	 * @return {Boolean}       whether it passed or not
	 */
	public function test($itemValue, $itemName = "", $props = array()) {
		
		$message = $props["string"];
		
		if (is_null($itemValue)) {
			$defaultMessage = "The value ".$itemName." not be null.";
			$message = (!empty($message)) ? str_replace("{{ itemName }}", $itemName, $message) : $defaultMessage;
			Error::set($message);
			return false;
		}
		
		return true;
		
	}
	
}
