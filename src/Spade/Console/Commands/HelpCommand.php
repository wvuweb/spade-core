<?php

/*!
 * Console Help Command Class
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\Console\Commands;

use \Spade\Console;
use \Spade\Console\Command;
use \Spade\Timer;

class HelpCommand extends Command {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->command = "help:";
		
		Console::setCommand($this->command,"Print the help for a given command","The help command prints out the help for a given flag. Just use -h with another command and it will tell you all of the options.","h:");
		Console::setCommandSample($this->command,"To get help for a particular command:","<command-name>");
		
	}
	
	public function run() {
		
		if ($helpCommand = Console::findCommandValue("h|help")) {
			$helpCommand = str_replace("-","",$helpCommand);
			if ($commandFound = Console::findCommandLong($helpCommand)) {
				Console::writeHelpCommand($commandFound);
			} else {
				Console::writeHelp();
			}
		} else {
			Console::writeHelp();
		}
		
	}
	
}
