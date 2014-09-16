<?php

/*!
 * GreaterThanOrEqual Test
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\ValidationTests;

use \Spade\Error;
use \Spade\ValidationTest;

class GreaterThanOrEqual extends ValidationTest {
	
	/**
	 * Check to see if this item is less than the given value
	 *
	 * yaml use:
	 *   ModelName:
	 *     itemName: 
	 *         - GreaterThanOrEqual:
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
			Error::setError("need a value to compare");
			Error::render();
		}
		
		if ($props["value"] > $itemValue) {
			$defaultMessage = "The value ".$itemName."should be greater than or equal to ".$props["value"]".";
			$message = (isset($props["message"]) && ($props["message"] != "~")) ? str_replace("{{ itemName }}", $itemName, str_replace("{{ comparedValue }}", $props["value"], $props["message"])) : $defaultMessage;
			Error::setError($message);
			return false;
		}
		
		return true;
		
	}
	
}
