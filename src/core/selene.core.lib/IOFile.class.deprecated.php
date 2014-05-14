<?php

namespace \Core\Selene;

/**
 * Use SplFileObject class instead
 */

class IOFile {
	protected $fd;
	
	/**
	 * Creates a new IOFile Object.
	 * 
	 * @param string $filename Path to the file.
	 * @param string $mode fopen modes.
	 * @param bool $use_include_path If true file will be searched for in include_path.
	 * 
	 * @throws RuntimeException if fails openning the file.
	 */
	public function __construct ($filename, $mode = 'r+', $use_include_path = FALSE)
	{
		$this->fd = fopen ($filename, $mode, $use_include_path);

		if (!$this->fd) {
			$message = "Imposible to open the file";
			throw new RuntimeException ($message);
		}
	}
	
	/**
	 * Reads $lentgth chars from file.
	 * 
	 * @param integer @length bytes to read.
	 * 
	 * @throws RuntimeException If fails when reading.
	 */
	public function read ($length)
	{
		$buff = fread ($this->fd, $length);

		if (!$buff)
			throw new RuntimeException (
				"Error when reading the file"
			);
	}
	
	/**
	 * Writes in file.
	 * 
	 * @param string $string the string to write.
	 * @param integer $length optional.
	 * 
	 * @throws RuntimeExcpetion If fails when writing.
	 */
	public function write ($string, $length = NULL)
	{
		if (fwrite ($this->fd, $string, $length) === FALSE)
			throw new RuntimeException ("Errore when writing the file");
	}
	
	/**
	 * @breif Alias of $this->write ($string . PHP_EOL)
	 */
	public function setLine ($string)
	{
		$this->write ($string . PHP_EOL);
	}
	
	/**
	 * Reads a line from file.
	 * 
	 * @throws RuntimeException If fails to read.
	 */
	public function getLine ()
	{
		$line = fgets ($this->fd);

		if($line == false && feof ($this->fd) === FALSE)
			throw new RuntimeException ( //TODO: Lenguage Suppport
				"Error when reading the file"
			);

		return $line;
	}
	
	public function __get ($name)
	{
		switch ($name) {
			case 'eof':
				return feof ($this->fd);
			break;
			case 'pos':
				return ftell ($this->fd);
			break;
			case 'fd':
			case 'handle':
				return $this->fd;
			break;
			default:
				return NULL;
			break;
		}
	}
	
	public function __set ($name, $value)
	{
		switch ($name) {
			case 'pos':
				if (!is_int ($value))
					throw new InvalidArgumentException ( //TODO: Language Support
					 "Value given must be an integer"
					);
					
				fseek ($this->fd, $value);
			break;
			default:
				return NULL;
			break;
		}
	}
	
	public function __destruct ()
	{
		fclose ($fd);
	}
}
?>