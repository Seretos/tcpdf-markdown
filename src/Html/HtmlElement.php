<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Html;

use seretos\TCPDFMarkdown\Node;
use seretos\TCPDFMarkdown\TransformNodeInterface;

abstract class HtmlElement implements TransformNodeInterface
{
    /**
     * @var HtmlElement[]
     */
    protected array $children = [];

    public function __construct(
        protected readonly Node $node
    )
    {
    }

    public function addChildren(HtmlElement $child): void {
        $this->children[] = $child;
    }

    /**
     * @return HtmlElement[]
     */
    public function getChildren(): array {
        return $this->children;
    }

    public function __toString(): string
    {
        return implode('', $this->children);
    }
}