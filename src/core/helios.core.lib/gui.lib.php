<?php

namespace Helios\GUI;

/**
 * The instances of classes which implements this interface can be used as an
 * argument for the \DOMNode::appendChild () method.
 */
interface convertibleToDOMNode {
	/**
	 * As __toString () method returns a string representation of the object,
	 * this function returns a \DOMNode representation of the object.
	 * 
	 * @param \DOMDocument $parent_DOM_document
	 * @returns \DOMNode The \DOMNode representation of the object.
	 */
	public function asDOMNode (\DOMDocument $parent_DOM_document);
}

/**
 * This class adds some useful functions to work with HTMLPage.
 * 
 * The main change into this function is the modification of the appendChild ()
 * method, wich can recive as argument a \DOMNode or any object which its class
 * implements the \Helios\GUI\convertibleToDOMNode interface.
 */
class HTMLPageNode extends \DOMNode
{
	/**
	 * Now the argument can be a \DOMNode or an interface of an object which its
	 * class implements the \Helios\GUI\convertibleToDOMNode interface.
	 * 
	 * @param mixed $new_node the appened child.
	 * @return \HTMLPageNode The node added.
	 */
	public function appendChild ($new_node)
	{
		if ($new_node instanceof convertibleToDOMNode) {
			return parent::appendChild ($new_node->asDOMNode($this->ownerDocument));
		} else {
			return parent::appendChild ($new_node);
		}
	}

	/**
	 * Removes it self from the parent node.
	 *
	 * @return \DOMNode The removed child.
	 */
	public function remove ()
	{
		return $this->parentNode->removeChild ($this);
	}

	/**
	 * Removes all childs and all values.
	 */
	public function childless ()
	{
		$this->nodeValue = "";
	}
}

