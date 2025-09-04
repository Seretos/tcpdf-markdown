<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown;

class Node
{
    /**
     * @var Node[]
     */
    private array $children = [];

    public function __construct(
        private readonly string $type,
        private readonly mixed $value = null,
        private readonly array $metadata = []
    )
    {
    }

    public function addChild(Node $child): void {
        $this->children[] = $child;
    }

    /**
     * @return Node[]
     */
    public function getChildren(): array {
        return $this->children;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getValue(): mixed {
        return $this->value;
    }

    public function getMetaData(): array {
        return $this->metadata;
    }
}