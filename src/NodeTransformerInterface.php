<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown;

interface NodeTransformerInterface
{
    public function transform(Node $node): TransformNodeInterface;
}