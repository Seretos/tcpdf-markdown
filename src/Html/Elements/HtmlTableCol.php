<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Html\Elements;

use seretos\TCPDFMarkdown\Html\HtmlElement;

class HtmlTableCol extends HtmlElement
{
    public function __toString(): string
    {
        return '<td align="' . $this->node->getValue() . '">' . parent::__toString() . '</td>';
    }
}