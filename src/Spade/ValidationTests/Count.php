<?php

/*!
 * Count Test
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\ValidationTests;

use \Spade\Error;
use \Spade\ValidationTest;

class Count extends ValidationTest {
	
	/**
	 * Check to see if this option has the correct number of options
	 *
	 * yaml use:
	 *   ModelName:
	 *      itemName: 
	 *         - Count:
	 *             min:     [integer]
	 *             max:     [integer]
	 *             exact:   [integer]
	 *             message: [error message]
	 *
	 * @param  {String}        the item to be checked
	 * @param  {String}        the name to be included in messaging
	 * @param  {Array}         any properties that might be used in the validation
	 *
	 * @return {Boolean}       whether it passed or not
	 */
	public function test($itemValue, $itemName = "", $props = array()) {
		
		if (!is_array($itemValue) && !is_object($itemValue)) {
			Error::setError("the value passed to the count test must be an object or array.");
			Error::render();
		}
		
		$cnt = 0;
		foreach ($itemvalue as $item) {
			$cnt++;
		}
		
		if (isset($props["min"]) && isset($props["max"])) {
			if (($cnt < $props["min"]) || ($cnt > $props["max"])) {
				$defaultMessage = "The value, ".$itemName.", you selected should have between ".$props["min"]." and ".$props["max"]." parts selected.";
				$message = (isset($props["message"]) && ($props["message"] != "~")) ? str_replace("{{ itemName }}", $itemName, $props["message"]) : $defaultMessage;
				Error::setError($message);
				return false;
			}
		} else if (isset($props["min"])) {
			if ($cnt < $props["min"]) {
				$defaultMessage = "The collection, ".$itemName.", should contain ".$props["min"]." elements or more.";
				$message = (isset($props["message"]) && ($props["message"] != "~")) ? str_replace("{{ itemName }}", $itemName, $props["message"]) : $defaultMessage;
				Error::setError($message);
				return false;
			}
		} else if (isset($props["max"])) {
			if ($cnt > $props["max"]) {
				$defaultMessage = "The collection, ".$itemName.", should contain ".$props["max"]." elements or less.";
				$message = (isset($props["message"]) && ($props["message"] != "~")) ? str_replace("{{ itemName }}", $itemName, $props["message"]) : $defaultMessage;
				Error::setError($message);
				return false;
			}
		} else if (isset($props["exact"])) {
			if ($cnt !== $props["exact"]) {
				$defaultMessage = "The collection, ".$itemName.", should contain exactly ".$props["exact"]." elements.";
				$message = (isset($props["message"]) && ($props["message"] != "~")) ? str_replace("{{ itemName }}", $itemName, $props["message"]) : $defaultMessage;
				Error::setError($message);
				return false;
			}
		}
		
		// evaluate if this is a single option or multiple options to check
		if ((is_string($itemValue) || is_int($itemValue)) && !in_array($itemValue,$choices)) {
			$defaultMessage = "The value, ".$itemName.", you selected is not a valid choice.";
			$message = (isset($props["message"]) && ($props["message"] != "~")) ? str_replace("{{ itemName }}", $itemName, $props["message"]) : $defaultMessage;
			Error::setError($message);
			return false;
		} else if (is_array($itemValue) && isset($props["multiple"]) && ($props["multiple"] == false)) {
			
			if (count($itemValue) > 1) {
				Error::setError("you can only provide one option for the choice test.");
				Error::render();
			}
			
			if (!in_array($itemValue[0],$choices)) {
				$defaultMessage = "The value, ".$itemName.", you selected is not a valid choice.";
				$message = (isset($props["message"]) && ($props["message"] != "~")) ? str_replace("{{ itemName }}", $itemName, $props["message"]) : $defaultMessage;
				Error::setError($message);
				return false;
			}
			
		} else if (is_array($itemValue) && isset($props["multiple"]) && ($props["multiple"] == true))
			
			$min = (isset($props["min"])) ? $props["min"] : 1;
			$max = (isset($props["max"])) ? $props["max"] : 25;
			$cnt = 0;
			
			foreach ($itemValue as $item) {
				if (in_array($item,$choices)) {
					$cnt++;
				}
			}
			
			if (($cnt >= $min) && ($cnt <= $max)) {
				$defaultMessage = "One or more of the given values is invalid.";
				$message = (isset($props["message"]) && ($props["message"] != "~")) ? str_replace("{{ itemName }}", $itemName, $props["message"]) : $defaultMessage;
				Error::setError($message);
				return false;
			}
			
		} else {
			Error::setError("unsupported type for the choice test.");
			Error::render();
		}
		
		return true;
		
	}
	
}
