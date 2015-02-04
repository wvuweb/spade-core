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

use \Spade\Error;
use \Spade\Render;
use \Spade\Validator;
use \Illuminate\Database\Eloquent\Model;

class Session extends \Illuminate\Database\Eloquent\Model {

	/**
	* The database table name to map to, defaults to the name of the class
	*/
	protected $table = 'sessions';

	/**
	* Before create let's make sure we validate the data and populate the session id
	* @param  {Object}        the event
	* @param  {Object}        the session object
	*/
	public function beforeCreate($event,$object) {
		// populate session info here. could also populate the remoted ip here
		$object->session_id = session_id();
	}

	/**
	* Before update let's set some information
	* @param  {Object}        the event
	* @param  {Object}        the session object
	*/
	public function beforeUpdate($event,$object) {}

	/**
	* Notes
	*
	* Eloquent will also assume that each table has a primary key column named id.
	* You may define a primaryKey property to override this convention. Likewise,
	* you may define a connection property to override the name of the database
	* connection that should be used when utilizing the model.
	*
	* Relationships
	* http://laravel.com/docs/4.2/eloquent#relationships
	*/

}
