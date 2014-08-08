<?php

namespace Core\Lib;

// Load exceptions
use \Core\Lib\InvalidSyntaxException;

/**
 * Static class used to store configuration values as pair of properties-values
 * using the INI file format.
 */
class SimpleINIConfigManager
{
	const SINICM_DEFAULT_SECTION	= 'System';
	const SINICM_SECTION_GLUE		= '.';

	static private $stack;
	static private $files;

	static public function getByKey ($key)
	{
		$section = self::getSectionByKey ($key);
		$property = self::getPropertyByKey ($key);
		
		if (!self::keyExists ($property, $section))
			throw new OutOfBoundsException (_('Invalid key.'));
		
		return self::$stack [$section] [$property];
	}
	
	static public function setByKey ($key, $value)
	{
		$section = self::getSectionByKey ($key);
		$property = self::getPropertyByKey ($key);
		
		if (!self::sectionExists ($section)) {
			throw new OutOfBoundsException (
				_('Section creation not supported.')
			);
		}

		self::$stack [$section] [$property] = $value;
	}
	
	static public function sectionExists ($section) //needed?
	{
		return isset (self::$stack [$section]);
	}

	static public function keyExists ($key, $section = NULL)
	{
		if (!$section) {
			$section = self::getSectionByKey ($key);
			$property = self::getPropertyByKey ($key);
		} else {
			$property = $key;
		}

		return isset (self::$stack [$section] [$property]);
	}
	
	static public function loadFromFile ($filename)
	{
		$sections = parse_ini_file ($filname, true);

		//Save file names and containing sections
		//NOTE: Global properties are not supported.
		foreach ($sections as $section => $properties) {
			if (!is_array ($properties)) {
				throw new InvalidSyntaxException (
					_('Global properties not supported')
				);
			}

			self::$files [$filename] [] = $section;
		}
		
		self::$stack += $sections;
	}
	
	static public function saveToFile ()
	{
		foreach (self::$files as $file) {
			//Prepare output string
			$out = NULL;
			
			//Each file could contain more than one section
			foreach ($file as $section) {
				//Section
				$out .= '[' . $section . ']' . PHP_EOL;

				//Values
				foreach (self::$stack [$section] as $property => $value) {
					if (is_string ($value)) //sure?
						$value = '"' . $value . '"';
					
					$out .= $property . '=' . $value . PHP_EOL;
				}
			}
			
			file_put_contents ($file, $out);
		}
	}
	
	static public function getSectionByKey ($key)
	{
		//It is a root key?
		if (strpos ($key, SINICM_SECTION_GLUE) == false)
			return self::SINICM_DEFAULT_SECTION;
		else
			return substr ($key, 0, strrpos ($key, SINICM_SECTION_GLUE));
	}
	
	static public function getPropertyByKey ($key)
	{
		return substr ($key, strrpos ($key, SINICM_SECTION_GLUE) + 1);
	}
}
