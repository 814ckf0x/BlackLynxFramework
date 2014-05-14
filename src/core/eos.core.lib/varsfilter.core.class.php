<?php

namespace \Core\Eos;

class VarsFilter {
	const FILTER_HTML	= 0;
	const FILTER_MYSQL	= 1;
	
	/**
	 * PCRE extension errors sufix.
	 */
	const PREG_MESSAGE_SUFIX = 'PCRE: ';

	private $vars;
	
	/**
	 * Checks if there is an error while using PCRE extension.
	 * 
	 * @throws RuntimeException if there is an error while using PCRE extension.
	 */
	static public function checkPregError ()
	{
		$error = preg_last_error ();
		
		if ($error !== PREG_NO_ERROR) {
			switch ($error) {
				case PREG_INTERNAL_ERROR:
					$error = _('There is an internal error.');
				break;
				case PREG_BACKTRACK_LIMIT_ERROR:
					$error = _('Backtrack limit was exhausted.');
				break;
				case PREG_RECURSION_LIMIT_ERROR:
					$error = _('Recursion limit was exhausted.');
				break;
				case PREG_BAD_UTF8_ERROR:
					$error = _('Bad UTF8 error.');
				break;
				case PREG_BAD_UTF8_OFFSET_ERROR:
					$error = _('Bad UTF8 offset error.');
				break;
				default:
					$error = _('Un documented error.');
				break;
			}

			throw new RuntimeException (self::PREG_MESSAGE_SUFIX . $error);
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
	static public function pregfilter ($pattern,
									   $replacement,
									   $subject,
									   $limit = -1,
									   &$count = NULL
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
	static public function pregMatchAll ($pattern,
										 $subject,
										 array &$matches = NULL,
										 $flags = PREG_PATTERN_ORDER,
										 $offset = 0
										)
	{
		$match_counter = preg_match_all ($pattern, $subjet, $matches, $flags, $offset);
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
	static public function pregMatch ($pattern,
									  $subject,
									  array &$matches = NULL,
									  $flags = 0,
									  $offset = 0
									 )
	{
		$match = preg_match ($pattern, $subject, $matches, $flags, $offset);
		self::checkPregError ();
		
		//NOTE: At this point $match will 'never' be false because of self::checkPregError ().
		return ($match)? true : false;
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
	static public function pregReplaceCallback ($pattern,
												callable $callback,
												$subject,
												$limit = -1,
												&$count = NULL
											   )
	{
		$replaced = preg_replace_callback ($pattern, $callback, $subject, $limit, $count);
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
	static public function pregReplace ($pattern,
										$replacement,
										$subject,
										$limit = -1,
										&$count = NULL
									   )
	{
		$replaced = preg_replace ($pattern,
								  $replacement,
								  $subject,
								  $limit,
								  $count
								 );
		self::checkPregError ();
		return $replaced;
	}

	/**
	 * A simplified variables validator.
	 * 
	 * @param string $filter The filter to use.
	 * @param string $subject The invalidated string.
	 * @param mixed $options Validate filter options.
	 * 
	 * @return bool True if $subject is valid, false otherwise.
	 */
	static public function validateVar ($filter, $subject, $options = NULL)
	{
		// Is $filter a PCRE pattern?
		if (strpos ($filter, '/') === 0) {
			$type = 'regex';
			$options = array ('options' => array ('regexp' => $filter));
		} else {
			$type = $filter;
		}

		
		$filtered = filter_var ($subject,
								self::testFilterMethod ($type),
								$options
							   );
		return ($filtered !== false);
	}

	/**
	 * Returns a validate filter.
	 * 
	 * @param string $type A variable type or regex.
	 * 
	 * @return unknown A validate filter.
	 * 
	 * @throws InvalidArgumentException if $type is not a valid type
	 */
	static public function testFilterMethod ($type)
	{
		
		switch ($type) {
			case 'int':
			case 'integer':
				return FILTER_VALIDATE_INT;
			break;
			case 'bool':
			case 'boolean':
				return FILTER_VALIDATE_BOOLEAN;
			break;
			case 'float':
			case 'real':
			case 'double':
				return FILTER_VALIDATE_FLOAT;
			break;
			case 'regex':
				return FILTER_VALIDATE_REGEXP;
			break;
			case 'url':
				return FILTER_VALIDATE_URL;
			break;
			case 'email':
				return FILTER_VALIDATE_EMAIL;
			break;
			case 'ip':
				return FILTER_VALIDATE_IP;
			break;
			default:
				$message = _('Unknown filter method');
				throw new InvalidArgumentException ($message);
		}
	}

//TODO: update this shit.

	/**
	 * TODO translate
	 * Guarda una copia de la matriz para filtrar
	 * 
	 * @param array $container
	 * A array containing the strings to filter for.
	 */
	public function __construct ($container)
	{
		if (!is_array($container)) {
			$message = _('Container MUST be an array');
			throw new InvalidArgumentException ($message);
		}

		$this->vars = $container;
	}

	/**
	 * Returns a unfiltered viariable contined in $vars wich key is $varname
	 * 
	 * @param string $varname
	 * The key of the variable.
	 */
	public function unfiltered ($varname)
	{
		if(!isset($this->vars[$varname])){
			return false;
		}
		return $this->vars[$varname];
	}

	/**
	 * Check if $varname key exists in $vars.
	 * 
	 * @param string $varname
	 * The key of the variable.
	 * 
	 * @param bool $trow
	 * Says the funtion to throw an exception if the $varname variable does not exists.
	 */
	public function issetVar($varname, $throw = false)
	{
		$ret = isset($this->vars[$varname]);
		
		if (!$ret && $throw) {
			$message = _('Unknown variable');
			throw new OutOfBoundsException ($message);
		}

		return $ret;
	}
	
	/**
	 * Searchs and return the filtered value of $varname or null if not exists.
	 * 
	 * @param string $varname
	 * The key of the variable.
	 * 
	 * @param int $mode
	 * TODO translate
	 * Define el modo para filtrar las variables.
	 * 
	 * @param bool $nl2br
	 * if $mode is HTML_FILTERED this var says if change the 'nl' char to '<br />' string
	 */
	public function getByKey ($varname, $mode = FILTER_HTML, $nl2br = true)
	{
		$this->issetVar ($varname, true);

		switch ($mode) {
			case FILTER_HTML:

				if (is_array ($this->vars [$varname])) {
					$temp = array_map ('htmlentities',
										$this->vars [$varname],
										array (ENT_QUOTES),
										array (\Core\Core::getSystemConfigurationVar ('Page_Encoding'))
							);
				} else {
					$temp = htmlentities ($this->vars [$varname],
											ENT_QUOTES,
											\Core\Core::getSystemConfigurationVar ('Page_Encoding')
										);
				}

				return $nl2br? nl2br ($temp) : $temp;
			default:
				return mysqli_real_escape_string ($this->vars[$varname]);
		}
	}

	public function __get ($varname)
	{
		$this->getByKey ($varname);
	}

	/**
	 * Adds or change the value of $varname in $vars.
	 * 
	 * @param string $varname
	 * The key of the variable.
	 * 
	 * @param string $value
	 * The value.
	 */
	public function setVal ($varname, $value = '')
	{
		$this->vars[$varname] = $value;
	}
	
	/**
	 * Check if $varname in $vars correspond to the format especified by $type
	 * 
	 * @param string $type
	 * The validate method
	 * 
	 * @param string $varname
	 * The key of the variable.
	 * 
	 * @param mixed $options default NULL
	 * Like options parameter of filter functions, please check the php documentation (ref.filter.php)
	 */
	public function validate ($type, $varname, $options = NULL)
	{
		$this->issetVar ($varname, true);

		
		return filter_var ($this->vars[$varname], $filter, $options);
	}
	
	/**
	 * Tries to sanitize the variable in $vars wich key is $varname.
	 * 
	 * @param string $type
	 * The sanitize method.
	 * 
	 * @param string $varname
	 * The variable key;
	 * 
	 * @param mixed $options
	 * See VarsFilter::validate ()
	 */
	public function sanitize ($type, $varname, $options = NULL)
	{
		$this->issetVar ($varname, true);

		switch ($type) {
			case 'email':
				$filter = FILTER_SANITIZE_EMAIL;
				break;
			case 'encoded':
				$filter = FILTER_SANITIZE_ENCODED;
				break;
			case 'magic_quotes':
				$filter = FILTER_SANITIZE_MAGIC_QUOTES;
				break;
			case 'float':
			case 'real':
			case 'double':
			case 'number_float':
				$filter = FILTER_SANITIZE_NUMBER_FLOAT;
				break;
			case 'int':
			case 'integer':
			case 'number_int':
				$filter = FILTER_SANITIZE_NUMBER_INT;
				break;
			case 'special_chars':
				$filter = FILTER_SANITIZE_SPECIAL_CHARS;
				break;
			case 'str':
			case 'string':
			case 'stripped':
				$filter = FILTER_SANITIZE_STRING;
				break;
			case 'url':
				$filter = FILTER_SANITIZE_URL;
				break;
			case 'unsafe_raw':
				$filter = FILTER_UNSAFE_RAW; // WTF?
				break;
			default:
				$message = _('Unknown sanitize method');
				throw new BadFunctionCallException ($message);
		}
		
		return filter_var ($this->vars[$varname], $filter, $options);
	}
}
