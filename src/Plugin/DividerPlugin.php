<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class DividerPlugin extends AbstractPlugin
{
    public function __construct(int $priority, private readonly string $divider = '-')
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
        $dividerEscaped = preg_quote($this->divider, '/');
        if (!preg_match('/^[ \t]*[' . $dividerEscaped . '(?: )]{3,}[ \t]*(?:\r?\n|$)/', $slice, $m)) {
            return null;
        }
        $newPos  = $indentPos + strlen($m[0]);
        return new PluginTokenResult([
            new MarkdownToken('DIVIDER', $m[0])
        ], $newPos);
    }

    public function supports(?MarkdownToken $token = null): bool
    {
        return $token === null;
    }
}