<?php

namespace \Core\Library;

/**
 * Extends ArrayAcces to references support.
 */
interface NonCopyArrayAccess extends ArrayAccess
{
	/**
	 * Get a reference to the object in the offset
	 * 
	 * @param integer $offset The offset retrieve.
	 * 
	 * @return mixed A reference of the offset value. 
	 */
	public function &offsetGet ($offset);

	/**
	 * Set a new value and return a reference to it.
	 * 
	 * @param mixed $key The value key to set for.
	 * @param mixed $value The new value to store.
	 * 
	 * @return mixed A reference to the new value.
	 */
	public function &offsetSet ($key, &$value);
}

/**
 * Extends Iterator to references support.
 */
interface NonCopyIterator extends Iterator
{
	/**
	 * Get a referent to the current element value.
	 */
	public function &current ();
}

/**
 * Classes implementing NonCopyRecursiveIterator works with real childs,
 * not copied.
 */
interface NonCopyRecursiveIterator extends NonCopyIterator, RecursiveIterator
{
	/**
	 * Get a reference of children.
	 */
	public function &getChildren ();
}

/**
 * Extends the NonCopyIterator Interface to add seek support.
 */
interface NonCopySeekableIterator extends NonCopyIterator
{
	/**
	 * Seeks to a position.
	 * 
	 * @param integer $position The position to seek to.
	 */
	public function seek ($position);
}

/**
 * Extends the SPL RecrusiveIterator Interface to work with parents
 */
interface ParentableRecursiveIterator extends NonCopyRecursiveIterator
{
	/**
	 * Return a pointer to parent.
	 */
	public function &getParent ();
	
	/**
	 * Return a pointer to the root parent.
	 */
	public function &getRootParent ();
	
	/**
	 * checks if the current instance has parent.
	 */
	public function hastParent ();
}
