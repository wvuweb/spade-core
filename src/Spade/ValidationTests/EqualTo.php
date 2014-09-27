<?php

/*!
 * EqualTo Test
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\ValidationTests;

use \Spade\Error;
use \Spade\ValidationTest;

class EqualTo extends ValidationTest {
	
	/**
	 * Check to see if this item is equal to the given value
	 * uses != and not !== (use identical for latter)
	 *
	 * yaml use:
	 *   ModelName:
	 *     itemName: 
	 *         - EqualTo:
	 *             value:   [value]
	 *             message: [error message]
	 *
	 * @param  {String}        the item to be checked
	 * @param  {String}        the name to be included in messaging
	 * @param  {Array}         any properties that might be used in the validation
	 *
	 * @return {Boolean}       whether it passed or not
	 */
	public function test($itemValue, $itemName = "", $props = array()) {
		
		if (!isset($props["value"])) {
			Error::set("need a value to compare equal to");
			return false;
		}
		
		if ($itemValue != $props["value"]) {
			$defaultMessage = "This value ".$itemName." should be equal to ".$props["value"].".";
			$message = (isset($props["message"]) && ($props["message"] != "~")) ? str_replace("{{ itemName }}", $itemName, str_replace("{{ comparedValue }}", $props["value"], $props["message"])) : $defaultMessage;
			Error::set($message);
			return false;
		}
		
		return true;
		
	}
	
}
