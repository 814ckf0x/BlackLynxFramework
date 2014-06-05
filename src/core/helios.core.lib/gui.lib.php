<?php

namespace Helios\GUI;

class HTMLObject
{
	protected $name;
	protected $attributes = NULL;
	protected $childs = NULL;

	public function __construct ($name, $attributes = NULL)
	{
		$this->name = $name;

		if ($attributes && is_array ($attributes)) {

			$this->setAttribute (array_keys ($attributes),
								 array_values ($attributes)
								);
		} else {
			throw new InvalidArgumentException (
				_('Attributes most be an array')
			);
		}
	}

	public function getHTMLContent ()
	{
		$content = "";
		$attrstring = "";

		if ($this->childs != NULL) {//Has childs
			foreach ($this->childs as $child) {

				$content .= $child->getHTMLContent;
			}
		}
		
		if ($this->attributes !== NULL) {
			foreach ($this->attributes as $attrname => $attrvalue) {
				$attrstring .= ' ' .
							   (($attrvalue != "")?
								   $attrname . '="' . $attrvalue . '"' :
								   $attrname // Bolean attribute.
							   );
			}
		}
		
		return ($content !== "")?
				"<{$this->name}{$attrstring}>{$content}</{$this->name}>" :
				"<{$this->name}{$attrstring}>"; // Void element.
	}

	public function setAttribute ($attrname, $attrvalue = NULL)
	{
		if (is_array ($attrvalue)) {

			reset ($attrvalue); //Needed?
			foreach ($attrName as $attr) {

				$val = each ($attrvalue);
				if ($val === false ||
					$val ['value'] === NULL ||
					$val ['value'] === ""
				) { //if
					$val ['value'] = ""; // Bolean attribute.
				}
				$this->attributes [$attr] = $val ['value'];
			}
		} else {
			$this->attributes [$attrname] = $attrvalue;
		}
	}

	private function attrIsSet ($attrname)
	{
		if (!isset ($this->attributes [$attrname]))
			throw new OutOfBoundsException (
				$attrname . ' ' . _('attribute is not set.')
			);
	}

	public function getAttribute ($attrname)
	{
		$this->attrIsSet ($attrname);
		return $this->attributes [$attrname];
	}

	public function delAttribute ($attrname)
	{
		$this->attrIsSet ($attrname);
		unset ($this->attributes [$attrname]);
	}

	public function appendChild (HTMLObject &$child)
	{
		$this->childs [] =& $child;
	}
	
	public function appendText ($text)
	{
		$text = new HTMLRawData ($text);
		$this->appendChild ($text);
	}
}

class HTMLRawData extends HTMLObject
{
	private $data;
	
	public function __constructor ($data = NULL)
	{
		if ($data === NULL)
			$data = "";
		
		$this->data = $data;
	}
	
	public function __toString ()
	{
		return $this->data;
	}
	
	public function getHTMLContent ()
	{
		return (string) $this->data;
	}
}

class HTMLFormInput extends HTMLObject
{
	private $label;
	
	public function __construct ($name, $label, $type = "text", $pattern = NULL)
	{
		parent::__construct ('input');
		/*
		$attrname [] = 'type';
		$attrvalue [] = $type;

		$attrname [] = 'name';
		$attrvalue [] = $name;
		
		$attrname [] = 'id'; // id can be replaced
		$attrvalue [] = $name;
		 */
		 
		 $attrname = array ('type', 'name', 'id');
		 $attrvalue = array ($type, $name, $name);
		
		if (isset ($pattern)) {
			$attrname [] = 'pattern';
			$attrvalue [] = $pattern;
		}
		
		parent::setAttribute ($attrname, $attrvalue);
		
		$this->label = new HTMLObject ('label');
	}

	public function getHTMLContent ()
	{
		$this->label->setAttribute ('for', parent::getAttribute ('id'));
		
		return $this->label->getHTMLContent () . parent::getHTMLContent();
	}
}

class HTMLFormFieldset extends HTMLObject
{
	public function __construct ($legend = NULL, $formid = NULL)
	{
		parent::__construct ('fieldset');

		if ($legend !== NULL) {
			$legend = new HTMLObject ('legend');
			$legend->appendChild (new HTMLRawData ($legend));
			
			parent::appendChild ($legend);
		}
		
		if ($formid !== NULL) {
			parent::setAttribute ('form', $formid);
		}
	}
}

class HTMLFormulary extends HTMLObject
{
	private $fieldsets = NULL;
	
	public function __construct ($action = NULL, $method = post)
	{
		$attributes = array ('action' => $action, 'method' => $method);
		parent::__construct ('form', $attributes);
	}
	
	public function addFieldSet (HTMLFormFieldset &$fieldset)
	{
		parent::appendChild ($fieldset);
	}
}

class HTMLTemplateLoader extends HTMLObject
{
	
}
