<?php

/*
 * Copyright (C) 2014 blackfox
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace Core\Lib\Abstraction;

/**
 * Description of ConfigManager
 *
 * @author blackfox
 */
abstract class ConfigManager
{
	abstract protected function getConfigValue ($section, $property);
	
	abstract protected function setConfigValue ($section, $property, $value);
	
	abstract public function sectionExists ($section);

	abstract public function keyExists ($key, $section);

	protected function getSectionByKey ($key, $glue)
	{
		if (strpos ($key, $glue) == false) {
			return false;
		} else {
			return substr ($key, 0, strrpos ($key, $glue));
		}
	}
	
	protected function getPropertyByKey ($key)
	{
		return substr ($key, strrpos ($key, self::SCM_SECTION_GLUE) + 1);
	}

	public function __get ($key) // $object->{'key'} syntax
	{
		return $this->getByKey ($key);
	}
	
	public function __set ($key, $val)
	{
		$this->setByKey ($key, $val);
	}
}
