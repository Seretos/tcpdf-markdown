<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class ListElementPlugin extends AbstractPlugin
{
    private string $listMatcher = '[*+-]{1}';

    public function __construct(int $priority, private readonly string $type = 'UNSORTED')
    {
        parent::__construct($priority);
        if($this->type === 'SORTED')
            $this->listMatcher = '\d+\.';
    }

    public function parse(string $markdown, int $position): ?PluginTokenResult
    {
        if(!preg_match('/^([ \t]*(' . $this->listMatcher . ')).{1,}/', $markdown, $m))
            return null;

        $content = trim(substr($markdown, strlen($m[1]),strlen($markdown)));
        return new PluginTokenResult([
            new MarkdownToken($this->type . '_LIST_ELEMENT_START',$m[2]),
            new MarkdownToken($this->type . '_LIST_ELEMENT_CONTENT', trim($content), true),
            new MarkdownToken($this->type . '_LIST_ELEMENT_END')
        ], strlen($markdown));
    }

    public function supports(?MarkdownToken $token = null): bool
    {
        return $token !== null && $token->getType() === $this->type . '_LIST_CONTENT';
    }
}