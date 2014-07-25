<?php

namespace Core\Helios;

class DOMTemplate implements ConvertibleToDOMNode
{
	protected $template;
	protected $DOMParent;
	protected $subtemplates;

	/**
	 * Takes a sub-template and looks for specific ids as variable values to
	 * append new elements.
	 * 
	 * The $vars parameter is an array which its keys represent the id to look
	 * look for into the template and its values represent the object to be append.
	 * 
	 * @param \DOMElement $template The sub-template to be parsed.
	 * @param array $vars An array containing the new elements.
	 */
	static public function parseTemplate (\DOMElement $template, $vars)
	{
		$newElement = $template->cloneNode ();

		foreach ($vars as $id => $var) {
			//$element = $newElement->getElementById ($id);
			$element = self::searchElementsByAttribute($newElement, 'id', $id);
			$element = $element [0];

			if (!($var instanceof \DOMNode)) {
				$var = new \DOMText ((string) $var);
			}

			$element->appendChild ($var);
			$element->removeAttribute ('id');
		}
		
		return $newElement;
	}
	
	public function __construct ($template, $DOM_parent)
	{
		$this->template = $template;
		$this->DOMParent = $DOM_parent;
	}

	public function replaceVars ()
	{
	}
	
	public function asDOMNode (\DOMDocument $parent_DOM_document)
	{
		
	}
}

