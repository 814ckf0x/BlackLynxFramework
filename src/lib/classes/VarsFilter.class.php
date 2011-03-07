<?php
/**
 * VarsFilter.php
 * Created By: blackfox
 * Created Date: Jun 1, 2010
 * Last Modified: Jun 2, 2010
 * TODO translate
 * Filtro de variables
 */

/*
 * TODO translate
 * Constantes necesarias para definir cómo serán filtradas las variables.
 */

define (FILTER_HTML, 0);
define (FILTER_MYSQL, 1);

class VarsFilter {
	private $vars;
	
	/**
	 * TODO translate
	 * Guarda una copia de la matriz para filtrar
	 * 
	 * @param array $container
	 * A array containing the strings to filter for.
	 */
	public function __construct ($container)
	{
		if (!is_array($container))
			throw new Exception (_("Container MUST be an array"));

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
		
		if (!$ret && $throw)
			throw new Exception (_("Unknown variable"));

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
	 * if $mode is HTML_FILTERED this var says if change the "nl" char to "<br />" string
	 */
	public function getByKey ($varname, $mode = FILTER_HTML, $nl2br = true)
	{
		$this->issetVar ($varname, true);

		switch ($mode) {
			case FILTER_HTML:
				$temp = htmlentities ($this->vars[$varname], ENT_QUOTES, 'ISO8859-15');
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
	public function setVal ($varname, $value)
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

		switch ($type) {
			case 'int':
			case 'integer':
				$filter = FILTER_VALIDATE_INT;
				break;
			case 'bool':
			case 'boolean':
				$filter = FILTER_VALIDATE_BOOLEAN;
				break;
			case 'float':
			case 'real':
			case 'double':
				$filter = FILTER_VALIDATE_FLOAT;
				break;
			case 'regex':
				$filter = FILTER_VALIDATE_REGEXP;
				break;
			case 'url':
				$filter = FILTER_VALIDATE_URL;
				break;
			case 'email':
				$filter = FILTER_VALIDATE_EMAIL;
				break;
			case 'ip':
				$filter = FILTER_VALIDATE_IP;
				break;
			default:
				throw new Exception (_("Unknown tilter method"));
		}
		
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
				throw new Exception (_('Unknown sanitize method'));
		}
		
		return filter_var ($this->vars[$varname], $filter, $options);
	}
}
