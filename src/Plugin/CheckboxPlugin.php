<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class CheckboxPlugin extends AbstractPlugin
{
    public function parse(string $markdown, int $position): ?PluginTokenResult
    {
        $slice = substr($markdown, $position);
        if (preg_match('/^(\[([ x])\])[ \t]{1,}[^\s]/', $slice, $m)) {
            $newPos = $position + strlen($m[1]);

            return new PluginTokenResult([
                new MarkdownToken('CHECKBOX', strcasecmp($m[2],'x') === 0)
            ], $newPos);
        }

        return null;
    }

    public function supports(?MarkdownToken $token = null): bool
    {
        return $token !== null && in_array($token->getType(), ['SORTED_LIST_ELEMENT_CONTENT','UNSORTED_LIST_ELEMENT_CONTENT']);
    }
}