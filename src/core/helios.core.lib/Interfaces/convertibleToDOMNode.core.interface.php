<?php

namespace Core\Helios;

/**
 * The instances of classes which implements this interface can be used as an
 * argument for the \DOMNode::appendChild () method.
 */
interface convertibleToDOMNode {
	/**
	 * This method returns a \DOMNode representation of the object.
	 * 
	 * @param \DOMDocument $parent_DOM_document
	 * @returns \DOMNode The \DOMNode representation of the object.
	 */
	public function asDOMNode (\DOMDocument $parent_DOM_document);
}

