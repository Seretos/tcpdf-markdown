<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Tcpdf;

use TCPDF;

class Pdf
{
    public function __construct(private readonly TCPDF $tcpdf)
    {
        $this->tcpdf->setCellPadding(.0);
    }

    public function getPageWidth(): float
    {
        return $this->tcpdf->getPageWidth();
    }

    public function getPageHeight(): float {
        return $this->tcpdf->getPageHeight();
    }

    public function getMarginLeft(): float
    {
        return $this->tcpdf->getMargins()['left'];
    }

    public function getMarginRight(): float
    {
        return $this->tcpdf->getMargins()['right'];
    }

    public function getMarginBottom(): float
    {
        return $this->tcpdf->getMargins()['bottom'];
    }

    public function getCellMarginLeft(): float {
        return $this->tcpdf->getCellMargins()['L'] ?? .0;
    }

    public function getCellMarginRight(): float {
        return $this->tcpdf->getCellMargins()['R'] ?? .0;
    }

    public function getCellPaddingLeft(): float {
        return $this->tcpdf->getCellPaddings()['L'] ?? .0;
    }

    public function getCellPaddingRight(): float {
        return $this->tcpdf->getCellPaddings()['R'] ?? .0;
    }

    public function write(string $s, float $height = .0, string $link = ''): void {
        $this->tcpdf->Write($height, $s, $link);
    }

    public function ln(): void {
        $this->tcpdf->Ln();
    }

    public function setFont(string $family, string $style = '', ?float $size = null): void {
        $this->tcpdf->setFont($family, $style, $size);
    }

    public function getStringHeight(string $s = 'J', string $family = 'helvetica', string $style = '', ?float $size = null): float {
        $this->tcpdf->setFont($family, $style, $size);
        return $this->tcpdf->getStringHeight($this->getStringWidth($s, $family, $style, $size)*2, $s);
    }

    public function getStringWidth(string $s, string $family = 'helvetica', string $style = '', ?float $size = null): float {
        $w = $this->tcpdf->GetStringWidth('A' . $s . 'A', $family, $style, $size);
        return $w - (2 * $this->tcpdf->GetStringWidth('A', $family, $style, $size));
    }

    public function getX(): float
    {
        return $this->tcpdf->GetX();
    }

    public function getY(): float {
        return $this->tcpdf->GetY();
    }

    public function setXY(float $x, float $y): static
    {
        $this->tcpdf->setXY($x, $y);
        return $this;
    }

    public function rectangle(float $x, float $y, float $width, float $height, string $style = '', ?array $backgroundColor = null, array $borderStyle = []): void {
        $this->tcpdf->Rect($x, $y, $width, $height, $style, $borderStyle, $backgroundColor ?? []);
    }

    public function setFontColor(?array $color): static {
        if($color !== null)
            $this->tcpdf->setTextColorArray($color);
        else
            $this->tcpdf->setTextColorArray([0,0,0]);
        return $this;
    }
}