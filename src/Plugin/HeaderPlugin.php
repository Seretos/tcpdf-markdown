<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class HeaderPlugin extends AbstractPlugin
{
    public function parse(string $markdown, int $position): ?PluginTokenResult
    {
        [$sol, $indentPos] = $this->lineStartAndIndent($markdown, $position);
        if ($position > $indentPos) {
            return null;
        }

        $slice = substr($markdown, $indentPos);
        if (!preg_match('/^(?:\r?\n)*(#{1,6})[ \t]+([^\r\n]+?)(?:\s*#+\s*)?(?:\r?\n|$)/', $slice, $m)) {
            return null;
        }
        $level   = strlen($m[1]);
        $content = rtrim($m[2], " \t");
        $newPos  = $indentPos + strlen($m[0]);

        $tokens = [
            new MarkdownToken('HEADER_START', $level),
            new MarkdownToken('CONTENT', $content, true),
            new MarkdownToken('HEADER_END', $level)
        ];
        return new PluginTokenResult($tokens, $newPos);
    }

    public function supports(?MarkdownToken $token = null): bool
    {
        return $token === null;
    }
}