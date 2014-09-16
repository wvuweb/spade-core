<?php

/*!
 * Twitter Service Class
 *
 * Copyright (c) 2014 Dave Olsen
 *
 * Send tweets
 *
 */

namespace Spade\Services;

use \Spade\Config;
use \Spade\Render;
use \Spade\Timer;

class Twitter {
	
	/**
	* Tweet a message
	* @param  {String}       the twitter message
	* @param  {String}       the link if the message is too long
	* @param  {Boolean}      whether we should force the tweet to be unique
	*/
	public static function tweet($message,$moreInfo,$unique = false) {
		
		// see if the tweet is longer than 140 characters
		$lengthCheck = ($unique) ? 136 : 140;
		if (strlen($message) > $lengthCheck) {
			$subStrLen = ($lengthCheck - 1) - strlen("... ".$moreInfo);
			$message   = substr($message,0,$subStrLen)."... ".$moreInfo;
		}
		
		// figure out if the message should have a unique string
		$message = ($unique) ? $message.self::getRandString() : $message;
		
		// set-up the tweet settings
		$optionBase    = self::getOptionBase();
		$settings      = array(
		    "oauth_access_token"        => Config::getOption($optionBase."oauth_token"),
		    "oauth_access_token_secret" => Config::getOption($optionBase."oauth_token_secret"),
		    "consumer_key"              => Config::getOption($optionBase."consumer_key"),
		    "consumer_secret"           => Config::getOption($optionBase."consumer_secret")
		);
		$url           = "https://api.twitter.com/1.1/statuses/update.json";
		$requestMethod = "POST";
		$postFields    = array("status" => $message);
		
		// tweet it
		$twitter = new \TwitterAPIExchange($settings);
		$twitter->buildOauth($url, $requestMethod);
		$twitter->setPostfields($postFields);
		$twitter->performRequest(false);
		
	}
	
	/**
	* Get the option base string so we can properly set-up config option gets
	*
	* @return {String}       the option base
	*/
	public static function getOptionBase() {
		
		$twitterConfigBase = "twitter";
		$twitterType       = Config::getOption("enableTwitterDebug") ? "debug" : "default";
		return $twitterConfigBase.".".$twitterType.".";
		
	}
	
	/**
	* generate a random string to attach to a tweet. output is: ^a`	x
	*
	* @return {String}       the random string
	*/
	protected static function getRandString() {
		return " ^".substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"),0,2);
	}
	
}

