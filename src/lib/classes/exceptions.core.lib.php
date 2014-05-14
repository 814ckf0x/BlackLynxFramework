<?php

namespace Core\Library;

/**
 * Invalid syntax exception.
 * 
 * Wether a file that you are reading has syntax error throw this class as
 * exception class. 
 */
class InvalidSyntaxException extends Exception
{
	/**
	 * The file with bad syntax.
	 */
	protected $ise_file;
	
	/**
	 * The line where the bad syntax was detected.
	 */
	protected $ise_line;
	
	/**
	 * Initializes an instance.
	 * 
	 * @param string $file The file with bad syntax.
	 * @param int $line The line where the bad syntax was detected.
	 * @param string $message Used for Exception::$message.
	 * @param int $code Used for Exception::$code.
	 * @param Exception $previous used for Exception::$previous.
	 */
	public function __construct ($file,
								 $line,
								 $message = "",
								 $code = 0,
								 Exception $previous = NULL
								)
	{
		// Initialize the parent class
		$message = "Invalid syntax detected: " . $message;
		parent::__construct ($message, $code, $previous);

		$this->ise_file = $file;
		$this->ise_line = $line;
	}
	
	/**
	 * Get the file in wich the syntax is wrong.
	 */
	public function getISEFile ()
	{
		return $this->ise_file;
	}
	
	/**
	 * Get the lin in wich the syntax is wrong.
	 */
	public function getISELine ()
	{
		return $this->ise_line;
	}
}
