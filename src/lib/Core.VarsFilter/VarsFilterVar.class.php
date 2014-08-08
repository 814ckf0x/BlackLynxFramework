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

namespace Core\Lib\VarsFilter;

/**
 * Description of VarsFilterVar
 *
 * @author blackfox
 */

class VarsFilterVar
{

	private $data = NULL;
	private $options = array ();

	/**
	 * Prepares the new VarsFilterVar instance for filtering $data.
	 * 
	 * @param mixed $data You can store any type of data.
	 */
	public function __construct ($data)
	{
		$this->data = $data;
	}

	/**
	 * An internal htmlentities () alias with a few modification, See htmlentities ()
	 * documentation.
	 * 
	 * @param integer $flags default: ENT_QOUTES | ENT_HTML5
	 * @param type $encoding default: UTF-8
	 * @param type $double_encode default: true
	 * @return self
	 */
	public function HTML ($flags = NULL, $encoding = 'UTF-8', $double_encode = true
	)
	{
		if (is_null ($flags)) {
			$flags = ENT_QUOTES | ENT_HTML5;
		}

		return new self (htmlentities ($this->data, $flags, $encoding, $double_encode
				)
		);
	}

	/**
	 * Sets filter_var () options, Check 'Data Filtering' from the oficial PHP
	 * documentation to know how it works.
	 * 
	 * Option can be an asosiative array, with pair option => value, or a single
	 * option name wich will be merged with the current options array in the
	 * 'Data Filtering' way.
	 * 
	 * @param mixed $option
	 * @param mixed $value
	 */
	public function setOption ($option, $value = NULL)
	{
		if (is_array ($option)) {
			$this->options ['options'] = array_merge ($this->options ['options'], $option
			);
		} else {
			$this->options ['options'] [$option] = $value;
		}

		return $this;
	}

	/**
	 * This function append or switch on a filter configuration flag.
	 * 
	 * @param integer $flag
	 */
	public function setFlag ($flag)
	{
		$this->options ['flags'] |= $flag;
	}

	/**
	 * This function differs from self::setFlag () function by ignoring all
	 * previously set flags.
	 * 
	 * @param int $flags
	 */
	public function setFlags ($flags)
	{
		$this->options ['flags'] = $flags;
	}

