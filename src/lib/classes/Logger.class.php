<?php
/* -*- Mode: PHP; indent-tabs-mode: t; tab-width: 4 -*- */
/*
	lib/classes/Logger.class.php
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

/**#@+
 * These constants define the log type
 */
define ('LOGINFO',		'Information');
define ('LOGWARNING',	'Warning');
define ('LOGERROR',		'Error');
/**#@-*/

/**
 * This constan determine the máx logfile size in bytes 
 */

define ('MAXLOGSIZE', 45*1000);//FIXME: use config class

/**
 * Simply class for loggin process.
 *
 * Logger is an interface that make posible to generate a standar format
 * log easily, it can helps you to manage your software.
 *
 * @author BlackFox <814ckf0x@gmail.com>
 * @version 0.1-1
 * @copyright BlackFox <814ckf0x@gmail.com>, 12 November, 2010
 * @package Logger
 */

class Logger
{
	/**
	 * $logfile contains the absolute log path
	 *
	 * @var string
	 */
	private $logfile; //FIXME: use config class
	
	/**
	 * Loads the file and check if $file is a regular file
	 *
	 * @return void
	 */
	public function __construct ($file)
	{
		if (!file_exists ($file)) {
			throw new InvalidArgumentException ("File does not exist")
		}

		$this->logfile = new SplFileObject ($file, 'a'); // on error it throws a RuntimeException
		
		if (!$this->logfile->isFile ())
			throw new InvalidArgumentException ("File argument is not a regular file");

		$this->checkLength ();
	}
	
	/**
	 * Creates a log entry
	 *
	 * @acces public
	 * @param string $message Log message
	 * @param int $type Type of log entry
	 * @param string $proc Process that makes the log entry.
	 * @return void
	 */

	public function log ($message, $type = LOGINFO, $proc = "Unknown")
	{
		$line = date ('M d G:i:s') . "\t$type\t$prc\t$message\r\n";
		$this->logfile->write ($line);
	}

	/**#@+
	 * types of log
	 *
	 * @acces public
	 * @param string $message Log message
	 * @param string $message Process that makes the log entry.
	 * @return void
	 */
	
	public function warning ($message, $proc = "Unknown")
	{
		$this->Log ($message, LOGWARNING, $proc);
	}
	
	public function info ($message, $proc = "Unknown")
	{
		$this->Log ($message, LOGINFO, $proc);
	}
	
	public function error ($message, $proc = "Unknown")
	{
		$this->Log ($message, LOGERROR, $proc);
	}
	/**#@-*/
	
	/**
	 * Check if the log file excedds the MAXLOGSIZE size, in that case invokes
	 * compressLog method
	 */

	protected function checkLength ()
	{
		$logsize = $this->logfile->getSize();
		
		if ($logsize > MAXLOGSIZE) {
			$this->compressLog ();
		}
	}

	/**
	 * This function compresses the log in other file and clear its contents.
	 */
	
	protected function compressLog () {

		$logcontent = "";
		$logpath = dirname ($this->logfile);
		$temp = $this->logfile->getFileName ();

		// Save the extension of the file
		$extension

		// Get the name of the log file
		$logname = $this->logfile->getBasename ($this->logfile->getExtensionName ());

		// Determine log compressed file name
		$logfiles = scandir ($logpath);

		$versions = array ();

		for ($i=0; $i<count ($logfiles); $i++) {
			if (!is_file ($logfiles [$i])) {
				unset ($logfiles [$i]);
			} else {
				$temp = array ();

				if (preg_match ("/". $logname ."-(d*).txt.gz/", $logfiles [$i], $matches)) {

					$versions [] = $matches [1];
				}
			}
		}

		sort ($version, SORT_NUMERIC);

		$compressedlogname = $logname . "-" . ($version [$i]+1) . ".txt.gz" ;

		// Read all log content
		while (!$this->logfile->eof()) {

			$logcontent .= $this->logfile->fgets ();
		}

		// Create log compressed string
		$logcontent = gzencode ($logcontent);

		// Save compressed log
		file_put_contents ($logpath . $compressedlogname, $logcontent);

		// Clear log file
		$this->logfile->ftruncate (0);
	}
}
