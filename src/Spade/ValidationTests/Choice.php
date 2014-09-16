<?php

/*!
 * Choice Test
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\ValidationTests;

use \Spade\Error;
use \Spade\ValidationTest;

class Choice extends ValidationTest {
	
	/**
	 * Check to see if this option is found in a list of options
	 *
	 * yaml use:
	 *   ModelName:
	 *     itemName: 
	 *        - Choice:      [comma-delimited choices]
	 * or
	 *   ModelName:
	 *      itemName: 
	 *         - Choice:
	 *             choices:  [comma-delimited choices]
	 *             callback: [model,method]
	 *             multiple: [boolean]
	 *             min:      [integer]
	 *             max:      [integer]
	 *             message:  [error message]
	 *
	 * @param  {String}        the item to be checked
	 * @param  {String}        the name to be included in messaging
	 * @param  {Array}         any properties that might be used in the validation
	 *
	 * @return {Boolean}       whether it passed or not
	 */
	public function test($itemValue, $itemName = "", $props = array()) {
		
		// figure out the choices to be checked against
		if (isset($props["string"])) {
			$choices = explode(",",$props["string"]);
		} else if (isset($props["choices"])) {
			$choices = explode(",",$props["choices"]);
		} else if (isset($props["callback"])) {
			$callbackOptions = explode(",",$props["callback"]);
			if (!isset($callbackOptions[1])) {
				Error::setError("your callback needs two separate options");
				Error::render();
			}
			$callbackModel   = $callbackOptions[0];
			$callbackOption  = $callbackOptions[1];
			$choices         = $callbackModel::$callbackOption();
		} else {
			Error::setError("you must supply a list of choices for the choice test.");
			Error::render();
		}
		
		// evaluate if this is a single option or multiple options to check
		if ((is_string($itemValue) || is_int($itemValue)) && !in_array($itemValue,$choices)) {
			
			$defaultMessage = "The value, ".$itemName.", you selected is not a valid choice.";
			$message = (isset($props["message"])) ? str_replace("{{ itemName }}", $itemName, $props["message"]) : $defaultMessage;
			Error::setError($message);
			return false;
			
		} else if (is_array($itemValue) && isset($props["multiple"]) && ($props["multiple"] == false)) {
			
			if (count($itemValue) > 1) {
				Error::setError("you can only provide one option for the choice test.");
				Error::render();
			}
			
			if (!in_array($itemValue[0],$choices)) {
				$defaultMessage = "The value, ".$itemName.", you selected is not a valid choice.";
				$message = (isset($props["message"])) ? str_replace("{{ itemName }}", $itemName, $props["message"]) : $defaultMessage;
				Error::setError($message);
				return false;
			}
			
		} else if (is_array($itemValue) && isset($props["multiple"]) && ($props["multiple"] == true)) {
			
			$min = (isset($props["min"])) ? $props["min"] : 1;
			$max = (isset($props["max"])) ? $props["max"] : 25;
			$cnt = 0;
			
			foreach ($itemValue as $item) {
				if (in_array($item,$choices)) {
					$cnt++;
				}
			}
			
			if (($cnt < $min) || ($cnt > $max)) {
				$defaultMessage = "One or more of the given values is invalid.";
				$message = (isset($props["message"])) ? str_replace("{{ itemName }}", $itemName, $props["message"]) : $defaultMessage;
				Error::setError($message);
				return false;
			}
			
		}
		
		return true;
		
	}
	
}
