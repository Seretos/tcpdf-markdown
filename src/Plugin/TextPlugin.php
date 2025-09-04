<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class TextPlugin extends AbstractPlugin
{
    public function parse(string $markdown, int $position): ?PluginTokenResult
    {
        $substr = substr($markdown, $position);
        if (preg_match('/^(?:[A-Za-z0-9]+|[*_~]+|\/\*)/', $substr, $matches)) {
            $word = $matches[0];
            $tokens = [
                new MarkdownToken('TEXT', $word)
            ];
            $newPos = $position + strlen($word);
            return new PluginTokenResult($tokens, $newPos);
        }

        return null;
    }
}