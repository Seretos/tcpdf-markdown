<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class ReferencePlugin extends AbstractPlugin
{
    private array $references = [];

    public function init(string $markdown): void
    {
        $pattern = '/^\[(?P<id>[^\]]+)\]:\s+(?P<url>\S+)(?:\s+(?:"(?P<title1>[^"]*)"|\'(?P<title2>[^\']*)\'))?\s*$/m';
        preg_match_all($pattern, $markdown, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $this->references[$match['id']] = [
                'url' => $match['url'],
                'title' => array_key_exists('title1', $match) ? $match['title1'] : ($match['title2'] ?? null),
            ];
        }
    }

    public function parse(string $markdown, int $position): ?PluginTokenResult
    {
        $slice = substr($markdown, $position);
        if (preg_match('/^(?:[ \t]*\r?\n)*\[(?P<id>[^\]]+)\]:\s+(?P<url>\S+)(?:\s+(?:"(?P<title1>[^"]*)"|\'(?P<title2>[^\']*)\'))?\s*(?:\r?\n)?/s', $slice, $m)) {
            $newPos = $position + strlen($m[0]);

            return new PluginTokenResult([],$newPos);
        }

        if (preg_match('/^(?:[ \t]*\r?\n)*\[(?P<text>[^\]]+)\]\[(?P<id>[^\]]+)\]/', $slice, $m)) {
            $newPos = $position + strlen($m[0]);

            if(!array_key_exists($m['id'], $this->references))
                return null;

            return new PluginTokenResult([
                new MarkdownToken('LINK_START', $this->references[$m['id']]['url']),
                new MarkdownToken('LINK_CONTENT', $m['text'], true),
                new MarkdownToken('LINK_END', $this->references[$m['id']]['url'])
            ], $newPos);
        }

        if (preg_match('/^(?:[ \t]*\r?\n)*\!\[(?P<text>[^\]]+)\]\[(?P<id>[^\]]+)\]/', $slice, $m)) {
            $newPos = $position + strlen($m[0]);

            if(!array_key_exists($m['id'], $this->references))
                return null;

            return new PluginTokenResult([
                new MarkdownToken('IMAGE', $this->references[$m['id']]['url'])
            ], $newPos);
        }
        return null;
    }
}