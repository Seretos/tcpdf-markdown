<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class SeTextPlugin extends HeaderPlugin
{
    public function __construct(int $priority, private readonly string $divider = '=', private readonly int $level = 1)
    {
        parent::__construct($priority);
    }

    public function parse(string $markdown, int $position): ?PluginTokenResult
    {
        [$sol, $indentPos] = $this->lineStartAndIndent($markdown, $position);
        if ($position > $indentPos) {
            return null;
        }

        $slice = substr($markdown, $indentPos);
        if (!preg_match('/^(?:\r?\n)*([^\r\n]+)\r?\n[ \t]*([' . $this->divider . ']+)[ \t]*(?:\r?\n|$)/', $slice, $m)) {
            return null;
        }
        $content = rtrim($m[1], " \t");
        $newPos  = $indentPos + strlen($m[0]);

        $tokens = [
            new MarkdownToken('HEADER_START', $this->level),
            new MarkdownToken('CONTENT', $content, true),
            new MarkdownToken('HEADER_END', $this->level)
        ];
        return new PluginTokenResult($tokens, $newPos);
    }

    public function supports(?MarkdownToken $token = null): bool
    {
        return $token === null;
    }
}