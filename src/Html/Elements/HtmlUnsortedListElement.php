<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Html\Elements;

use seretos\TCPDFMarkdown\Html\HtmlElement;

class HtmlUnsortedListElement extends HtmlElement
{
    public function __toString(): string
    {
        return '<li>' . parent::__toString() . '</li>';
    }
}