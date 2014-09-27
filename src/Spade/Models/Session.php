<?php

/*!
 * Session Model
 *
 * Copyright (c) 2014 Dave Olsen
 *
 * The database model for the session
 *
 */

namespace Spade\Models;

use \Pheasant\Types;
use \Spade\Error;
use \Spade\Model;
use \Spade\Render;
use \Spade\Validator;

class Session extends \Pheasant\DomainObject {
	
	/**
	 * The properties that define the database
	 */
	public function properties() {
		return array(
			'id'         => new Types\Integer(11, 'primary auto_increment'),
			'user_id'    => new Types\Integer(11, 'required'),
			'session_id' => new Types\String(45, 'required'),
			'remote_ip'  => new Types\String(45)
		);
	}
	
	/**
	 * REMINDER: CAN DO RELATIONSHIPS
	 *	public function relationships() {
	 *		return array(
	 *			'Type' => Type::hasOne('type_id')
	 *		);
	 *	}
	 */
	
	/**
	 * Before create let's make sure we validate the data and populate the session id
	 * @param  {Object}        the session object
	 */
	public function beforeCreate($event,$object) {
		
		// populate session info here. could also populate the remoted ip here
		$object->session_id = session_id();
		
		/**
		 * Validate objects and use their class name to find items in validations.yml
		 * Validator::validateObject($object,"Status");
		 * Validator::checkForErrors();
		 */
		
	}
	
	public function beforeUpdate($event,$object) {
		
		// can do stuff here
		
	}
	
}
