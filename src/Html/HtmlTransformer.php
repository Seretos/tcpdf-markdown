<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Html;

use seretos\TCPDFMarkdown\Factory;
use seretos\TCPDFMarkdown\Node;
use seretos\TCPDFMarkdown\NodeTransformerInterface;

class HtmlTransformer implements NodeTransformerInterface
{
    public function __construct(
        private readonly HtmlNodeFactoryInterface $factory = new Factory()
    )
    {
    }

    public function transform(Node $node): HtmlElement
    {
        $class = $this->factory->findHtmlNodeClass($node);
        /** @var $resultNode HtmlElement*/
        $resultNode = new $class($node);
        foreach ($node->getChildren() as $child) {
            $resultNode->addChildren($this->transform($child));
        }
        return $resultNode;
    }
}