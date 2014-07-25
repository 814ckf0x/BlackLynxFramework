<?php

namespace Core\Helios\GUI;

class HTMLPage extends \DOMDocument
{
	/**
	 * Represents blocks positions in human-readable way.
	 * @{
	 */
	const BLOCK_POS_TOP		= 0;
	const BLOCK_POS_LEFT	= 1;
	const BLOCK_POS_RIGHT	= 2;
	const BLOCK_POS_BOTTOM	= 3;
	/** @} */

	/**
	 * This constants will be used as html-id to look for the element into the
	 * template.
	 * @{
	 */
	const CONT_TEMPLATE_ID	= 'ContentTemplate';
	const CONT_TITLE_ID		= 'ContentTitle';
	const CONT_CONTENT_ID	= 'ContentContent';
	const CONT_FOOTER_ID	= 'ContentFooter';

	const BLOCK_TEMPLATE_ID	= 'BlockTemplate';
	const BLOCK_TITLE_ID	= 'BlockTitle';
	const BLOCK_CONTENT_ID	= 'BlockContent';

	const TOP_BLOCKS_ID		= 'TopBlocks';
	const LEFT_BLOCKS_ID	= 'LefBlocks';
	const RIGHT_BLOCKS_ID	= 'RightBlocks';
	const BOTTOM_BLOCKS_ID	= 'BottomBlocks';
	const CONTENTS_ID		= 'MainContent';
	const FOOTER_ID			= 'PageFooter';
	const MAIN_MENU_ID		= 'MainMenu';
	/** @} */

	/** 
	 * Boolean which represents if the documents is already parsed.
	 */
	protected $parsed = false;

	/**
	 * A path pointing to the template file.
	 */
	protected $template;

	/**
	 * A sub template used to insert content.
	 */
	protected $contentTemplate;

	/**
	 * A sub template used to insert blocks.
	 */
	protected $blockTemplate;

	/**
	 * An array containing the content of the page.
	 * 
	 * In the first level it is divided by posible weights to order the content,
	 * i.e.:
	 * 
	 * $this->contentList [Weight] = array ( contents... )
	 */
	protected $contentList = NULL;

	/**
	 * An array containing the page blocks.
	 * 
	 * In the first level the array is divided by the page block positions, at
	 * the second level is divided by weights, i.e.:
	 * 
	 * $this->blockList [Position] [Weight] = array ( blocks... )
	 */
	protected $blockList = NULL;

	/**
	 * A HTMLNavMenu object with the main menu of the page.
	 */
	protected $mainMenu = NULL;

	/**
	 * A static method used to search through an DOM elements tree for elements
	 * with a specific attribute.
	 * 
	 * Without $attr_val this method will search for all elements which have
	 * the specified attribute without worryng about its value.
	 * 
	 * @param \DOMElement $parent The root element.
	 * @param string $attr_name The attribute used to discriminate.
	 * @param string $attr_val The value of the attribute (Optional).
	 * @return array containing the found elements.
	 */
	static public function searchElementsByAttribute (\DOMElement $parent,
													  $attr_name,
													  $attr_val = NULL
													 )
	{
		$found_elements = array (); // It should be a \DOMElementList object

		foreach ($parent->childNodes as $child) {
			if ($attr_val === NULL) {
				$child->hasAttribute ($attr_name);
			}elseif ($child->getAttribute ($attr_name) == $attr_val){
				$found_elements [] = $child;
			}

			if ($child->hasChildNodes ()) {
				//Recursivity
				$r = self::searchElementsByAttribute ($child, $attr_name, $attr_val)

				$found_elements = array_merge ($found_elements, $r);
			}
		}
		return $found_elements;
	}
	//TODO: Rename?.

	/**
	 * Creates a new \Helios\GUI\HTMLPage object.
	 * 
	 * @param string $template The path to the template.
	 * @param string $version The XML version
	 * @param string $encoding The encoding.
	 */
	public function __construct ($template, $version = NULL, $encoding = NULL) // FIXME: Null as default...
	{
		parent::__construct ($version, $encoding);
		$this->loadHTMLFile ($template);
		
		// Extract the sub-templates and remove it from the document.
		$content_template = $this->getElementById (self::CONT_TEMPLATE_ID);
		$block_template = $this->getElementById (self::BLOCK_TEMPLATE_ID);
		
		$this->contentTemplate = $this->removeChild ($content_template);
		$this->blockTemplate = $this->removeChild ($block_template);
		
		$this->template = $template;
		
		//Register the HTMLPageNode as DOMNode class
		$this->registerNodeClass('HTMLPageNode', 'DOMNode');
	}
	
