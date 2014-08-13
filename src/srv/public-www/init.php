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

// Aliassing
//use const DIRECTORY_SEPARATOR as DS; // PHP 5.6+
if (!defined ('DS')) {
	define ('DS', DIRECTORY_SEPARATOR);
}

// Initialize Core variables
$_env = array ();
$_argv = array ();

// Configure paths
$installation_root = 'src';

$main_init = $installation_root . DS . 'lib' . DS . 'Initialization' . DS . 'main.php';

require_once $main_init; // main_init loads the core