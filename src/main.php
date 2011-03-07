<?php
/* main.php
 * Created By: blackfox
 * Created Date: May 28, 2010
 * TODO translate
 * Ejecuta las tareas de inicio de la web.
 */

require_once ('config.php');

$db = new DataBase ($config ['dbhost'], $config ['dbuser'], $config ['dbpass'], $config ['dbname']);