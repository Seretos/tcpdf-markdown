<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class PHPCodePlugin extends AbstractPlugin
{
    public function parse(string $markdown, int $position): ?PluginTokenResult
    {
        $substr = substr($markdown, $position);
        if (preg_match('/^(?!\s)(\$?\w+|\'[^\']*\'|"[^"]*"|[^\s\w])/', $substr, $matches)) {
            $word = $matches[1];
            $newPos = $position + strlen($word);
            $tokens = [];
            if (in_array($word, [
                'public',
                'private',
                'protected',
                'static',
                'class',
                'function',
                'implements',
                'extends',
                'use',
                'new',
                'return',
                'print',
                'const',
                'void',
                'null',
                'bool',
                'boolean',
                'int',
                'integer',
                'float',
                'array',
                'string'])) {
                $tokens = [new MarkdownToken('CODE_KEYWORD', $word)];
            } else if ((str_starts_with($word, '\'') && str_ends_with($word, '\'')) ||
                (str_starts_with($word, '"') && str_ends_with($word, '"')))
                $tokens = [
                    new MarkdownToken('CODE_STRING_START', substr($word, 0, 1)),
                    new MarkdownToken('CONTENT', $word, true),
                    new MarkdownToken('CODE_STRING_END', substr($word, 0, 1))
                ];
            else if (str_starts_with($word, '$'))
                $tokens = [new MarkdownToken('CODE_VARIABLE', $word)];
            else
                $tokens = [new MarkdownToken('TEXT', $word)];

            if (count($tokens) > 0)
                return new PluginTokenResult($tokens, $newPos);
        }
        return null;
    }

    public function supports(?MarkdownToken $token = null): bool
    {
        if ($token === null)
            return false;
        if ($token->getType() !== 'CODE_CONTENT')
            return false;
        if (isset($token->getMetaData()['type']) && $token->getMetaData()['type'] === 'php')
            return true;
        return false;
    }
}