<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Html\Elements;

use seretos\TCPDFMarkdown\Html\HtmlElement;

class HtmlCodeKeyword extends HtmlElement
{
    public function __toString(): string
    {
        return '<span class="code_keyword">' . $this->node->getValue() . '</span>';
    }
}