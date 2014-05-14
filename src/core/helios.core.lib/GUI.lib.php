<?php

namespace Library\GUI;

/**
 * Graphical User Interface
 *
 * @author 814ckf0x 814ckf0x@gmail.com
 * @version 0.1
 * @copyright 814ckf0x 814ckf0x@gmail.com, 17 March, 2011
 * @package GraphicalUserInterface
 */

/**
 * GUIObject
 **/
class GUIObject
{
	protected $data;
	protected $keyWords;

	public $description;

	public function __construct($name, $description = '')
	{
		$this->description = $description;

		// Create a new Document wich represents the main information of GUIObject
		$this->data = new DOMDocument (core::getConfigVal ('DOM_XML_Version'), core::getConfigVal ('Page_Encoding'));

		// set the name of the object as the root element of the document
		$this->data->appendChild (new DOMElement ($name));
	}

	/**
	 * getHTMLContent retun a string containin the HTML version of the object
	 * 
	 * @access public
	 * @return string HTML version of the object
	 */
	public function getHTMLContent ()
	{
		return $this->data->saveHTML ();
	}

	/**
	 * getXMLContent, like getHTMLContent but returns XML 
	 * 
	 * @access public
	 * @return string XML version of the object
	 */
	public function getXMLContent ()
	{
		return $this->data->saveXML ();
	}

	public function setKeyWords ($keywords)
	{
		$temp = array_map ('trim', explode (',', $keywords));
		$this->keyWords = array_merge ($this->keyWords, $temp);
	}
}

/**
 * GUITemplateLoader
 **/

define ('GUITL_OPEN_TAG', '#['); // Default '*['
define ('GUITL_CLOSE_TAG', ']'); // Default ']'

/*inline*/ function guitl_key2tag ($key)
{
	return GUITL_OPEN_TAG . $key . GUITL_CLOSE_TAG;
}

class GUITemplateLoader
{
	protected $templateFile;
	private $templatePath;
	private $loaded = false;

	public function __construct($templatepath)
	{
		$this->setTemplate ($templatepath);
	}

	public function setTemplate ($templatepath)
	{
		if (!file_exists ($templatepath)) {
			$message = _('The template does not exists');
			throw new InvalidArgumentException ($message);
		}

		$this->templatePath = $templatepath;

		$this->loaded = false;
	}

	public function replaceValues ($replacement)
	{
		if (!$this->loaded) {
			$this->loadTemplate ();
		}

		$templateFile = str_replace (
						array_map ('guitl_key2tag', array_keys ($replacement)), // converts each key in a tag
						array_values ($replacement),
						$templateFile
		);
	}

	protected function loadTemplate ()
	{
		$templateFile = file_get_contents ($templatePaht);
		$this->loaded = true;
	}

	protected function cleanTemplate ()
	{
		// Default: '/\*\[[^\]]*\]/';
		$pattern = '/' . preg_quote (GUITL_OPEN_TAG)
				. '[^' . preg_quote (GUITL_CLOSE_TAG) . ']*'
				. preg_quote (GUITL_CLOSE_TAG) . '/';
		preg_replace ($pattern, '', $templateFile);
	}
}

/**
 * GUI
 **/

class GUI extends GUITemplateLoader
{
	protected $data;
	protected $keyWords;
	protected $libraries;
	protected $plugins;
	protected $log;

	public $descriptions;

	
	public function __construct($name)
	{
		// Create a Valid XHTML Document
		$doctype = DOMImplementation::createDocumentType ('html',
						'//W3C//DTD XHTML 1.1//EN',
						'http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd');

		$this->data = DOMImplementation::createDocument('http://www.w3.org/1999/xhtml',
						'html',
						$doctype);

	}

	public function getLibrariesNeeded ()
	{
		// code...
	}

	public function getPluginsNeeded ()
	{
		// code...
	}

	public function getHTMLContent ()
	{
		// code...
	}

	public function getXMLContent ()
	{
		// code...	
	}

	public function setKeyWords ($keywords)
	{
		// code...
	}

	public function addJavaScriptLibrary ($libname)
	{
		// code...
	}
}

/**
 * GUIBlockRequester, request blocks to the core
 **/
class GUIBlockRequester
{
	public $Blocks;
	
	function requestBlock ($name, $content = '', $position = GUI_BR_LEFT, $template = '')
	{
		// code...
	}
}

/**
 * GUITemplate, needed to load templates
 **/
class GUITemplate extends GUI
{
	public $templateFile;
	
	public function __construct ($templatepath)
	{
		// code...
	}

	public function __destruct ()
	{
		// code...
	}

	public function setTemplate ($template)
	{
		// code...
	}

	protected function replaceValues ($values)
	{
		// code...
	}

	protected function loadTemplate ()
	{
		// code...
	}

	protected function cleanTemplate ()
	{
		// code...
		}
}