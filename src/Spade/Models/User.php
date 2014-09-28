<?php

/*!
 * User Model
 *
 * Copyright (c) 2014 Dave Olsen
 *
 * The database model for the user
 *
 */

namespace Spade\Models;

use \Pheasant\Types;
use \Spade\Error;
use \Spade\Model;
use \Spade\Render;
use \Spade\Validator;

class User extends \Pheasant\DomainObject {
	
	public function properties() {
		return array(
			'id'       => new Types\Integer(11, 'primary auto_increment'),
			'username' => new Types\String(45, 'required'),
			'active'   => new Types\String(45, 'required'),
			'added_by' => new Types\String(45, 'required'),
			'added_on' => new Types\String(45, 'required'),
			'admin'    => new Types\Integer(11, 'required')
		);
	}
	
}