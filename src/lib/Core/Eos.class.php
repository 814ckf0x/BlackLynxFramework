<?php
/* -*- Mode: PHP; indent-tabs-mode: t; tab-width: 4 -*- */
/**
 * this file contains the Eos class.
 *
 * @author Blackfox <blackfox@blackotakuzone.es>
 * @version 0.1
 * @copyright Blackfox <blackfox@blackotakuzone.es>, 05 January, 2011
 * @package core
 */

namespace Core
{
	define ('LIBPATH', $_SERVER['DOCUMENT_ROOT'] . '/lib/libraries');
	define ('MODPATH', $_SERVER['DOCUMENT_ROOT'] . '/modules');

	$GUI;

	class Eos
	{
		/**
		 * Loads the configuration variables
		 * 
		 * @static
		 * @access public
		 * @return void
		 */
		public static function init ()
		{
			$GUI = new \Library\GUI\GUI ($this->getConfigVal ('Web_Name'));
		}

		/**
		 * Initiate a Module instance, loads libraries needed for the module and execute de module
		 * 
		 * @static
		 * @access public
		 * @return void
		 */
		public static function initModule ()
		{
			$modname = $_GET ['module'];
			if (!isset ($modname) || $modname = '') $modname = $this->getConfigVal ('Default_Module');

			//TODO: check permissions

			\Core\CoreFunctions\check_module ($modname);
		}

		/**
		 * loadLibrary 
		 * 
		 * @param mixed $name 
		 * @static
		 * @access public
		 * @return void
		 */
		public static function loadLibrary ($name)
		{
			//TODO: check if library exists and if had been installed
			include (LIBPATH . '/' . $name);
		}

		/**
		 * loadPlugin 
		 * 
		 * @param mixed $name 
		 * @static
		 * @access public
		 * @return void
		 */
		public static function loadPlugin ($name)
		{
			// code...
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

		/**
		 * getSystemConfigurationVar 
		 * 
		 * @param mixed $var 
		 * @static
		 * @access public
		 * @return void
		 */
		public static function getSystemConfigurationVar ($var)
		{
			// code...
		}

		/**
		 * setSystemConfigurationVar 
		 * 
		 * @param mixed $var 
		 * @param mixed $value 
		 * @static
		 * @access public
		 * @return void
		 */
		public static function setSystemConfigurationVar ($var, $value)
		{
			// code...
		}

		/**
		 * SaveSystemConfigurationVar 
		 * 
		 * @param mixed $var 
		 * @param mixed $value 
		 * @static
		 * @access public
		 * @return void
		 */
		public static function SaveSystemConfigurationVar ($var, $value)
		{
			// code...
		}

		/**
		 * addSystemLogEntry 
		 * 
		 * @param mixed $entry 
		 * @static
		 * @access public
		 * @return void
		 */
		public static function addSystemLogEntry ($entry)
		{
			// code...	
		}

		/**
		 * addSytemDebugEntry 
		 * 
		 * @param mixed $entry 
		 * @static
		 * @access public
		 * @return void
		 */
		public static function addSytemDebugEntry ($entry)
		{
			// code...
		}
	}
}
