<?php
/* -*- Mode: PHP; indent-tabs-mode: t; tab-width: 4 -*- */
/*
	lib/classes/DataBaseInterface/MySQLDataBase.class.php
	Copyright (C) 2010 BlackFox <814ckf0x@gmail.com>

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Connect to MySQL database and insert, update and delete rows
 *
 * Using MySQL Data Base class you can connect to your server and
 * make queries as a common user, the methods clean the queries and
 * implements a security layer filtering all queries.
 *
 * @author BlackFox <814ckf0x@gmail.com>
 * @version 0.1
 * @copyright BlackFox <814ckf0x@gmail.com>, 07 December, 2010
 * @package DataBase
 */

/**
 * Represent a connection between PHP an MySQL server
 *
 * @subpackage MySQLDataBase
 **/
class MySQLDataBase implements DataBaseInterface
{
	private $dbhandler;
	public function __construct ($dbhost, $dbuser, $dbname, $pass)
	{
		$this->dbhandler = new mysqli ($dbhost, $dbuser, $dbname, $pass);

		if ($this->dbhandler->connect_error) {
			throw new ErrorException ($this->dbhandler->connect_error,
									$this->dbhandler->connect_errno);
		}
	}

	/**
	 * Insert a new row in the database
	 * 
	 * @param string $table The name of the table where de new row will be added
	 * @param array $values An array wich stores the values, the key MAY be the col_name.
	 * 	if all keys are not integers key will be taken as col_name.
	 * @param string $opts Compatibility with the interface
	 * @access public
	 * @return void
	 */
	public function insertEntry ($table, $values, $opts = "")
	{
		/* checking if $values array contains string keys */
		$stringkeys = (array_sum (array_keys ($values)) == count ($values))? false : true;

		if ($stringkeys) { // TODO: finish it using VarsFilter
			foreach ($values as $key => $val) {
				$columns = $key
			}
		}
	}

	public function multipleInsert ($table, $values, $cols = array (), $opts = "")
	{
		//code ...
	}

	public function getEntry ($table, $cols, $opts)
	{
		// code...
	}

	public function editEntry ($table, $values, $opts)
	{
		// code...
	}

	public function deleteEntry ($table, $opts)
	{
		// code...
	}
} // END class 
