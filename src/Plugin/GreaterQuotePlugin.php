<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class GreaterQuotePlugin extends AbstractPlugin
{
    public function parse(string $markdown, int $position): ?PluginTokenResult
    {
        [$sol, $indentPos] = $this->lineStartAndIndent($markdown, $position);
        if ($position > $indentPos) {
            return null;
        }

        $slice = substr($markdown, $position);
        if (preg_match('/^(?:[ \t]*\r?\n)*((?:[ \t]*>.*(?:\r?\n|$))+)/', $slice, $m)) {
            $content = $m[1];
            $newPos  = $position + strlen($m[0]);

            return new PluginTokenResult([
                new MarkdownToken('QUOTE_START', '>'),
                new MarkdownToken('GREATER_QUOTE_CONTENT', $this->clearContent(trim($content)), true),
                new MarkdownToken('QUOTE_END')
            ], $newPos);
        }

        return null;
    }

    private function clearContent(string $content): string {
        $lines = explode("\n", $content);
        $lines = array_map(function($line) {
            return preg_replace('/^[ \t]*>\s?/', '', $line);
        }, $lines);
        return implode("\n", $lines);
    }

    public function supports(?MarkdownToken $token = null): bool
    {
        return $token === null || in_array($token->getType(), ['GREATER_QUOTE_CONTENT','CONTENT', 'SORTED_LIST_ELEMENT_CONTENT','UNSORTED_LIST_ELEMENT_CONTENT']);
    }
}