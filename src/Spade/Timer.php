<?php

/*!
 * Timer Class
 *
 * Copyright (c) 2014 Dave Olsen
 *
 * Can use this to debug issues
 *
 */

namespace Spade;

use \Spade\Render;

class Timer {
	
	protected static $startTime;
	protected static $checkTime;
	
	/**
	* Check the current timer
	*/
	public static function check($text = "") {
		
		// make sure start time is set
		if (empty(self::$startTime)) {
			Render::error("the timer wasn't started...");
		}
		
		// make sure check time is set
		if (empty(self::$checkTime)) {
			self::$checkTime = self::$startTime;
		}
		
		// format any extra text
		$insert = "";
		if (!empty($text)) {
			$insert = "<info>".$text." >> </info>";
		}
		
		// get the current time
		$checkTime = self::getTime();
		
		// get the data for the output
		$totalTime = ($checkTime - self::$startTime);
		$mem       = round((memory_get_peak_usage(true)/1024)/1024,2);
		
		// figure out what tag to show
		$timeTag = "info";
		if (($checkTime - self::$checkTime) > 0.2) {
			$timeTag = "error";
		} else if (($checkTime - self::$checkTime) > 0.1) {
			$timeTag = "warning";
		}
		
		// set the checkTime for the next check comparison
		self::$checkTime = $checkTime;
		
		// write out time/mem stats
		Render::line($insert."currently taken <".$timeTag.">".$totalTime."</".$timeTag."> seconds and used <info>".$mem."MB</info> of memory...");
		
	}
	
	/*
	* Get the time stamp
	*/
	protected static function getTime() {
		$mtime = microtime();
		$mtime = explode(" ",$mtime); 
		$mtime = $mtime[1] + $mtime[0]; 
		return $mtime;
	}
	
	/**
	* Start the timer
	*/
	public static function start() {
		
		// get the current time
		self::$startTime = self::getTime();
		
	}
	
	/**
	* Stop the timer
	*/
	public static function stop() {
		
		// make sure start time is set
		if (empty(self::$startTime)) {
			Render::error("the timer wasn't started...");
		}
		
		// get the current time
		$endTime = self::getTime();
		
		// get the data for the output
		$totalTime = ($endTime - self::$startTime);
		$mem = round((memory_get_peak_usage(true)/1024)/1024,2);
		
		// figure out what tag to show
		$timeTag = "info";
		if ($totalTime > 0.5) {
			$timeTag = "error";
		} else if ($totalTime > 0.3) {
			$timeTag = "warning";
		}
		
		// write out time/mem stats
		Render::line("site generation took <".$timeTag.">".$totalTime."</".$timeTag."> seconds and used <info>".$mem."MB</info> of memory...");
		
	}
	
}