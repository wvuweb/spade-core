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
	* @param  {Array}        various options that can be used for the email
	*
	* @param  {Object}       the status of the sent email
	*/
	public static function send($emailName = "", $options = array()) {
		
		// if email isn't enabled just silently fail as if it were a success
		if (!Config::getOption("enableEmail")) {
			return true;
		}
		
		// make sure we have an email to pull options for
		if (empty($emailName)) {
			Render::warning("need to pass an emailName");
			return false;
		}
		
		// populate default vars
		$optionBase   = self::getOptionBase($emailName);
		$data         = isset($options["data"])         ? $options["data"] : array();
		$to           = isset($options["to"])           ? $options["to"] : Config::getOption($optionBase."to");
		$cc           = isset($options["cc"])           ? $options["cc"] : Config::getOption($optionBase."cc");
		$from         = isset($options["from"])         ? $options["from"] : Config::getOption($optionBase."from");
		$subject      = isset($options["subject"])      ? $options["subject"] : Config::getOption($optionBase."subject");
		$templatePath = isset($options["templatePath"]) ? $options["templatePath"] : Config::getOption($optionBase."template");
		
		// render the email body
		$body         = Render::template($templatePath,$data);
		
		// make sure certain vars are arrays for swiftmailer
		$to           = (!is_array($to))   ? array($to)   : $to;
		$from         = (!is_array($from)) ? array($from) : $from;
		
		// swiftmailer connection info
		$transport    = \Swift_SmtpTransport::newInstance('smtp.wvu.edu', 25);
		$mailer       = \Swift_Mailer::newInstance($transport);
		
		// set-up and send the message
		try {
			$message      = \Swift_Message::newInstance();
			$message->setTo($to);
			$message->setFrom($from);
			$message->setSubject($subject);
			$message->setBody($body);
			if ($cc) {
				$cc = (!is_array($cc)) ? array($cc) : $cc;
				$message->setCc($cc);
			}
			$result = $mailer->send($message);
		} catch (\Exception $e) {
			return false;
		}
		
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
