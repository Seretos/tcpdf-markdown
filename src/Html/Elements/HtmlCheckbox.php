<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Html\Elements;

use seretos\TCPDFMarkdown\Html\HtmlElement;

class HtmlCheckbox extends HtmlElement
{
    public function __toString(): string
    {
        $checked = '';
        if($this->node->getValue())
            $checked = 'checked';
        return '<input type="checkbox" ' . $checked . '/>';
    }
}