<?php

namespace Core\Selene;

// Prepare VarsFilterManager class.
use \Core\Eos\VarsFilterManager;

//Prepare Containable interface.
use \Core\Library\Containable;

/**
 * Represents a configuration option entry containing infomation about it.
 */
class ConfigObject implements JsonSerializable
{
	/**
	 * A regular expression witch match with a valid configuration value name.
	 */
	const CO_VALID_NAME	= '/^[a-zA-Z_][a-zA-Z0-9\_]*/';

	/**
	 * Describes the configuration object and its representation.
	 */
	protected $description;

	/**
	 * The name of the configuration object.
	 */
	protected $key;

	/**
	 * The value of the configuration object
	 */
	protected $value;

	/**
	 * A string representing the valid format of the configuration object.
	 * 
	 * Can be any type of data like 'string', 'integer', ... or a PCRE string.
	 */
	protected $format;

	/**
	 * The default value of the configuration object.
	 */
	protected $default;

	/**
	 * Creates an instance of ConfigObject
	 */
	public function __construct ($key,
								 $format = NULL,
								 $value = NULL,
			 					 $description = NULL,
								 $default = NULL
								)
	{
		if (!VarsFilterManager::validateVar (self::CO_VALID_NAME, $key)) {
			$message = _('Invalid option name');
			throw new InvalidArgumentException ();
		}

		$this->key = $key;
		
		$this->__set ('format', $format);
		
		if ($value !== NULL)
			$this->__set ('value', $value);
		
		if ($default !== NULL)
			$this->__set ('default', $default);
		
		$this->description = $description;
	}

	/**
	 * Returns properties of the configuration object.
	 * 
	 * @param string $name The name of the property.
	 * 
	 * @return mixed The porperty value.
	 */	
	public function __get ($name)
	{
		switch ($name) {
			case 'description':
				return $this->description;
			break;
			case 'value':
				return $this->value;
			break;
			case 'format':
				return $this->format;
			break;
			case 'default':
				return $this->default;
			break;
			default:
				return $this->key;
			break;
		}
	}

	/**
	 * Sets a specified property.
	 * 
	 * @param string $name The name of the property.
	 * @param mixed $value The new value of the property.
	 */
	public function __set ($name, $value)
	{
		switch ($name) {
			case 'description':
				$this->description = $value;
			break;
			case 'format':
				try {
					VarsFilterManager::testFilterMethod ($value);
				} catch (InvalidArgumentException $e) {
					$message = _('Invalid option format');
					throw new InvalidArgumentException ($message);
				}
				
				$this->format = $value;
			break;
			case 'default':
				if (!VarsFilterManager::validateVar ($this->format, $value)) {
					$message = _('Default has not a valid format');
					throw new InvalidArgumentException ($message);
				}
				$this->default = $value;
			break;
			default:
				if (!VarsFilterManager::validateVar ($this->format, $value)) {
					$message = _('Value has not a valid format');
					throw new InvalidArgumentException ($message);
				}
				$this->value = $value;
			break;
		}
	}

	/**
	 * Specify data which should be serialized to JSON
	 * 
	 * The output of json_encode would be somthing like:
	 * @code
	 * {
	 * 		'key' : [
	 * 			'value' : 'val',
	 * 			'default' : 'defval',
	 * 			'format' : 'form',
	 * 			'description' : 'desc'
	 * 		]
	 * }
	 * @endcode
	 */
	 public function jsonSerialize ()
	 {
	 	return array ($this->key => array ('value'=> $this->value,
										   'default' => $this->default,
										   'format' => $this->format,
										   'description' => $this->description
					  					  )
					 );
	 }
}

class ConfigGroup implements ArrayAccess, Containable, JsonSerializable
{
	protected $key;
	protected $stack;
	protected $parent = false;

	public function __construct ($key, array $stack, $parent = false)
	{
		foreach ($stack as $value) {
			$this->appendChild ($value);
		}

		$this->key = $key;

		if ($parent)
			$this->setParent ($parent);
	}

	public function offsetExists ($offset)
	{
		return isset ($this->stack [$offset]);
	}

	public function &offsetGet ($offset)
	{
		if ($this->offsetExists ($offset))
			return $this->stack [$offset];
		else {
			$message = _('Accesing to an invalid key');
			throw new InvalidArgumentException ($message);
		}
	}

	protected function offsetSet ($offset, $value)
	{
		$this->appendChild ($value, $offset);
	}

	public function offsetUnset ($offset)
	{
		unset ($this->stack [$offset]);
	}

	public function appendChild (&$child, $key = false)
	{
		$instanceof_self = ($child instanceof self);
		if (!($instanceof_self || $child instanceof ConfigObject)) {
			$message = _('Invalid child type.');
			throw new InvalidArgumentException ($message);
		}

		if ($key !== false && $child->key !== $key){
			$message = _('Invalid Key');
			throw new InvalidArgumentException ($message);
		}
		
		if ($instanceof_self)
			$child->setParent ($this);

		$this->stack [$child->key] =& $child;
	}

	public function &__get ($name)
	{
		if ($name == 'key') {
			$copied_key = $this->key; //Create a copy of the key.
			return $copied_key; //and return a reference to the copy.
		} else {
			return $this->offsetGet ($name);
		}
	}

	public function __set ($name, $value)
	{
		$this->appendChild($value, $name);
	}

	protected function setParent (&$parent)
	{
		if (!($parent instanceof self)) {
			$message = _('Parent must be a ') . __CLASS__;
			throw new InvalidArgumentException ($message);
		}
		$this->parent =& $parent;
	}

	public function &getParent ()
	{
		if ($this->hasParent ())
			return $this->parent;
		else
			return NULL;
	}

	public function hasParent ()
	{
		return $this->parent != false;
	}
	
	public function jsonSerialize ()
	{
		//Preparing childs
		$childs = array ();
		foreach ($this->stack as $value) {
			$childs [] = $value->jsonSerialize ();
		}

		return array ('GroupName' => $this->key,
					  $childs
					 );
	}
}

class configManager
{
	protected static $config;

	protected static $configFiles;

	public static function init ($configpath) //TODO: use sysconfdir
	{
		$this->config = new ConfigGroup ('main', self::loadFromFile ($configpath));
	}

	public static function load ($filename, $group = NULL)
	{
		self::$config->appendChild (self::loadFromFile ($filename));
	}

	protected static function loadFromFile ($filename)
	{
		return json_decode (file_get_contents ($filename), true);
	}

	public function &getInstance ($group = NULL)
	{
	}

	public static function flush ($group = NULL)
	{
	}
}
