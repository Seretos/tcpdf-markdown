<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Html\Elements;

use seretos\TCPDFMarkdown\Html\HtmlElement;
use seretos\TCPDFMarkdown\Node;

class HtmlImage extends HtmlElement
{
    public function __toString(): string
    {
        $img = '<img src="' . $this->node->getValue() . '" ';
        if(isset($this->node->getMetaData()['width']))
            $img .= 'width="' . $this->node->getMetaData()['width'] . '" ';
        if(isset($this->node->getMetaData()['height']))
            $img .= 'height="' . $this->node->getMetaData()['height'] . '" ';
        $img .= '/>';
        return $img;
    }
}