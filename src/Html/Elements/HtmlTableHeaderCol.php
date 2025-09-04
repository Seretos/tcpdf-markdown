<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Html\Elements;

use seretos\TCPDFMarkdown\Html\HtmlElement;

class HtmlTableHeaderCol extends HtmlElement
{
    public function __toString(): string
    {
        return '<th align="' . $this->node->getValue() . '">' . parent::__toString() . '</th>';
    }
}