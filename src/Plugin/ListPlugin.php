<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class ListPlugin extends AbstractPlugin
{
    private string $listMatcher = '[*+-]{1}';
    private int $childSpaces = 2;
    public function __construct(int $priority, private readonly string $type = 'UNSORTED')
    {
        parent::__construct($priority);
        if($this->type === 'SORTED') {
            $this->listMatcher = '\d+\.';
            $this->childSpaces = 3;
        }
    }

    public function parse(string $markdown, int $position): ?PluginTokenResult
    {
        [$sol, $indentPos] = $this->lineStartAndIndent($markdown, $position);
        if ($position > $indentPos) {
            return null;
        }

        $slice = substr($markdown, $position) . "\n\n";
        if (preg_match('/^(?:[ \t]*\r?\n)*([ \t]*' . $this->listMatcher . '\[?[ x]?\]?[ \t]{1}\r?\n?(.{1,}\r?\n(?!\r\n))*)/', $slice, $m)) {
            $content = $m[1];
            $newPos  = $position + strlen($m[0]);
            $tokens = $this->findTokens($content);

            return new PluginTokenResult($tokens, $newPos);
        }

        return null;
    }

    private function findTokens(string $content): array {
        $elements = explode("\n",$content);
        for ($i=count($elements)-1;$i>=0;$i--) {
            if(!preg_match('/^[ \t]*' . $this->listMatcher . '.{1,}/', $elements[$i])) {
                $elements[$i-1] .= "\n" . $elements[$i];
                unset($elements[$i]);
            }
        }

        do {
            $merged = false;
            $elements = array_values($elements);
            for ($i = count($elements) - 1; $i > 0; $i--) {
                preg_match('/^([ \t]*' . $this->listMatcher . ').{1,}/', $elements[$i], $m1);
                preg_match('/^([ \t]*' . $this->listMatcher . ').{1,}/', $elements[$i - 1], $m2);
                if (strlen($m1[1]) - strlen($m2[1]) >= $this->childSpaces) {
                    $elements[$i - 1] .= "\n" . $elements[$i];
                    unset($elements[$i]);
                    $merged = true;
                }
            }
        }while($merged);

        $elements = array_values($elements);
        $tokens = [new MarkdownToken($this->type. '_LIST_START')];
        for ($i=0;$i<count($elements);$i++) {
            $tokens[] = new MarkdownToken($this->type . '_LIST_CONTENT', $elements[$i], true);
        }
        $tokens[] = new MarkdownToken($this->type . '_LIST_END');
        return $tokens;
    }

    public function supports(?MarkdownToken $token = null): bool
    {
        return $token === null || in_array($token->getType(), ['UNSORTED_LIST_ELEMENT_CONTENT', 'SORTED_LIST_ELEMENT_CONTENT']);
    }
}