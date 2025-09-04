<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class FontFormatPlugin extends AbstractPlugin
{
    protected array $strongRegex = array(
        '*' => '/^[*]{2}((?:\\\\\*|[^*]|[*][^*]*+[*])+?)[*]{2}(?![*])/s',
        '_' => '/^__((?:\\\\_|[^_]|_[^_]*+_)+?)__(?!_)/us',
        '~' => '/^~~((?:\\\\~|[^~]|~[^~]*+~)+?)~~(?!~)/us'
    );

    protected array $emRegex = array(
        '*' => '/^[*]((?:\\\\\*|[^\\\\]|[^*]|[*][*][^*]+?[*][*])+?)[*](?![*])/s',
        '_' => '/^_((?:\\\\_|[^_]|__[^_]*__)+?)_(?!_)\b/us',
    );

    public function parse(string $markdown, int $position, ?string $stopToken = null): ?PluginTokenResult
    {
        $slice = substr($markdown, $position);
        preg_match('/^[^?\r\n]*/', $slice, $m);
        $line = $m[0];

        if(!isset($line[1]))
            return null;
        $marker = $line[0];

        if(preg_match('/^(\\\\\*|\\\\_|\\\\~)/s', $line, $matches)) {
            return new PluginTokenResult([
                new MarkdownToken('TEXT', substr($matches[0],1,strlen($matches[0])))
            ], $position + strlen($matches[0]));
        }
        if(!in_array($marker, ['*','_','~']))
            return null;

        $tokenType = null;
        if($line[1] === $marker && preg_match($this->strongRegex[$marker], $line, $matches)) {
            if($marker === '*')
                $tokenType = 'BOLD';
            else
                $tokenType = 'STRIKETHROUGH';
        }
        else if (in_array($marker, ['*','_']) && preg_match($this->emRegex[$marker], $line, $matches)) {
            $tokenType = 'ITALIC';
        }

        if($tokenType)
            return new PluginTokenResult([
                new MarkdownToken($tokenType . '_START'),
                new MarkdownToken('CONTENT', $matches[1], true),
                new MarkdownToken($tokenType . '_END')
            ], $position + strlen($matches[0]));
        return null;
    }
}