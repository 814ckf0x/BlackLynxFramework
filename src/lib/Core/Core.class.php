<?php

namespace \Core;

use Core\Lib\SimpleConfigManager\SimpleConfigManager as ConfigManager;

final class Core
{
	static private $configStore; //Can't be modified by other class

	static public function init (array $_argv, array $_env)
	{
		//Prepare configurations;
		$this->configStore = new  ();
		
		//$_env
		foreach ($_env as $confvar =>  $confval) {
			// look for system paths
			$path_suffix = '_path';
			if (strpos($confvar, $path_suffix)) {
				$path_name = strstr ($confvar, $path_suffix, true);
				
				$this->configStore->setByKey(
						,$confval);
			}
		}
	}
}