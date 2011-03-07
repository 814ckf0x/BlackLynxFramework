<?php
/**
 * config.php
 * Created By: blackfox
 * Created Date: May 28, 2010
 * TODO translate
 * Asigna las variables necesarias para funciones del nucleo y para la
 * configuración básica del sitio.
 */

/* Installed libraries */

define ("GETTEXTLIB", true);

/* System Configuration. */
$config ['dbuser'];
$config ['dbpass'];
$config ['dbname'];
$config ['dbhost'];
$config ['dbprefix'];

$config ['rootpath'];
$config ['defaultlogpath']

$config ['themespath'] = $config['rootpath'] . "themes/";

$config ['privatepath'] = $config['rootpath'] . "private/";

/* Shortcut names. */
$dbprefix = &$config['dbprefix'];

/* Constants */

define ("THEMESPATH", $config['themespath']);

/* Error */
define ("DATABASE_ERROR",	0);
define ("FILE_SYS_ERROR",	1);

require ('core_functions.lib.php');
require ('errors.lib.php');

set_error_handler ("show_error");