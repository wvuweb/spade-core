<?php

/*!
 * Length Test
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\ValidationTests;

use \Spade\Error;
use \Spade\ValidationTest;

class Length extends ValidationTest {
	
	/**
	 * Check to see if the matches a certain length
	 *
	 * yaml use:
	 *   ModelName:
	 *     itemName: 
	 *        - Length:
	 *            min:     [integer - must be equal to or greater than this number]
	 *            max:     [integer - must be equal to or less than this number]
	 *            range:   [integer,integer - must be equal to or between these numbers]
	 *            exact:   [integer - must match exactly]
	 *            message: [error message]
	 *
	 * @param  {String}        the item to be checked
	 * @param  {String}        the name to be included in messaging
	 * @param  {Array}         any properties that might be used in the validation
	 *
	 * @return {Boolean}       whether it passed or not
	 */
	public function test($itemValue, $itemName = "", $props = array()) {
		
		// make sure it's an appropriate type
		if (is_array($itemValue) || (is_bool($itemValue))) {
			Error::set("invalid item type for the length test");
			return false;
		}
		
		// set-up default props
		$error   = false;
		$length  = strlen($itemValue);
		
		// check the given value
		if (isset($props["min"]) && ($props["min"] > $length)) {
			$error = true;
		} else if (isset($props["max"]) && ($length > $props["max"])) {
			$error = true;
		} else if (isset($props["exact"]) && ($length !== $props["exact"])) {
			$error = true;
		} else if (isset($props["range"])) {
			$bits = explode(",",$props["range"]);
			if (!isset($bits[1])) {
				Error::set("the range option for the length test requires a second attribute");
				Error::render();
			}
			$rangeMin = $bits[0];
			$rangeMax = $bits[1];
			if (($rangeMin > $length) || ($length > $rangeMax)) {
				$error = true;
			}
		} else {
			print_r($props);
			Render::error("the test type is not supported by the length test");
		}
		
		// write out the error if necessary
		if ($error) {
			$defaultMessage = "The value ".$itemName." is not the correct length.";
			$message = (isset($props["message"]) && ($props["message"] != "~")) ? str_replace("{{ itemName }}", $itemName, $props["message"]) : $defaultMessage;
			Error::set($message);
			return false;
		}
		
		return true;
		
	}
	
}
