<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Html\Elements;

use seretos\TCPDFMarkdown\Html\HtmlElement;

class HtmlDocument extends HtmlElement
{
    public function __toString(): string
    {
        return '<html lang="en">
<head>
<link rel="stylesheet" href="./default.css"/>
</head>
<body><div class="container">' . parent::__toString() . '</div></body></html>';
    }
}