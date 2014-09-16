<?php

/*!
 * Email Service Class
 *
 * Copyright (c) 2014 Dave Olsen
 *
 * Send emails
 *
 */

namespace Spade\Services;

use \Spade\Config;
use \Spade\Render;
use \Spade\Timer;

class Email {
	
	/**
	* Send the email
	* @param  {String}       the name of the email so attributes can be pulled from the config
	* @param  {String}       the subject for the email
	* @param  {String}       the body for the email
	*
	* @param  {Object}       the status of the sent email
	*/
	public static function send($emailName,$subject,$body) {
		
		// populate default vars
		$optionBase = self::getOptionBase($emailName);
		$to         = Config::getOption($optionBase."to");
		$cc         = Config::getOption($optionBase."cc");
		$from       = Config::getOption($optionBase."from");
		
		// make sure certain vars are arrays for swiftmailer
		$to         = (!is_array($to))   ? array($to)   : $to;
		$from       = (!is_array($from)) ? array($from) : $from;
		
		// swiftmailer connection info
		$transport  = \Swift_SmtpTransport::newInstance('smtp.wvu.edu', 25);
		$mailer     = \Swift_Mailer::newInstance($transport);
		
		// set-up the message
		$message    = \Swift_Message::newInstance();
		$message->setTo($to);
		$message->setFrom($from);
		$message->setSubject($subject);
		$message->setBody($body);
		if ($cc) {
			$cc = (!is_array($cc)) ? array($cc) : $cc;
			$message->setCc($cc);
		}
		
		// send it
		$result = $mailer->send($message);
		
		return $result;
		
	}
	
	/**
	* Get the option base string so we can properly set-up config option gets
	* @param  {String}       the name of the email so attributes can be pulled from the config
	*
	* @return {String}       the option base
	*/
	public static function getOptionBase($emailName) {
		
		$emailConfigBase = "emails";
		$emailType       = Config::getOption("enableEmailDebug") ? "debug" : "default";
		return $emailConfigBase.".".$emailName.".".$emailType.".";
		
	}
	
}
