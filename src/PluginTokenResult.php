<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown;

class PluginTokenResult
{
    /**
     * @param MarkdownToken[] $tokens
     * @param int $position
     */
    public function __construct(
        private readonly array $tokens,
        private readonly int $position
    )
    {
    }

    /**
     * @return MarkdownToken[]
     */
    public function getTokens(): array {
        return $this->tokens;
    }

    public function getPosition(): int {
        return $this->position;
    }
}