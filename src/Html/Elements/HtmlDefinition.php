<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Html\Elements;

use seretos\TCPDFMarkdown\Html\HtmlElement;

class HtmlDefinition extends HtmlElement
{
    public function __toString(): string
    {
        return '<dd>' . $this->node->getValue() . '</dd>';
    }
}