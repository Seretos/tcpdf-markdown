<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Html\Elements;

use seretos\TCPDFMarkdown\Html\HtmlElement;

class HtmlDefinitionList extends HtmlElement
{
    public function __toString(): string
    {
        return '<dl><dt>' . $this->node->getValue() . '</dt>' . parent::__toString() . '</dl>';
    }
}