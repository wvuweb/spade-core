<?php

/*!
 * GreaterThan Test
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\ValidationTests;

use \Spade\Error;
use \Spade\ValidationTest;

class GreaterThan extends ValidationTest {
	
	/**
	 * Check to see if this item is greater than the given value
	 *
	 * yaml use:
	 *   ModelName:
	 *     itemName: 
	 *         - GreaterThan:
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
			Error::set("need a value to compare");
			return false;
		}
		
		if ($props["value"] >= $itemValue) {
			$defaultMessage = "The value ".$itemName." should be greater than ".$props["value"]".";
			$message = (isset($props["message"]) && ($props["message"] != "~")) ? str_replace("{{ itemName }}", $itemName, str_replace("{{ comparedValue }}", $props["value"], $props["message"])) : $defaultMessage;
			Error::set($message);
			return false;
		}
		
		return true;
		
	}
	
}
