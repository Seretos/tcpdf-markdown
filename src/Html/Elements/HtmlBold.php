<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Html\Elements;

use seretos\TCPDFMarkdown\Html\HtmlElement;

class HtmlBold extends HtmlElement
{
    public function __toString(): string
    {
        return '<b>' . parent::__toString() . '</b>';
    }
}