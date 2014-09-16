<?php

/*!
 * Type Test
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\ValidationTests;

use \Spade\Error;
use \Spade\ValidationTest;

class Type extends ValidationTest {
	
	/**
	 * Check to see if this is a valid type
	 * Supports the is_ and ctype_ functions
	 * http://php.net/manual/en/ref.var.php
	 * http://php.net/manual/en/ref.ctype.php
	 *
	 * yaml use:
	 *   ModelName:
	 *     itemName: 
	 *        - Type: [type]
	 * or
	 *   ModelName:
	 *      itemName: 
	 *         - Type:
	 *             type: [type]
	 *             message: [error message]
	 *
	 * @param  {String}        the item to be checked
	 * @param  {String}        the name to be included in messaging
	 * @param  {Array}         any properties that might be used in the validation
	 *
	 * @return {Boolean}       whether it passed or not
	 */
	public function test($itemValue, $itemName = "", $props = array()) {
		
		$type   = (isset($props["string"])) ? $props["string"] : $props["type"];
		
		// make sure it's lowercased
		$type   = strtolower($type);
		
		// allow for the test of ctypes
		$cTypes = array("alnum", "alpha", "cntrl", "digit", "graph", "lower", "print", "punct", "Spade", "upper", "xdigit");
		
		// figure out the function name
		if (in_array($type,$cTypes)) {
			$funcName = "ctype_".$type;
			setlocale(LC_ALL,"en_US");
		} else {
			$funcName = "is_".$type;
		}
		
		if (!function_exists($funcName)) {
			Error::setError("The given type, ".$type.", is not supported by the Type validation test.");
			Error::render();
		}
		
		if (!$funcName($itemValue)) {
			$defaultMessage = "The value ".$itemName." should be of type ".$type.".";
			$message = (isset($props["message"]) && ($props["message"] != "~")) ? str_replace("{{ itemName }}", $itemName, str_replace("{{ type }}", $type, $props["message"])) : $defaultMessage;
			Error::setError($message);
			return false;
		}
		
		return true;
		
	}
	
}
