<?php

/*!
 * Console Version Command Class
 *
 * Copyright (c) 2014 Dave Olsen
 *
 */

namespace Spade\Console\Commands;

use \Spade\Config;
use \Spade\Console;
use \Spade\Console\Command;
use \Spade\Timer;

class VersionCommand extends Command {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->command = "version";
		
		Console::setCommand($this->command,"Print the version number","The version command prints out the current version of Spade.","v");
		
	}
	
	public function run() {
		
		Console::writeInfo("you're running <desc>v".Config::getOption("v")."</desc> of the PHP version of Spade...");
		
	}
	
}
