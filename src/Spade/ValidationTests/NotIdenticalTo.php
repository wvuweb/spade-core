<?php

/*!
 * NotIdenticalTo Test
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\ValidationTests;

use \Spade\Error;
use \Spade\ValidationTest;

class NotIdenticalTo extends ValidationTest {
	
	/**
	 * Check to see if this item is not identical to the given value
	 * uses === and not == (use notequalto for latter)
	 *
	 * yaml use:
	 *   ModelName:
	 *     itemName: 
	 *         - NotIdenticalTo:
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
			Error::setError("need a value to compare equal to");
			Error::render();
		}
		
		if (($props["value"] !== 0) && is_bool($props["value"])) {
			$itemValue = (bool)$itemValue;
		} else if (is_float($props["value"])) {
			$itemValue = (float)$itemValue;
		} else if (is_int($props["value"])) {
			$itemValue = (int)$itemValue;
		} else {
			$itemValue = (string)$itemValue;
		} 
		
		if ($itemValue === $props["value"]) {
			$defaultMessage = "This value ".$itemName." should not be identical to ".$props["value"].".";
			$message = (isset($props["message"]) && ($props["message"] != "~")) ? str_replace("{{ itemName }}", $itemName, str_replace("{{ comparedValue }}", $props["value"], $props["message"])) : $defaultMessage;
			Error::setError($message);
			return false;
		}
		
		return true;
		
	}
	
}
