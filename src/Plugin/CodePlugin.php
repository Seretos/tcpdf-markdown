<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class CodePlugin extends AbstractPlugin
{
    public function parse(string $markdown, int $position): ?PluginTokenResult
    {
        [$sol, $indentPos] = $this->lineStartAndIndent($markdown, $position);
        if ($position > $indentPos) {
            return null;
        }

        $slice = substr($markdown, $position);
        if (preg_match('/^(?:\r?\n)*```(\w+)?\r?\n(.*?)```/s', $slice, $m)) {
            $type = $m[1];
            $content = $m[2];
            $newPos  = $position + strlen($m[0]);

            return new PluginTokenResult([
                new MarkdownToken('CODE_START', $type),
                new MarkdownToken('CODE_CONTENT', $content, true, ['type' => $type]),
                new MarkdownToken('CODE_END', $type)
            ], $newPos);
        }

        return null;
    }

    public function supports(?MarkdownToken $token = null): bool
    {
        return $token === null || $token->getType() === 'GREATER_QUOTE_CONTENT';
    }
}