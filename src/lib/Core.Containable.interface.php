<?php

namespace Core\Library;

/**
 * The object has parents.
 */
interface Containable {
	public function setParent (&$parent);
	public function getParent ();
	public function hasParent ();
}