/**
 * Represents a HTML5 page.
 * 
 * \Helios\GUI\HTMLPage is a template-based \DOMDocument class which loads a
 * file containing the template.
 * 
 * Templates structure MUST have the next DOM elements:
 * 
 * - Content template element, id = \Helios\GUI\HTMLPage::CONT_TEMPLATE_ID (default:
 * ContentTemplate)
 * 
 * This element will be used as template of the page content. This element must
 * have a children with id = \Helios\GUI\HTMLPage::CONT_TITLE_ID (default: ContentTitle),
 * a children with id = \Helios\GUI\HTMLPage::CONT_CONTENT_ID (default: ContentContent),
 * a children with id = \Helios\GUI\HTMLPage::CONT_FOOTER_ID (default: ContentFooter) where
 * will be placed the article/content title, the content and the footer
 * respectively. E.g:
 * 
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~{.html}
 * <...>
 *	<article id='ContentTemplate'>
 *		<h1 id='ContentTitle'></h1>
 *		<div id='ContentContent'></div>
 *		<div id='ContentFooter'></div>
 *	</article>
 * </...>
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * 
 * > This element will be deleted from the document in order to be used only as
 * > a template, each id tag will be unset.
 * 
 * - Block template element, id = \Helios\GUI\HTMLPage::BLOCK_TEMPLATE_ID (default:
 * BlockTemplate)
 * 
 * This element will be used as template of blocks content. Inside there must be
 * a children with the id = \Helios\GUI\HTMLPage::BLOCK_TITLE_ID (default: BlockTitle) in
 * which the title text will be and a second one with id =
 * \Helios\GUI\HTMLPageElement::BLOCK_CONTENT_ID for the content. E.g:
 * 
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~{.html}
 * <...>
 *	<aside id='BlockTemplate'>
 *		<h1 id='BlockTitle'></h1>
 *		<div id='BlockContent'></div>
 *	</aside>
 * </...>
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * 
 * > This element will be deleted from the document in order to be used only as
 * > a template, each id tag will be unset.
 * 
 * - Main content element, id = \Helios\GUI\HTMLPage::CONTENTS_ID (default: MainContent)
 * 
 * Used to append the content.
 * 
 * - Footer element, id = \Helios\GUI\HTMLPage::FOOTER_ID (default: PageFooter)
 * 
 * Used to append footer information.
 * 
 * - Main menu element, id = \Helios\GUI\HTMLPage::MAIN_MENU_ID (default: MainMenu)
 * 
 * Used to append the main navigation menu.
 * 
 * - Block positions id
 * 
 * The posible positions of blocks.
 * 
 *  + \Helios\GUI\HTMLPage::TOP_BLOCKS_ID (default: TopBlocks)
 *  + \Helios\GUI\HTMLPage::LEFT_BLOCKS_ID (default: LeftBlocks)
 *  + \Helios\GUI\HTMLPage::RIGHT_BLOCKS_ID (default: RightBlocks)
 *  + \Helios\GUI\HTMLPage::BOTTOM_BLOCKS_ID (default: BottomBlocks)
 * 
 * A basic template could be like:
 * 
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~{.html}
 * <!DOCTYPE html>
 * <html>
 * <head>
 * <title></title>
 * </head>
 * <body>
 * <aside id='BlockTemplate'>
 *  <h1 id='BlockTitle'></h1>
 *  <div id='BlockContent'></div>
 * </aside>
 * <article id='ContentTemplate'>
 *  <h1 id='ContentTitle'></h1>
 *  <div id='ContentContent'></div>
 *  <div id='ContentFooter'></div>
 * </article>
 * <div id='MainMenu'></div>
 * <div id='TopBlocks></div>
 * <div id='LeftBlocks></div>
 * <div id='MainContent'></div>
 * <div id='RightBlocks'></div>
 * <div id='BottomBlocks'></div>
 * <div id='PageFooter'></div>
 * </body>
 * </html>
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 */
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
	const CONT_FOOTER_ID		= 'ContentFooter';

	const BLOCK_TEMPLATE_ID	= 'BlockTemplate';
	const BLOCK_TITLE_ID		= 'BlockTitle';
	const BLOCK_CONTENT_ID	= 'BlockContent';

	const TOP_BLOCKS_ID		= 'TopBlocks';
	const LEFT_BLOCKS_ID		= 'LefBlocks';
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
	 * Without $attr_value this method will search for all elements which have
	 * the specified attribute without worryng about its value.
	 * 
	 * @param \DOMElement $parent The root element.
	 * @param string $attr_name The attribute used to discriminate.
	 * @param string $attr_value The value of the attribute (Optional).
	 * @return array containing the found elements.
	 */
	static public function searchElementsByAttribute (\DOMElement $parent,
													  $attr_name,
													  $attr_value = NULL
													 )
	{
		$found_elements = array (); // It should be a \DOMElementList object
		foreach ($parent->childNodes as $child) {
			if ($attr_value === NULL) {
				$child->hasAttribute ($attr_name);
			}elseif ($child->getAttribute ($attr_name) == $attr_value){
				$found_elements [] = $child;
			}

			if ($child->hasChildNodes ()) {
				$found_elements = array_merge ($found_elements,
											   self::searchElementsByAttribute ($child,
																			    $attr_name,
																			    $attr_value
																			   )
											  );
			}
		}
		return $found_elements;
	}
	//TODO: Rename?.

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

			if (!($var instanceof \DOMNode)) {
				$var = new \DOMText ((string) $var);
			}

			$element->appendChild ($var);
			$element->removeAttribute ('id');
		}
		
		return $newElement;
	}
	
	/**
	 * Creates a new \Helios\GUI\HTMLPage object.
	 * 
	 * @param string $template The path to the template.
	 * @param string $version The XML version
	 * @param string $encoding The encoding.
	 */
	public function __construct ($template, $version = NULL, $encoding = NULL)
	{
		parent::__construct ($version, $encoding);
		$this->loadHTMLFile ($template);
		
		// Extract the sub-templates and remove it from the document.
		$content_template = $this->getElementById (self::CONT_TEMPLATE_ID);
		$block_template = $this->getElementById (self::BLOCK_TEMPLATE_ID);
		
		$this->contentTemplate = $this->removeChild ($content_template);
		$this->blockTemplate = $this->removeChild ($block_template);
		
		$this->template = $template;
		
		$this->registerNodeClass('HTMLPageNode', 'DOMNode');
	}
	
	/**
	 * Adds a content into the document.
	 * 
	 * Content can be 
	 * 
	 * @param type $title
	 * @param \DOMElement $content
	 * @param \DOMElement $footer
	 * @param type $weight
	 * @return type
	 */
	public function addContent ($title,
								$content,
								$footer = NULL, //FIXME: use BBCode from DB!!!
								$weight = 0
							   )
	{
		$vars = array (self::CONT_TITLE_ID		=> $title,
					   self::CONT_CONTENT_ID	=> $content,
					   self::CONT_FOOTER_ID		=> $footer
					  );

		$new_content = self::parseTemplate ($this->contentTemplate, $vars);
		$this->contentList [$weight] [] = $new_content;
		
		return $new_content;
	}
	
	public function addBlock ($title,
							  \DOMElement $content,
							  $position = self::BLOCK_POS_LEFT,
							  $weight = 0
							 )
	{
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

class HTMLNavMenuItem implements convertibleToDOMNode
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

class HTMLNavMenu implements convertibleToDOMNode
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
