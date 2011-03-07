<?php
/* -*- mode: php; indent-tabs-mode: t; tab-width: 4 -*- */
/*
	~/documents/phpprogramming/webpageengine/src/lib/classes/folderorganizer.class.php
	copyright (c) 2011 blackfox < 814ckf0x at gmail dot com >

	this program is free software; you can redistribute it and/or modify
	it under the terms of the gnu general public license as published by
	the free software foundation; either version 2 of the license, or
	(at your option) any later version.

	this program is distributed in the hope that it will be useful,
	but without any warranty; without even the implied warranty of
	merchantability or fitness for a particular purpose.  see the
	gnu general public license for more details.

	you should have received a copy of the gnu general public license
	along with this program; if not, write to the free software
	foundation, inc., 51 franklin street, fifth floor, boston, ma  02110-1301  usa
*/

/**
 * FolderOrganizer class helps you to manage your folder and files un the filesystem.
 *
 * You can simulate a categories system based in folders too.
 *
 * @author blackfox < 814ckf0x at gmail dot com >
 * @version 0.1
 * @copyright blackfox < 814ckf0x at gmail dot com >, 10 february, 2011
 * @package default
 */

define ('FO_RELATIVE_PATH', 0);
define ('FO_ABSOLUTE_PATH', 1);


/**
 * FolderOrganizer class definition
 **/
class FolderNavigator
{
	private $root, $navi;

	public function __construct($root)
	{
		if (!is_dir ($root))
			throw new InvalidArgumentException ("The argumet given is not a valid folder");

		$this->root = $root;
		$this->navi = dir ($this->root);
	}

	public function changeRoot ($newroot)
	{
		if (!is_dir ($root))
			throw new InvalidArgumentException ("The new root is not a valid folder");

		$this->root = $newroot

		$this->navi = dir ($this->root);
	}

	public function makeChild ($name)
	{
		if (chmod ($this->navi->path, 0777)) {
			if (!mkdir 
		}
	}

	/**
	 * changeDirectory, gets into a $name directory
	 * 
	 * @param string $name the name of the directory
	 * @access public
	 * @return void
	 */
	
	public function changeDirectory ($name)
	{
		$tmp = $this->navi->path . "/$name";

		if (is_dir ($tmp)) {
			$this->navi = dir ($tmp);
		} else {
			throw new InvalidArgumentException ("The name given is not a valid directory.");
		}
	}

	public function goToRoot ()
	{
		$this->navi = dir ($this->root);
	}

	protected function clearPath ($path, $type) //TODO: finish it
	{
		$pos = strpos ($path, $this->root);

		if ($type == FO_RELATIVE_PATH) {

			if ($pos !== false) {
				if ($pos != 0) {
					return false; // if the root path is containded but not leads the string someting is wrong.
				}

				// NOTE: length = offset + 1;
				$path = substr ($path, strlen ($this->root)); // remove the root path and the first "/"
			} else {

				if (strpos ($path, '/') === 0)
					$path = substr ($path, 1);
			}

			$lastslash = strrpos ($path, '/');

			if ($lastslash + 1 == strlen ($path)
				substr ($path, 0, -1); // delete the last "/"

			return $path;
		} elseif ($type = FO_ABSOLUTE_PATH) {

			if ($pos !== false) {
				if ($pos != 0) {
					return false; // the root path is not the first string
				}

				return $path;
			} else {
				$fslash =
			}
		}
	}

	protected function validateDir ($path, $type)
	{
		if ($type == FO_RELATIVE_PATH) {
			return is_dir ($this->root . '/' . $path);
		} elseif ($type = FO_ABSOLUTE_PATH) {
			return is_dir ($path)
		} else {
			throw new RunTimeException ("Invalid argument \$type, directory can't be validated");
		}
	}
}