	/**
	 * Adds a content into the document.
	 * 
	 * @param string $title
	 * @param mixed $content Can be a \DOMElement or a string
	 * @param mixed $footer Can be a \DOMElement or a string
	 * @param integer $weight
	 * @return HTMLPageNode
	 */
	public function addContent ($title,
								$content,
								$footer = NULL,
								$weight = 0
							   )
	{
		if (!($content instanceof \DOMElement)) {
			$content = $this->createTextNode ((string) $content);
		}

		if (!($footer instanceof \DOMElement)) {
			$footer = $this->createTextNode ((string) $footer);
		}

		$vars = array (self::CONT_TITLE_ID		=> $title,
					   self::CONT_CONTENT_ID	=> $content,
					   self::CONT_FOOTER_ID		=> $footer
					  );

		$new_content = self::parseTemplate ($this->contentTemplate, $vars);
		$this->contentList [$weight] [] = $new_content;
		
		return $new_content;
	}

	/**
	 * addBlock 
	 * 
	 * @param string $title 
	 * @param mixed $content 
	 * @param int $position 
	 * @param int $weight 
	 * @access public
	 * @return \Core\Helios\HTMLPageNode
	 */
	public function addBlock ($title,
							  $content,
							  $position = self::BLOCK_POS_LEFT,
							  $weight = 0
							 )
	{
		if (!($content instanceof \DOMElement)) {
			$content = $this->createTextNode ((string) $content);
		$vars = array (self::BLOCK_TITLE_ID		=> $title,
					   self::BLOCK_CONTENT_ID	=> $content
					  );

		$new_block = self::parseTemplate ($this->blockTemplate, $vars);
		$this->blockList [$position] [$weight] [] = $new_block;
		
		return $new_block;
	}
	
	public function addFooter (\DOMElement $content)
	{
		return $this->getElementById (self::FOOTER_ID)->appendChild ($content);
	}
	
	public function setPageTitle ($title)
	{
		$old_title = $this->getElementsByTagName ('title')->item (0);
		
		//clean title
		$old_title->nodeValue = '';
		
		$old_title->appendChild (new \DOMText ($title));
	}

	public function getPageTitle ()
	{
		return (string) $this->getElementByTagName ('title')->item (0)->nodeValue;
	}

	public function setMainMenu (HTMLMenu $menu)
	{
		$this->mainMenu = $menu;
	}

	public function parseDocument ()
	{
		if (!$this->parsed) {

			// Insert content
			if ($this->contentList !== NULL) {
				$this->appendListElements ($this->contentList,
										   $this->getElementById (self::CONTENTS_ID)
										  );
			}

			//Insert Blocks
			if ($this->blockList !== NULL) {
				$block_pos = Array (self::BLOCK_POS_TOP		=> self::TOP_BLOCKS_ID,
									self::BLOCK_POS_BOTTOM	=> self::BOTTOM_BLOCKS_ID,
									self::BLOCK_POS_LEFT	=> self::LEFT_BLOCKS_ID,
									self::BLOCK_POS_RIGHT	=> self::RIGHT_BLOCKS_ID
								   );

				foreach ($block_pos as $pos => $pos_id) {
					$this->appendListElements ($this->blockList [$pos],
											   $this->getElementById ($pos_id)
											  );
				}
			}

			//Insert main menu
			if ($this->mainMenu !== NULL) {
				$this->getElementById (self::MAIN_MENU_ID)->appendChild ($this->mainMenu);
			}

			$this->parsed = true;
		}
	}

	private function appendListElements ($element_list, \DOMElement $dest)
	{
		foreach ($element_list as $weight) {
			foreach ($weight as $element) {
				$dest->appendChild ($element);
			}
		}
	}
}

class HTMLNavMenuItem implements \Core\Helios\ConvertibleToDOMNode
{
	const HTML_MENU_ITEM_WRAPPER = 'li';

