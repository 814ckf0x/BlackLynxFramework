<?php
/* -*- Mode: PHP; indent-tabs-mode: t; tab-width: 4 -*- */
/*
	lib/classes/ConfigManager/ConfigFile.class.php
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
 * SCFR_DEFAULTCONFIGURATIONROOT stores the default path to the configuration folder
 * default = installationrooth/config
 */
define ('SCFR_DEFAULT_CONFIGURATION_ROOT', $root . '/config');

/**
 * SCFR_CONF_EXTENSION is the extension of the configuration files.
 * defult = conf
 **/
define ('SCFR_CONF_EXTENSION', 'conf');

/**
 * SCFR_COMMENT_CHARS backslash-escaped characters that could initiate an inline-comment
 * SCFR_COMMENT_CHARS will be used in a pcre pattern string, check the syntax.
 * default = #
 **/
define ('SCFR_COMMENT_CHARS', '\#'); // I use to escape all non-alphanumeric chars

/**
 * SCFR_VALID_VARIABLE_NAME the key name of any configuration pair SHOULD be like php variable names
 * default = [a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*
 **/
define ('SCFR_VALID_VARIABLE_NAME', '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*');

/**
 * You can use this class for load and write configuration files
 *
 * @author BlackFox <814ckf0x@gmail.com>
 * @version 0.1
 * @copyright BlackFox <814ckf0x@gmail.com>, 12 November, 2010
 * @package ConfigManager
 * @subpackage Config
 */

class SimpleConfigFileReader
{
	private $filehandler, $cache;

	/**
	 * __construct 
	 * 
	 * @param mixed $configname An identifier of the configuration.
	 * @param string $configpath 
	 * @access public
	 * @return void
	 */
	public function __construct ($configname, $configpath = SCFR_DEFAULT_CONFIGURATION_ROOT)
	{
		// Opening the config file
		$filename = $configname . SCFR_CONF_EXTENSION;
		$filename = $configpath . '/' . $filename;
		$this->loadFile ($filename);

		//TODO: check de file syntax an save it in a stream for a secured use.
	}

	/**
	 * Makes a SplFileObject to interact with the config file
	 *
	 * @param The configuration file name
	 * @return int
	 */
	private function loadFile ($file)
	{
		if (!file_exists ($file)) {
			throw new RuntimeException ("Config file does not exist: $file");
		}

		$this->filehandler = new SplFileObject ($file, 'r');
	}

	/**
	 * Gets a configuration value from the conf file and save it in $cache
	 *
	 * @param string the name of the config value
	 * @return mixed could be a string or an array TODO: array support
	 */
	public function getConfigVar ($name)
	{
		if (!preg_match (SCFR_VALID_VARIABLE_NAME, $name))
			throw new RuntimeException ("$name is not a valid variable name"); // TODO: use VarsFilter class

		if (isset $this->cache [$name]) {
			return $this->cache [$name]);
		} else {
			$matches = array ();
			while (!$this->filehandler->eof ()) {

				$line = $this->filehandler->fgets ();

				if (preg_match ("/^{$name}\\s*\\=\\s*([^" . SCFR_COMMENT_CHARS . "\\r\\n\\s])", // TODO: be most restrictive.
							$file,
							$matches
							) === false) {
					$lasterror = error_get_last ();

					throw new RuntimeException ($lasterror ['message']);

				} elseif (count ($matches) == 2) {

					$this->cache [$name] = $matches [1]; // clean "" or '' for strings.

					return $matches [1];
				}
			}
		}
	}

	/**
	 * Returns a configuartion value
	 *
	 * @param string $varname name of the configuration value
	 * @return mixed the returned value could be a string or an array TODO: array support.
	 */
	public function __get ($varname)
	{
		return $this->getConfigVar ($varname);
	}
}
