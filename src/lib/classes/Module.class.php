<?php
/**
 * Module.class.php
 * Creted By: blackfox
 * Create Date: Oct 07, 2010
 * Last Modified: 0ct 21, 2010
 *
 * Module is a container of modules, it saves the module name, page title,
 * calculates key words, etc...
 */

require_once ('config.php');

class Module {
	private $content, $blocks, $id, $title, $theme;

	/**
	 * This method sets the page title.
	 *
	 * @param string $title
	 * The title of the page.
	 */

	public function setTitle ($title)
	{
		$this->title = htmlentities ($title, ENT_QUOTES, 'ISO8859-15');
	}

	/**
	 * Sets the content of the module.
	 *
	 * @param string $content
	 * The content of the module.
	 */

	public function setContent ($content)
	{
		$this->content = $content;
	}
	
	/**
	 * Like sets content but appending the content.
	 *
	 * @param string $content
	public function addContent ($content, $offset = 1)
	{
		if ($offset = 0)
			$this->content = $content . $this->content;
		else
			$this->content .= $content;
	}
	
	public function setTheme ($themename)
	{
		$theme = THEMESPATH . $themename;
	}
	
	public function getKeyWords ()
	{
		// TODO: implements getKeyWords function
		return "";
	}
	
	public function __get ($var)
	{
		switch ($vat) {
			case 'keywords':
				return $this->getKeyWords();
				break;
		}
	}
}
