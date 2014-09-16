<?php

/*!
 * Console Config Command Class
 *
 * Copyright (c) 2014 Dave Olsen, http://dmolsen.com
 * Licensed under the MIT license
 *
 */

namespace Spade\Console\Commands;

use \Spade\Config;
use \Spade\Console;
use \Spade\Console\Command;
use \Spade\Timer;

class ConfigCommand extends Command {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->command = "config";
		
		Console::setCommand($this->command,"Project Configuration","The --config command allows for the review of existing config options for this project.","c");
		Console::setCommandOption($this->command,"get:","Get the value for a specific config option.","To get a single configuration option:","","configOption");
		Console::setCommandOption($this->command,"list","List the current config options.","To list all of the current configuration:");
		
	}
	
	public function run() {
		
		if (Console::findCommandOption("list")) {
			
			// get all of the options
			$options = Config::getOptions();
			
			// sort 'em alphabetically
			ksort($options);
			
			// find length of longest option
			$lengthLong = 0;
			foreach ($options as $optionName => $optionValue) {
				$lengthLong = (strlen($optionName) > $lengthLong) ? strlen($optionName) : $lengthLong;
			}
			
			// iterate over each option and spit it out
			foreach ($options as $optionName => $optionValue) {
				$optionValue = (is_array($optionValue)) ? implode(", ",$optionValue) : $optionValue;
				$optionValue = (!$optionValue) ? "false" : $optionValue;
				$spacer      = Console::getSpacer($lengthLong,strlen($optionName));
				Console::writeLine("<info>".$optionName.":</info>".$spacer.$optionValue);
			}
			
		} else if (Console::findCommandOption("get")) {
			
			// figure out which optino was passed
			$searchOption = Console::findCommandOptionValue("get");
			$optionValue  = Config::getOption($searchOption);
			
			// write it out
			if (!$optionValue) {
				Console::writeError("the --get value you provided, <info>".$searchOption."</info>, does not exists in the config...");
			} else {
				$optionValue = (is_array($optionValue)) ? implode(", ",$optionValue) : $optionValue;
				$optionValue = (!$optionValue) ? "false" : $optionValue;
				Console::writeInfo($searchOption.": <ok>".$optionValue."</ok>");
			}
			
		} else {
			
			// no acceptable options were passed so write out the help
			Console::writeHelpCommand($this->command);
			
		}
		
	}
	
}
