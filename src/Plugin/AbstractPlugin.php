<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginInterface;

abstract class AbstractPlugin implements PluginInterface
{
    public function __construct(
        private readonly int $priority
    )
    {
    }

    public function init(string $markdown): void {

    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function supports(?MarkdownToken $token = null): bool
    {
        return true;
    }

    protected function lineStartAndIndent(string $text, int $pos): array
    {
        $len = strlen($text);
        $i = $pos - 1;
        while ($i >= 0) {
            $ch = $text[$i];
            if ($ch === "\n" || $ch === "\r") {
                $i++;
                break;
            }
            $i--;
        }
        $sol = max(0, $i);

        $j = $sol;
        while ($j < $len) {
            $ch = $text[$j];
            if ($ch !== ' ' && $ch !== "\t") {
                break;
            }
            $j++;
        }

        return [$sol, $j];
    }
}