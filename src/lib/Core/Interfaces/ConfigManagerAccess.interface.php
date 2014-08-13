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

namespace Core\Lib\Interfaces;

/**
 *
 * @author blackfox
 */
interface ConfigManagerAccess
{
	const DEF_SECTION	= 'System';
	const GLUE		= '.';
	const DEF_CONF_F_FORMAT = "INI";

	public function getByKey ($key);
	
	public function setByKey ($key, $value);
	
	public function loadFromFile ($filename, $format);
}