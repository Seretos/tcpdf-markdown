<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Tcpdf\Elements;

use seretos\TCPDFMarkdown\Tcpdf\Pdf;
use seretos\TCPDFMarkdown\Tcpdf\TcpdfCell;
use seretos\TCPDFMarkdown\Tcpdf\TcpdfStyle;

class TcpdfText extends TcpdfCell
{
    private float $_width = .0;
    private float $_height = .0;

    public function __construct(Pdf $pdf, protected string $text, float $x, float $y, float $width, TcpdfStyle $style = new TcpdfStyle())
    {
        parent::__construct($pdf, $x, $y, $width, $style);
    }

    public function getHeight(): float
    {
        if($this->_height === .0)
            $this->_height = $this->pdf->getStringHeight($this->text, $this->style->getFamily(), $this->style->getFontStyle(), $this->style->getSize());
        return $this->_height;
    }

    public function getWidth(): float
    {
        if($this->_width === .0)
            $this->_width = $this->pdf->getStringWidth($this->text, $this->style->getFamily(), $this->style->getFontStyle(), $this->style->getSize());
        return $this->_width;
    }

    public function getText(): string {
        return $this->text;
    }

    public function render(): void
    {
        parent::render();
        $this->pdf->setXY($this->x, $this->y);
        $this->pdf->setFont($this->style->getFamily(), $this->style->getFontStyle(), $this->style->getSize());
        $this->pdf->setFontColor($this->style->getForegroundColor());
        $this->pdf->write($this->text, $this->getHeight(), $this->style->getLink());
    }
}