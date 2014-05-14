<?php

namespace \Core\Selene;

/**
 * Simply class for loggin process.
 *
 * Logger is an interface that make posible to generate a standar format
 * log easily, it can helps you to manage your software.
 */

class Logger
{
	/**
	 * Log levels.
	 * 
	 * @{
	 */
	const LOG_EMERG		=	0;
	const LOG_ALERT		=	1;
	const LOG_CRIT		=	2;
	const LOG_ERR		=	3;
	const LOG_WARNING	=	4;
	const LOG_NOTICE	=	5;
	const LOG_INFO		=	6;
	const LOG_DEBUG		=	7;
	/** @} */

	/**
	 * Date format for logs
	 */
	const LOG_DATE_FORMAT = 'M d G:i:s';

	/**
	 * Log field spearator
	 */
	const LOG_FIELD_SEPARATOR = '\t';
	
	/**
	 * $logFile contains a SplFileObject
	 */
	private $logFile;
	
	/**
	 * Friendly names log levels.
	 */
	protected $friendly_log_levels = array ('Emergency',
											'Alert',
											'Critical',
											'Error',
											'Warning',
											'Notice',
											'Information',
											'Debug'
											);

	/**
	 * Loads the file and check if $file is a regular file
	 *
	 * @param string $file The log name
	 */
	public function __construct ($name)//TODO: Where to save logs?
	{
		$this->logFile = new SplFileObject ($name, 'a');
		$this->logMessage ('Log started.', self::LOG_DEBUG); //TODO: Add Proc
	}
	
	/**
	 * Creates a log entry.
	 *
	 * @param string $message Log message
	 * @param integer $type Type of log entry
	 * @param string $proc Process that makes the log entry.
	 */

	public function logMessage ($message, $type = self::LOG_INFO, $proc = 'System')
	{
		$entry = date (self::LOG_DATE_FORMAT)	. self::LOG_FIELD_SEPARATOR
				. $type						. self::LOG_FIELD_SEPARATOR
				. $prc						. self::LOG_FIELD_SEPARATOR
				. $message					. PHP_EOL;

		$this->logFile->fwrite ($entry);

//		$this->checkLength ();
	}
	
	/**
	 * logMessage aliases for diferents log levels.
	 * 
	 * You can use a method e.g use $this->Emergency ($message) as an alias of
	 * $this->logMessage ($message, LOG_EMERG).
	 */
	public function __call ($name, $arguments)
	{
		$tmp = array_search ($name, $this->friendly_log_levels);
		if ($tmp !== FALSE)
			$this->logMessage ($argument [0], $tmp);
		else {
			$message = _('Calling undefined method.');
			throw new BadFunctionCallException ($message);
		}
	}


	/**#@-*/
	
	/**
	 * Check if the log file excedds the MAXLOGSIZE size, in that case invokes
	 * compressLog method
	 *<</

	protected function checkLength ()
	{
		$logsize = $this->logFile->getSize();
		
		if ($logsize > MAXLOGSIZE) {
			$this->clearLog ();
		}
	}

	/**
	 * This function compresses the log in other file and clear its contents.
	 *<</
	
	protected function clearLog () {

		$logcontent = '';
		$logpath = dirname ($this->logFile);
		$temp = $this->logFile->getFileName ();

		// Save the extension of the file
		$extension = $this->logFile->getExtension ();

		// Get the name of the log file
		$basename = $this->logFile->getBasename ();
		$logname = $basename . '.' . time () . $extension;

		if (extension_loaded ('zlib')) {
			// Read all log content
			while (!$this->logFile->eof()) {

				$logcontent .= $this->logFile->fgets ();
			}

			// Create log compressed string
			$logcontent = gzencode ($logcontent);

			// Save compressed log
			file_put_contents ($logpath . '/' . $logname . '.gz', $logcontent);

			// Clear log file
			$this->logFile->ftruncate (0);
		} else {
			if (!rename ($logpath . '/' . $basename, $logname))
				throw new RunTimeException ('Failed changing the log file name');

			unset ($this->logFile);
			$this->logFile = new SplFileObject ($basename);
		}
	}
*/
}
