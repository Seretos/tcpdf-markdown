<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Html\Elements;

use seretos\TCPDFMarkdown\Html\HtmlElement;

class HtmlHeader extends HtmlElement
{
    public function __toString(): string
    {
        return '<h' . $this->node->getValue() . '>' . parent::__toString() . '</h' . $this->node->getValue() . '>';
    }
}