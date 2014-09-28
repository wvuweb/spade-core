<?php

/*!
 * Auth Class
 *
 * Copyright (c) 2014 Dave Olsen
 *
 * Provide the auth framework
 *
 */

namespace Spade;

use \Spade\App;
use \Spade\Config;
use \SpadeKit\Models\User;
use \Spade\Render;
use \Spade\Timer;

class Auth {
	
	// default messaging
	protected static $messages = array(
		"ldap" => "there is something wrong with LDAP",
		"user" => "there is something wrong with your username or password",
	);
	
	/**
	* Make the LDAP call to see if the supplied username and password match
	* @param  {String}       the username to be checked
	* @param  {String}       the password to be checked
	*/
	public static function authenticate($username, $password) {
		
		$app = App::get();
		
		// default vars
		$i       = 0;
		$user_dn = false;
		
		// connect to ldap
		try {
			$ds = ldap_connect("ldap://".Config::getOption("ldap.hostname"));
		} catch (\Exception $e) {
			$app->flash('error', self::$messages["ldap"]);
			$app->redirect('/login/');
		}
		
		if ($ds) {
			
			// bind the ldap query user against ldap
			try {
				$ldapbind = ldap_bind($ds, Config::getOption("ldap.user"), Config::getOption("ldap.password"));
			} catch (\Exception $e) {
				$app->flash('error', self::$messages["ldap"]);
				$app->redirect('/login/');
			}
			
			// gather the list of OUs
			$ou_array = self::gatherOUList();
			
			while (!$user_dn) {
				
				// try looking at Config::getOption("ldap.baseDN") in addition to the OUs
				$sr   = @ldap_search($ds,$ou_array[$i],"(sAMAccountName=".$username.")");
				$info = @ldap_get_entries($ds, $sr);
				
				if (isset($info[0])) {
					$user_dn    = $info[0]["dn"];
					$user_cname = $info[0]["cn"][0];
				}
				
				if ((count($ou_array) == $i++) && !$user_dn) {
					$app->flash('error', self::$messages["user"]);
					$app->redirect('/login/');
				}
				
			}
			
			// bind for the user
			$bind = @ldap_bind($ds, $user_dn, $password);
			
			if ($bind) {
				$users = User::findByusername($username);
				if ((int)$users->count() != 1) {
					$app->flash('error', self::$messages["user"]);
					$app->redirect('/login/');
				}
				foreach ($users as $user) {
					$userID = $user->id;
				} 
				return $userID;
			} else {
				$app->flash('error', self::$messages["user"]);
				$app->redirect('/login/');
			}
			
		} else {
			$app->flash('error', self::$messages["ldap"]);
			$app->redirect('/login/');
		}
		
	}
	
	/**
	* Build the OUs list
	*/
	protected static function gatherOUList() {
		
		$ousNew  = array();
		$ousOrig = Config::getOption("ldap.ous");
		foreach ($ousOrig as $ou) {
			$ousNew[] = "OU=".$ou.",DC=wvu-ad,DC=wvu,DC=edu";
		}
		
		return $ousNew;
		
	}
	
	/**
	* Validate the username and password
	* @param  {String}       the username to be checked
	* @param  {String}       the password to be checked
	* 
	* @param  {String}       the clean version of the username
	*/
	public static function validate($username, $password) {
		
		$app = App::get();
		
		$username = strtolower(trim($username));
		if (empty($username) || empty($username) || !preg_match("/[a-z0-9\-]{3,30}/",$username)) {
			$app->flash('error', self::$messages["user"]);
			$app->redirect('/login/');
		}
		
		// see if the user exists
		$i = 0;
		$users = User::findByusername($username);
		if ((int)$users->count() != 1) {
			$app->flash('error', self::$messages["user"]);
			$app->redirect('/login/');
		}
		
		return $username;
		
	}
	
}
