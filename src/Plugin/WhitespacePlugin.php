<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class WhitespacePlugin extends AbstractPlugin
{
    public function parse(string $markdown, int $position): ?PluginTokenResult
    {
        $substr = substr($markdown, $position);
        if (preg_match('/^(\r?\n){2}/', $substr, $matches)) {
            $match = $matches[0];
            $tokens = [
                new MarkdownToken('NEWLINE', $match)
            ];
            $newPos = $position + strlen($match);
            return new PluginTokenResult($tokens, $newPos);
        }

        if (preg_match('/^(\r?\n)/', $substr, $matches)) {
            $tokens = [
                new MarkdownToken('WHITESPACE', ' ')
            ];
            $newPos = $position + strlen($matches[1]);
            return new PluginTokenResult($tokens, $newPos);
        }

        if (preg_match('/^([ \t]+)/', $substr, $matches)) {
            $tokens = [
                new MarkdownToken('WHITESPACE', $matches[1])
            ];
            $newPos = $position + strlen($matches[1]);
            return new PluginTokenResult($tokens, $newPos);
        }
        return null;
    }
}