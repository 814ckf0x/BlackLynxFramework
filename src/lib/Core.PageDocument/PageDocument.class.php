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

namespace Core\Lib\PageDocument;

/**
 * Description of PageDocument
 *
 * @author blackfox
 */

class PageDocument extends \DOMDocument
{

	const XML_DEFAULT_VERSION	 = '1.0';
	const XML_DEFAULT_ENCODING	 = 'utf-8';

	protected $XSLT;

	public function __construct ($xslt_path, $version = self::XML_DEFAULT_VERSION,
							  $encoding = self::XML_DEFAULT_ENCODING,
							  $xslt_version = self::XML_DEFAULT_VERSION,
							  $xslt_encoding = self::XML_DEFAULT_ENCODING
	)
	{
		parent::__construct ($version, $encoding);

		//Prepare the XSLT document
		$xslt_doc = new \DOMDocument ($xslt_version, $xslt_encoding);
		$xslt_doc->load ($xslt_path);

		$this->XSLT = new \XSLTProcessor ();
		$this->XSLT->importStylesheet ($xslt_doc);

		//Append root element
		$this->appendChild (new \DOMElement (self::ELEMENT_ROOT));

		//Create mandatory elements meta and contents;
		$this->documentElement->appendChild (new \DOMElement (self::ELEMENT_META));
		$this->documentElement->appendChild (new \DOMElement (self::ELEMENT_CONTENTS));
		//TODO: validate using DTD
	}

}