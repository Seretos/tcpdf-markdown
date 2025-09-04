<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown;

class TokenParser
{
    /**
     * @param MarkdownToken[] $tokens
     * @return Node
     */
    public function parse(array $tokens): Node
    {
        $root = new Node('DOCUMENT');
        $stack = [$root];

        foreach ($tokens as $token) {
            $current = &$stack[count($stack) - 1];

            if (str_ends_with($token->getType(), '_START')) {
                $node = new Node(substr($token->getType(), 0, strlen($token->getType())-6), $token->getContent(), $token->getMetaData());
                $current->addChild($node);
                $stack[] = $node;
            } else if (str_ends_with($token->getType(), '_END')) {
                array_pop($stack);
            } else {
                $current->addChild(new Node($token->getType(), $token->getContent(), $token->getMetaData()));
            }
        }

        return $root;
    }
}