<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Html\Elements;

use seretos\TCPDFMarkdown\Html\HtmlElement;

class HtmlQuote extends HtmlElement
{
    public function __toString(): string
    {
        $content = parent::__toString();
        //$content = str_replace(' ', '&nbsp;', $content);
        return '<div class="quote">' . $content . '</div>';
    }
}