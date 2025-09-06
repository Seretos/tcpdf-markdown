<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Tcpdf\Elements;

use seretos\TCPDFMarkdown\Tcpdf\TcpdfCell;

class TcpdfLine extends TcpdfCell
{
    /*public function __construct(Pdf $pdf, float $x, float $y, float $width, TcpdfStyle $style = new TcpdfStyle())
    {
        parent::__construct($pdf, $x, $y, $width, $style);
    }*/

    private float $_height = .0;
    private float $_width = .0;

    public function canAddText(TcpdfText $text): bool {
        $currentWidth = $this->getWidth();
        $textWidth = $text->getWidth();
        if($currentWidth + $textWidth <= $this->width)
            return true;
        return false;
    }

    public function getWidth(): float
    {
        return $this->_width;
    }

    public function getHeight(): float {
        return $this->_height;
    }

    public function addChild(TcpdfCell $child): static
    {
        if(!$child instanceof TcpdfText)
            return $this;
        if($this->style->isTrimLines() && count($this->children) === 0 && trim($child->getText()) === '')
            return $this;
        $child->x = $this->x + $this->getWidth();
        $child->y = $this->y;
        if($child->getHeight() > $this->_height)
            $this->_height = $child->getHeight();
        $this->_width += $child->getWidth();
        return parent::addChild($child);
    }
}