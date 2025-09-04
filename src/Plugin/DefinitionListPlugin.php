<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class DefinitionListPlugin extends AbstractPlugin
{
    public function parse(string $markdown, int $position): ?PluginTokenResult
    {
        $slice = substr($markdown, $position);
        if (preg_match('/^(?:[ \t]*\r?\n)*(?P<block>[^\r\n:]+(?:\r?\n[ \t]*:[^\r\n]+)+)/s', $slice, $m)) {
            $newPos = $position + strlen($m[0]);
            $block = $m['block'];
            $lines = preg_split('/\r?\n/', $block);
            $term = array_shift($lines);
            $tokens = [new MarkdownToken('DEFINITION_LIST_START', $term)];
            foreach ($lines as $line) {
                if (preg_match('/^[ \t]*:(.+)$/', $line, $dm)) {
                    $tokens[] = new MarkdownToken('DEFINITION', trim($dm[1]));
                }
            }
            $tokens[] = new MarkdownToken('DEFINITION_LIST_END');

            return new PluginTokenResult($tokens, $newPos);
        }
        return null;
    }

    public function supports(?MarkdownToken $token = null): bool
    {
        return $token === null;
    }
}