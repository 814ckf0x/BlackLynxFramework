<?php

\Core\Selene;

/**
 * Basic CURD Database interface
 */
interface CURDDataBase
{
	/**
	 * Saves a new row in the especified table.
	 * 
	 * @param string $table The name of the table
	 * @param string $values A CSV string in key=value format for each column
	 */

	public function insertEntry ($table, $values);
	
	/**
	 * Reads a entry from the especified table.
	 * 
	 * @param string $table The name of the table.
	 * @param string $cols A CSV list of cols to query.
	 * @param string $where CSV string in key=value format used as condition.
	 * 
	 * @return An array containing the database result.
	 */
	public function getEntry ($table, $cols, $where);
	
	/**
	 * Updates all entries where conditions are met.
	 * 
	 * @param string $table The name of the table.
	 * @param string $pair CSV string in key=value wich indicates de cols to update to.
	 * @param string $where CSV string in key=value format used as condition.
	 */
	public function updateEntry ($table, $pair, $where);
	
	/**
	 * Delete entries where conditions are met
	 * 
	 * @param string $table The name of the table.
	 * @param string $where CSV string in key=value format used as condition
	 */
	public function deletEntry ($table, $where);
}
