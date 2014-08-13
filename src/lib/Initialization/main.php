<?php

// Aliassing
//use const DIRECTORY_SEPARATOR as DS; // PHP 5.6+
if (!defined ('DS')) {
	define ('DS', DIRECTORY_SEPARATOR);
}

// Initialize Core variables
if (!is_array ($_env)) {
	$_env = array ();
}

if (!is_array ($_argv)) {
	$_argv = array ();
}

$installation_root = 'src';

$paths = array (
	'bin_path' => $installation_root . DS . 'bin',
	'etc_path' => $installation_root . DS . 'etc',
	'lib_path' => $installation_root . DS . 'lib',
	'modules_path' => $installation_root . DS . 'modules',
	'root_home_path' => $installation_root . DS . 'root',
	'public-www_path' => $installation_root . DS . 'srv'. DS . 'public-www',
	'root-www_path' => $installation_root . DS . 'srv'. DS . 'root-www',
	'tmp_path' => $installation_root . DS . 'tmp',
	'var_path' => $installation_root . DS . 'var',
);

$_env += $paths;

require_once $_env ['lib_path'] . DS . 'Core';

// Init core
\Core\Core::init ($_argv, $_env);