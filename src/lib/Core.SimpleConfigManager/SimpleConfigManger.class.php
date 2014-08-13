<?php

namespace Core\Lib\SimpleConfigManager;

/**
 * Simple config values store.
 */
class SimpleConfigManager extends \Core\Lib\ConfigManger\ConfigManager
	implements \Core\Lib\Interfaces\ConfigManagerAccess
{
	private $stack;
//	private $files;

	protected function getConfigValue ($section, $property)
	{
		
		if (!$this->propertyExists ($property, $section)) {
			throw new SCMException (_('Invalid key.'));
		}

		return $this->stack [$section] [$property];
	}
	
	protected function setConfigValue ($section, $property, $value)
	{
		$this->stack [$section] [$property] = $value;
	}

	public function propertyExists ($property, $section)
	{
		if (!$section) {
			$section = $this->getSectionByKey ($key);
			$property = $this->getPropertyByKey ($key);
		} else {
			$property = $key;
		}

		return isset ($this->stack [$section] [$property]);
	}

	public function getByKey ($key)
	{
		$section = $this->getSectionByKey ($key);
		$property = $this->getPropertyByKey ($key);
		
		return $this->getConfigValue($section, $property);
	}
	
	public function setByKey ($key, $value)
	{
		$section = self::getSectionByKey ($key);
		$property = self::getPropertyByKey ($key);

		$this->setConfigValue($section, $property, $value);
	}
	
	public function loadFromFile ($filename, $format = self::DEFAULT_CONFIG_FILE_FORMAT)
	{
	//	switch ($format) {
	//		case 'INI':
		if ($format === 'INI') {
			$sections = parse_ini_file ($filename, true);

			//NOTE: Global properties are not supported.
			foreach ($sections as $section => $properties) {
				if (!is_array ($properties)) {
					throw new SCMException (
						_('Global properties not supported')
					);
				}

				//Save file names and containing sections
			//	$this->files [$filename] [] = $section;
			}

			$this->stack += $sections;
		}
	//	} //switch end
	}
}
