<?php
/* -*- Mode: PHP; indent-tabs-mode: t; tab-width: 4 -*- */
/*
	lib/classes/DataBaseInterface/DataBase.interface.php
	Copyright (C) 2010 BlackFox

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
 * Basics interface implementations
 *
 * TODO:longdesc
 * @author BlackFox
 * @version 0.1
 * @copyright BlackFox, 07 December, 2010
 * @package DataBase
 */

require_once ('config.php');

/**
 * DataBaseInterface helps you to consult the database and insert/edit fields
 *
 * @subpackage Interfaces
 */

interface DataBaseInterface
{
	/**
	 * insertEntry saves a new row in the especified table.
	 * 
	 * @param string $table name of the table
	 * @param string $values a string with comma-separated pair of values (ie, col=val[, ...])
	 * @param string $opts DBMS query options
	 * @access public
	 * @return void
	 */

	public function insertEntry ($table, $values, $opts = "");

	/**
	 * multipleInsert saves multiples entries in the DB
	 * 
	 * @param mixed $table Name of the table
	 * @param mixed $values An array containing a list of values
	 * @param array $cols optional An array containing the name of columns
	 * @param string $opts DBMS query options
	 * @access public
	 * @return void
	 */

	public function multipleInsert ($table, $values, $cols = array (), $opts = "");
	public function getEntry ($table, $cols, $opts);
	public function editEntry ($table, $pair, $opts);
	public function deletEntry ($table, $opts);
}

/**
 * Manage your database with admin privileges
 *
 * @subpackage Interfaces
 */

interface DataBaseAdminInterface
{
	public function createDataBase ($dbname, $specifications, $force);
	public function createTable ($tablename, $definitions, $options, $sstatement);
	public function renameTable ($tablename, $newname);
	public function dropDatabase ($dbname);
	public function dropTable ($tablename);
	public function addUser ($username, $pass, $tables, $privileges);
	public function deleteUser ($username);
}
