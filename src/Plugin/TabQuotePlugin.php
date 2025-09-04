<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class TabQuotePlugin extends AbstractPlugin
{
    public function parse(string $markdown, int $position): ?PluginTokenResult
    {
        [$sol, $indentPos] = $this->lineStartAndIndent($markdown, $position);
        if ($position > $indentPos) {
            return null;
        }

        $slice = substr($markdown, $position);
        if (preg_match('/^(?:[ \t]*\r?\n*)((?: {4}|\t).*(?:\r?\n|$)(?:(?: {4}|\t).*(?:\r?\n|$)|[ \t]*\r?\n)*)/', $slice, $m)) {
            $content = $m[1];
            $newPos  = $position + strlen($m[0]);

            return new PluginTokenResult([
                new MarkdownToken('QUOTE_START', 'tab'),
                new MarkdownToken('TAB_QUOTE_CONTENT', trim($this->clearContent($content)), true),
                new MarkdownToken('QUOTE_END')
            ], $newPos);
        }

        return null;
    }

    private function clearContent(string $content): string {
        $lines = explode("\n", $content);
        for ($i=0;$i<count($lines);$i++) {
            $lines[$i] = substr($lines[$i], 4, strlen($lines[$i]));
        }
        return implode("\n", $lines);
    }

    public function supports(?MarkdownToken $token = null): bool
    {
        return $token === null || in_array($token->getType(), ['GREATER_QUOTE_CONTENT']);
    }
}