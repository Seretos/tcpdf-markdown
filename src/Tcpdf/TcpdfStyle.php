<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Tcpdf;

use Exception;

class TcpdfStyle
{
    private ?array $backgroundColor = null;
    private ?array $foregroundColor = null;
    private bool $trimLines = false;
    private float $marginLeft = .0;
    private float $marginRight = .0;
    private float $marginBottom = .0;
    private float $marginTop = .0;
    private bool $strikethrough = false;
    private bool $unserline = false;
    private string $link = '';

    public function __construct(
        private readonly string $family = 'helvetica',
        private float $size = 9,
        private bool $bold = false,
        private bool $italic = false)
    {
    }

    public function setSize(float $size = 9): static {
        $this->size = $size;
        return $this;
    }

    public function setMargin(float $left = .0, float $right = .0, float $top = .0, float $bottom = .0): static {
        $this->marginLeft = $left;
        $this->marginRight = $right;
        $this->marginTop = $top;
        $this->marginBottom = $bottom;
        return $this;
    }

    public function getMarginLeft(): float {
        return $this->marginLeft;
    }

    public function getMarginRight(): float {
        return $this->marginRight;
    }

    public function getMarginTop(): float {
        return $this->marginTop;
    }

    public function getMarginBottom(): float {
        return $this->marginBottom;
    }

    public function setTrimLines(bool $trim = true): static {
        $this->trimLines = $trim;
        return $this;
    }

    public function isTrimLines(): bool {
        return $this->trimLines;
    }

    public function getFamily(): string {
        return $this->family;
    }

    public function getSize(): float {
        return $this->size;
    }

    public function getFontStyle(): string {
        $style = '';
        if($this->bold)
            $style .= 'B';
        if($this->italic)
            $style .= 'I';
        if($this->strikethrough)
            $style .= 'D';
        if($this->unserline)
            $style .= 'U';
        return $style;
    }

    public function setBold(bool $bold = true): static {
        $this->bold = $bold;
        return $this;
    }

    public function setItalic(bool $italic = true): static {
        $this->italic = $italic;
        return $this;
    }

    public function setUnderline(bool $underline = true): static {
        $this->unserline = $underline;
        return $this;
    }

    public function getBackgroundColor(): ?array {
        return $this->backgroundColor;
    }

    public function getForegroundColor(): ?array {
        return $this->foregroundColor;
    }

    public function getRectangleStyle(): string {
        $style = '';
        if($this->backgroundColor !== null)
            $style .= 'F';
        return $style;
    }

    public function getRectangleBorderStyle(): array {
        return [];
        //return ['all' => ['width' => 1,'color' => $this->hex2rgb('#000000'),'cap' => 'butt', 'join' => 'miter', 'dash' => 0]];
    }

    /**
     * @throws Exception
     */
    public function setBackgroundColor(?string $color): static {
        if($color !== null)
            $this->backgroundColor = $this->hex2rgb($color);
        else
            $this->backgroundColor = null;
        return $this;
    }

    public function setForegroundColor(?string $color): static {
        if($color !== null)
            $this->foregroundColor = $this->hex2rgb($color);
        else
            $this->foregroundColor = null;
        return $this;
    }

    /**
     * @throws Exception
     */
    private function hex2rgb($hex): array
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) == 3) {
            $r = hexdec(str_repeat($hex[0], 2));
            $g = hexdec(str_repeat($hex[1], 2));
            $b = hexdec(str_repeat($hex[2], 2));
        } else if (strlen($hex) == 6) {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        } else {
            throw new Exception("invalid color: $hex");
        }

        return [$r, $g, $b];
    }

    public function setStrikethrough(bool $strikethrough = true): static
    {
        $this->strikethrough = $strikethrough;
        return $this;
    }

    public function setLink(string $link = ''): static {
        $this->link = $link;
        return $this;
    }

    public function getLink(): string {
        return $this->link;
    }
}