<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Html;

use seretos\TCPDFMarkdown\Node;

interface HtmlNodeFactoryInterface
{
    public function findHtmlNodeClass(Node $node): string;
}