<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class InlineCodePlugin extends AbstractPlugin
{
    public function parse(string $markdown, int $position): ?PluginTokenResult
    {
        $slice = substr($markdown, $position);
        if (preg_match('/^`((?:[^\r\n`]+(?:\r?\n(?!\r?\n)[^\r\n`]*)*)?)`/', $slice, $m)) {
            $newPos = $position + strlen($m[0]);

            return new PluginTokenResult([new MarkdownToken('INLINE_CODE', trim($m[1]))],$newPos);
        }
        return null;
    }

    public function supports(?MarkdownToken $token = null): bool
    {
        return $token === null || $token->getType() !== 'TAB_QUOTE_CONTENT';
    }
}