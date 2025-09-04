<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class LinkPlugin extends AbstractPlugin
{
    public function parse(string $markdown, int $position): ?PluginTokenResult
    {
        $slice = substr($markdown, $position);
        if (preg_match('/^\[((?>[^\[\]]+|\[(?1)\])*)\]\(([^)\s]+)(?:\s+["\']([^"\']+)["\'])?\)/s', $slice, $m)) {
            $content = $m[1];
            $link = $m[2];
            $matched = $m[0];
            $newPos = $position + strlen($matched);

            return new PluginTokenResult([
                new MarkdownToken('LINK_START', $link),
                new MarkdownToken('LINK_CONTENT', $content, true),
                new MarkdownToken('LINK_END', $link)
            ], $newPos);
        }

        return null;
    }

    public function supports(?MarkdownToken $token = null): bool
    {
        return $token !== null && in_array($token->getType(), ['CONTENT', 'UNSORTED_LIST_ELEMENT_CONTENT', 'SORTED_LIST_ELEMENT_CONTENT']);
    }
}