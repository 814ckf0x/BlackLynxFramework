<?php

namespace Core\Lib\VarsFilter;

class VarsFilterManager
{

	/**
	 * PCRE extension errors sufix.
	 */
	const PREG_MESSAGE_SUFIX = 'PCRE: ';

	private $vars = array ();
	static public $lastMatches = NULL;

	/**
	 * Checks if there is an error while using PCRE extension.
	 * 
	 * @throws RuntimeException if error found.
	 */
	static public function checkPregError ()
	{
		$error = preg_last_error ();

		if ($error !== PREG_NO_ERROR) {
			switch ($error) {
				case PREG_INTERNAL_ERROR:
					$error = _ ('There is an internal error.');
					break;
				case PREG_BACKTRACK_LIMIT_ERROR:
					$error = _ ('Backtrack limit was exhausted.');
					break;
				case PREG_RECURSION_LIMIT_ERROR:
					$error = _ ('Recursion limit was exhausted.');
					break;
				case PREG_BAD_UTF8_ERROR:
					$error = _ ('Bad UTF8 error.');
					break;
				case PREG_BAD_UTF8_OFFSET_ERROR:
					$error = _ ('Bad UTF8 offset error.');
					break;
				default:
					$error = _ ('Un documented error.');
					break;
			}

			throw new \RuntimeException (self::PREG_MESSAGE_SUFIX . $error);
		}
	}

	/**
	 * Perform a regular expression search and replace, see preg_filter ().
	 * 
	 * @param mixed $pattern
	 * @param mixed $replacement
	 * @param mixed $subject
	 * @param integer $limit
	 * @param integer &$count
	 * 
	 * @return mixed $filtrated
	 */
	static public function pregfilter ($pattern, $replacement, $subject,
									$limit = -1, &$count = NULL
	)
	{
		$filtrated = preg_filter ($pattern, $replacement, $subject, $limit, $count);
		self::checkPregError ();

		return $filtrated;
	}

	/**
	 * Return array entries that match the pattern. see preg_grep ().
	 * 
	 * @param string $pattern
	 * @param array $input
	 * @param integer $flags
	 * 
	 * @return array $matches
	 */
	static public function pregGrep ($pattern, array $input, $flags = 0)
	{
		$matches = preg_grep ($pattern, $input, $flags);
		self::checkPregError ();

		return $matches;
	}

	/**
	 * Perform a global regular expression match. see preg_match_all ().
	 * 
	 * @param string $pattern
	 * @param string $subject
	 * @param array &$matches
	 * @param integer $flags
	 * @param integer $offset
	 * 
	 * @return integer $match_counter
	 */
	static public function pregMatchAll ($pattern, $subject, array &$matches = NULL,
										$flags = PREG_PATTERN_ORDER, $offset = 0
	)
	{
		$match_counter = preg_match_all ($pattern, $subject, $matches, $flags, $offset);
		self::checkPregError ();
		return $match_counter;
	}

	/**
	 * Perform a regular expression match. see preg_match ().
	 * 
	 * @param string $pattern
	 * @param string $subject
	 * @param array &$matches
	 * @param integer $flags
	 * @param integer $offset
	 * 
	 * @return bool $match
	 */
	static public function pregMatch ($pattern, $subject, array &$matches = NULL,
								 $flags = 0, $offset = 0
	)
	{
		$match = preg_match ($pattern, $subject, $matches, $flags, $offset);
		self::checkPregError ();

		//NOTE: At this point $match will 'never' be false because of self::checkPregError ().
		return ($match) ? true : false;
	}

	/**
	 * Escapes regular expression characters, see preg_quote ().
	 * 
	 * @param string $str
	 * @param string $delimiter
	 * 
	 * @return string Returns the quoted (escaped) string.
	 */
	static public function pregQuote ($str, $delimiter = NULL)
	{
		return preg_quote ($str, $delimiter);
	}

	/**
	 * Perform a regular expression search and replace using a callback.
	 * see preg_replace_callback ().
	 * 
	 * @param mixed $pattern
	 * @param callable $callback
	 * @param mixed $subject
	 * @param integer $limit
	 * @param integer &$count
	 * 
	 * @return mixed $replaced
	 */
	static public function pregReplaceCallback ($pattern, callable $callback,
											 $subject, $limit = -1, &$count = NULL
	)
	{
		$replaced = preg_replace_callback ($pattern, $callback, $subject, $limit,
									 $count);
		self::checkPregError ();
		return $replaced;
	}

	/**
	 * Perform a regular expression search and replace. see preg_replace ().
	 * 
	 * @param mixed $pattern
	 * @param mixed $replacement
	 * @param mixed $subject
	 * @param integer $limit
	 * @param integer &$count
	 * 
	 * @return mixed $replaced
	 */
	static public function pregReplace ($pattern, $replacement, $subject,
									 $limit = -1, &$count = NULL
	)
	{
		$replaced = preg_replace ($pattern, $replacement, $subject, $limit, $count
		);
		self::checkPregError ();
		return $replaced;
	}

	/**
	 * Creates a new instance
	 * 
	 * @param array $container Contains the array of variables to be filtered.
	 */
	public function __construct ($container, $trim = true)
	{
		if (!is_array ($container)) {
			$message = _ ('Argument given MUST be array type');
			throw new \InvalidArgumentException ($message);
		}

		foreach ($container as $name => $value) {
			if ($trim) {
				$value = trim ($value);
			}
			$this->vars [$name] = new VarsFilterVar ($value);
		}
	}

	/**
	 * Checks if $varname key exists.
	 * 
	 * @param string $varname The key of the variable.
	 * 
	 * @param bool $throw whether trow an error when not found.
	 */
	public function issetVar ($varname, $throw = false)
	{
		$ret = isset ($this->vars [$varname]);

		if (!$ret && $throw) {
			$message = _ ('Variable is not set.');
			throw new \OutOfBoundsException ($message);
		}

		return $ret;
	}

	public function __get ($varname)
	{
		$this->issetVar ($varname, true);
		return $this->vars [$varname];
	}

	/**
	 * Adds or change the value of $varname in $vars.
	 * 
	 * @param string $varname The key of the variable.
	 * 
	 * @param string $value The new value.
	 */
	public function __set ($varname, $value = '')
	{
		$this->vars[$varname] = new VarsFilterVar ($value);
	}

}