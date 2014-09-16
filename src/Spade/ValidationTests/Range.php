<?php

/*!
 * Range Test
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\ValidationTests;

use \Spade\Error;
use \Spade\ValidationTest;

class Range extends ValidationTest {
	
	/**
	 * Check to see if the item is within a range
	 *
	 * yaml use:
	 *   ModelName:
	 *     itemName: 
	 *        - Range:
	 *            min:        [integer - must be equal to or greater than this number]
	 *            max:        [integer - must be equal to or less than this number]
	 *            message:    [error message (for both)]
	 *
	 * @param  {String}        the item to be checked
	 * @param  {String}        the name to be included in messaging
	 * @param  {Array}         any properties that might be used in the validation
	 *
	 * @return {Boolean}       whether it passed or not
	 */
	public function test($itemValue, $itemName = "", $props = array()) {
		
		// make sure it's an appropriate type
		if (($itemValue = filter_var($itemValue,FILTER_VALIDATE_INT)) === false) {
			Error::setError("This value should be a valid number.");
			Error::render();
		}
		
		// make sure the range is properly set
		if (!isset($props["min"]) || !isset($props["max"])) {
			Error::setError("This test requires both min and max values.s");
			Error::render();
		}
		
		// set-up default props
		$error   = false;
		$length  = strlen($itemValue);
		
		// check the given value
		if (($props["min"] > $itemValue) || ($itemValue > $props["max"])) {
			$defaultMessage = "This value ".$itemName." is not within the valid range of ".$props["min"]."-".$props["max"].".";
			$message = (isset($props["message"]) && ($props["message"] != "~")) ? str_replace("{{ itemName }}", $itemName, $props["message"]) : $defaultMessage;
			Error::setError($message);
			return false;
		}
		
		return true;
		
	}
	
}
