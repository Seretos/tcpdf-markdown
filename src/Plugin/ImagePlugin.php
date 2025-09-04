<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class ImagePlugin extends AbstractPlugin
{
    public function parse(string $markdown, int $position): ?PluginTokenResult
    {
        $slice = substr($markdown, $position);
        if (preg_match('/^(?:\r?\n)*<img\s+([^>]+)\/?>(<\/img>)?/', $slice, $m)) {
            $paramStr = $m[1];
            $newPos = $position + strlen($m[0]);

            preg_match_all('/(\w+)\s*=\s*(["\'])(.*?)\2/', $paramStr, $matches, PREG_SET_ORDER);

            $result = [];
            foreach ($matches as $m) {
                $key = $m[1];
                $value = $m[3];
                $result[$key] = $value;
            }
            return new PluginTokenResult([
                new MarkdownToken('IMAGE', $result['src'] ?? '', false, $result)], $newPos);
        }

        if (preg_match('/^(?:\r?\n)*\!\[([^\]]+)\]\(([^)\s]+)(?:\s+["\']([^"\']+)["\'])?\)/', $slice, $m)) {
            $alt = $m[1];
            $src = $m[2];
            $newPos = $position + strlen($m[0]);

            return new PluginTokenResult([
                new MarkdownToken('IMAGE', $src, false, ['alt' => $alt])], $newPos);
        }
        return null;
    }

    public function supports(?MarkdownToken $token = null): bool
    {
        return $token === null || $token->getType() === 'CONTENT' || $token->getType() === 'LINK_CONTENT';
    }
}