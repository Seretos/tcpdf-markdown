<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class NewlinePlugin extends AbstractPlugin
{

    public function parse(string $markdown, int $position): ?PluginTokenResult
    {
        $substr = substr($markdown, $position);
        if (preg_match('/^(\r?\n)/', $substr, $matches)) {
            $match = $matches[0];
            $tokens = [
                new MarkdownToken('NEWLINE', $match)
            ];
            $newPos = $position + strlen($match);
            return new PluginTokenResult($tokens, $newPos);
        }

        return null;
    }

    public function supports(?MarkdownToken $token = null): bool
    {
        return $token !== null && in_array($token->getType(), ['TAB_QUOTE_CONTENT', 'GREATER_QUOTE_CONTENT','CODE_CONTENT']);
    }
}