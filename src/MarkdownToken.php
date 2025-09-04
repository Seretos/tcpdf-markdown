<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown;

class MarkdownToken
{
    public function __construct(
        private readonly string $type,
        private readonly mixed  $content = null,
        private readonly bool   $parseable = false,
        private readonly array  $metadata = [])
    {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getContent(): mixed
    {
        return $this->content;
    }

    public function isParseable(): bool
    {
        return $this->parseable;
    }

    public function getMetaData(): array {
        return $this->metadata;
    }
}