	/**
	 * Here is where the magic occurs. When retriving information from the
	 * object you can switch between many filters type getting the filtered var
	 * or a boolean value depending on which filter you choose. i.e:
	 * 
	 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~{.php}
	 * <?php
	 * using \Core\Eos\VarsFilterVar;
	 * 
	 * $filtered_email = new VarsFilterVar ("some_user@server.tld");
	 * $filtered_bool = new VarsFilterVar ("no"); // a string meaning bool FALSE
	 * 
	 * var_dump ($filtered_email->isEmail) // returns TRUE
	 * var_dump ($filtered_bool->isBool) // returns TRUE
	 * 
	 * var_dump ($filtered_email->asEmail) // returns some_user@server.tld
	 * var_dump ($filtered_bool->asBool) //returns TRUE
	 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	 * 
	 * You choose the filter type retriving an object property. options are:
	 * 
	 * - Sanitize options:
	 * 	+ HTML (Aliasses: html, htmlEntities)
	 * 	+ asEmail (Aliasses: asEMail)
	 * 	+ URLEncoded (Aliasses: urlEncoded)
	 * 	+ magicQuotes
	 * 	+ asFloat (Aliasses: asReal, asDouble)
	 * 	+ asInt (Aliasses: asInteger)
	 * 	+ specialChars (Aliasses: HTMLSpecialChars, htmlSpecialChars)
	 * 	+ fullSpecialChars (Aliasses: HTMLFullSpecialChars, htmlFullSpecialChars)
	 * 	+ asString (Aliasses: stripped)
	 * 	+ asURL (Aliasses: asUrl)
	 * 	+ asRaw (Aliasses: asOrigin, asUnsafe, data)
	 * 	+ asBool (Aliasses: asBoolean)
	 * - Validate options:
	 * 	+ isBool (Aliasses: isBoolean)
	 * 	+ isEmail (Aliasses: isEMail)
	 * 	+ isFloat (Aliasses: isReal, isDouble)
	 * 	+ isInt (Aliasses: isInteger)
	 * 	+ isIP (Aliasses: isIp)
	 * 	+ matches (Aliasses: isValid)
	 * 	+ regexp
	 * 	+ isURL (Aliasses: isUrl)
	 * 
	 * @param string $name Filter selected.
	 * @return mixed Depends on the filter type.
	 * @throws \LogicException When using the regexp type without defining the pattern
	 * @throws \OutOfBoundsException When choosing an invalid filter type.
	 */
	public function __get ($name)
	{
		switch ($name) {
			case 'HTML':
			case 'html':
			case 'htmlEntities':
				return $this->HTML ();
			case 'asEmail':
			case 'asEMail':
				$filter = FILTER_SANITIZE_EMAIL;
				break;
			case 'URLEncoded':
			case 'urlEncoded':
				$filter = FILTER_SANITIZE_ENCODED;
				break;
			case 'magicQuotes':
				$filter = FILTER_SANITIZE_MAGIC_QUOTES;
				break;
			case 'asFloat':
			case 'asReal':
			case 'asDouble':
				$filter = FILTER_SANITIZE_NUMBER_FLOAT;
				break;
			case 'asInt':
			case 'asInteger':
				$filter = FILTER_SANITIZE_NUMBER_INT;
				break;
			case 'specialChars':
			case 'htmlEspecialChars':
			case 'HTMLEspecialChars':
				$filter = FILTER_SANITIZE_SPECIAL_CHARS;
				break;
			case 'fullSpecialChars':
			case 'htmlFullSpecialChars':
			case 'HTMLFullSpecialChars':
				$filter = FILTER_SANITIZE_FULL_SPECIAL_CHARS;
				break;
			case 'asString':
			case 'stripped':
				$filter = FILTER_SANITIZE_STRING;
				break;
			case 'asURL':
			case 'asUrl':
				$filter = FILTER_SANITIZE_URL;
				break;
			case 'asRaw':
			case 'asOrigin':
			case 'asUnsafe':
			case 'data':
				return $this->data;
			case 'isBool':
			case 'isBoolean':
				$this->setFlag (FILTER_NULL_ON_FAILURE);
			case 'asBool':
			case 'asBoolean':
				$filter = FILTER_VALIDATE_BOOLEAN;
				break;
			case 'isEmail':
				$filter = FILTER_VALIDATE_EMAIL;
				break;
			case 'isFloat':
			case 'isReal':
			case 'isDouble':
				$filter = FILTER_VALIDATE_FLOAT;
				break;
			case 'isInt':
			case 'isInteger':
				$filter = FILTER_VALIDATE_INT;
				break;
			case 'isIP':
			case 'isIp':
				$filter = FILTER_VALIDATE_IP;
				break;
			case 'matches':
			case 'isValid':
			case 'regexp':
				if (!isset ($this->options ['options'] ['regexp'])) {
					$message = _ ('Assign regexp option first');
					throw new \LogicException ($message);
				}

				$pattern = $this->options ['options'] ['regexp'];
				return VarsFilterManager::pregMatch ($pattern, $this->data);
			/*
			  By using VarsFilterManager you can control errors better.
			  $filter = FILTER_VALIDATE_REGEXP;
			 */
			case 'isURL':
			case 'isUrl':
				$filter = FILTER_VALIDATE_URL;
				break;
			default:
				$message = _ ('Invalid property.');
				throw new \OutOfBoundsException ($message);
		}

		$filtered = filter_var ($this->data, $filter, $this->options);

		if ($filter == FILTER_VALIDATE_BOOLEAN &&
				$this->options ['flags'] & FILTER_NULL_ON_FAILURE
		) {
			return ($filtered === NULL) ? false : true;
		}

		if ((strstr ($name, 'is') === 0)) {
			return (bool) $filtered;
		} else {
			return $filtered;
		}
	}

	public function __toString ()
	{
		return $this->data;
	}

}