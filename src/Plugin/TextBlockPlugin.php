<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class TextBlockPlugin extends AbstractPlugin
{
    public function parse(string $markdown, int $position): ?PluginTokenResult
    {
        $slice = substr($markdown, $position);
        if (!preg_match('/^(?!\n)(.*?)(?:\r?\n\r?\n|$)/s', $slice, $m)) {
            return null;
        }

        $content = rtrim($m[1], " \t");
        $newPos  = $position + strlen($m[0]);

        return new PluginTokenResult([
            new MarkdownToken('BLOCK_START'),
            new MarkdownToken('CONTENT', trim($content), true),
            new MarkdownToken('BLOCK_END')
        ], $newPos);
    }

    public function supports(?MarkdownToken $token = null): bool
    {
        return $token === null;
    }
}