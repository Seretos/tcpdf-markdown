<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown;

interface PluginInterface
{
    public function init(string $markdown): void;

    public function parse(string $markdown, int $position): ?PluginTokenResult;

    public function getPriority(): int;

    public function supports(?MarkdownToken $token = null): bool;
}