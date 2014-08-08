<?php
/**
 * index.php
 * Created By: blackfox
 * Created Date: May 28, 2010
 * TODO translate
 * Carga y configura los módulos y muestra una salida al usuario.
 */

require('../config.php');
require ('../main.php');

$mod = $get->mod;

$query = "SELECT * FROM {$dbprefix}_modules WHERE code=$mod";

try {
	$result = $db->query ($query);
	
	if ($result->num_rows != 1) throw new DatabaseError ();
} catch (DataBaseError $e) {
	
}