	protected $label;
	protected $URI;
	protected $HTMLWrapper;
	protected $AnchorDOMAttributes = array ();

	public function __construct ($label,
								 $URI = NULL,
								 $HTML_wrapper = self::HTML_MENU_ITEM_WRAPPER
								)
	{
		$this->label = $label;
		$this->URI = $URI;
		$this->HTMLWrapper = $HTML_wrapper;
	}

	public function setAnchorDOMAttributes ($name, $value = NULL)
	{
		if (is_array ($name)) {
			$this->AnchorDOMAttributes = array_merge($this->AnchorDOMAttributes,
													 $name
													);
		} else {
			$this->AnchorDOMAttributes [$name] = $value;
		}
	}

	public function asDOMNode (\DOMDocument $parent_DOM_document)
	{
		$DOM_element = $parent_DOM_document->createElement ($this->HTMLWrapper);

		if ($this->URI !== NULL) {
			$anchor = $DOM_element->appendChild (new \DOMElement ('a'));
			$anchor->setAttribute ('href', $this->URI);

			foreach ($this->AnchorDOMAttributes as $name => $value) {
				$anchor->setAttribute ($name, $value);
			}
		} else {
			$DOM_element->appendChild (new \DOMText ($this->label));
		}

		return $DOM_element;
	}
}

class HTMLNavMenuList extends HTMLNavMenuItem
{
	const HTML_MENU_LIST_WRAPPER = 'ul';

	protected $menuItems;
	protected $HTMLListWrapper;

	public function __construct ($label,
								 $URI = NULL,
								 $HTML_wrapper = parent::HTML_MENU_ITEM_WRAPPER,
								 $HTML_list_wrapper = self::HTML_MENU_LIST_WRAPPER
								)
	{
		parent::__construct ($label, $URI, $HTML_wrapper);

		$this->HTMLListWrapper = $HTML_list_wrapper;
	}

	public function appendMenuItem ($child)
	{
		if (is_array ($child)) {
			foreach ($child as $menu_item) {
				if ($menu_item instanceof HTMLNavMenuItem) {//or HTMLNavMenuList
					$this->menuItems [] = $menu_item;
				} //Else Ignore;
			}
		} elseif ($child instanceof HTMLNavMenuItem) {//or HTMLNavMenuList
			$this->menuItems [] = $child;
		} // Else Ignore
	}

	public function asDOMNode (\DOMDocument $parent_DOM_document) {
		$DOM_element = parent::asDOMNode ($parent_DOM_document);

		$list = $DOM_element->appendChild (new \DOMElement ($this->HTMLListWrapper));

		foreach ($this->menuItems as $menu_item) {
			$list->appendChild ($menu_item);
		}

		return $DOM_element;
	}

	public function __get ($name)
	{
		if ($name == 'menuItems') {
			return $this->menuItems;
		}
	}
}

class HTMLNavMenu implements \Core\Helios\ConvertibleToDOMNode
{
	const NAV_MENU_WRAPPER = 'nav';

	protected $menuDOMAttributes;

	protected $menuItems;
	protected $HTMLWrapper;
	protected $HTMLListWrapper;

	public function __construct ($HTML_wrapper = self::NAV_MENU_WRAPPER,
								 $HTML_list_wrapper = parent::MENU_LIST_WRAPPER
								)
	{
		$this->HTMLWrapper = $HTML_wrapper;
		$this->HTMLListWrapper = $HTML_list_wrapper;
	}

	public function appendMenuItem (HTMLNavMenuItem $child)
	{
		$this->menuItems [] = $child;
	}

	public function asDOMNode (\DOMDocument $parent_DOM_document)
	{
		$DOM_element = $parent_DOM_document->createElement ($this->HTMLWrapper);

		$list = $DOM_element->appendChild (new \DOMElement ($this->HTMLListWrapper));
		foreach ($this->menuItems as $menu_item) {
			$list->appendChild ($menu_item);
		}
	}
}


//TEST:

$tmp = new HTMLNavMenu ();
$items = array (new HTMLNavMenuItem ('test', 'http://nowhere'), new HTMLNavMenuItem ('test2', 'http://test2'));

$tmp->appendMenuItem ($items);
print_r ($tmp);
