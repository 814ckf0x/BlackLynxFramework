<?php
/* -*- Mode: PHP; indent-tabs-mode: t; tab-width: 4 -*- */
/*
	core/core.class.php
	Copyright (C) 2011 Blackfox <blackfox@blackotakuzone.es>

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
 * this file contains the core class.
 *
 * @author Blackfox <blackfox@blackotakuzone.es>
 * @version 0.1
 * @copyright Blackfox <blackfox@blackotakuzone.es>, 05 January, 2011
 * @package core
 */

/**
 * This is the core of the web page application that control all libraries, logs and other resources
 **/
class Core
{
	/**
	 * Loads the configuration variables and saves some control data
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function Init ()
	{
	}

	/**
	 * Initiate a Module instance, loads the libraries needed for the module and execute de module
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function initModule ()
	{
	}

	public static function loadLibrary ()
	{
	}

	/**
	 * Initiate and return a resource like a file instance, temporary file instance, database, etc.
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function getResource ()
	{
		// code...
	}
}
