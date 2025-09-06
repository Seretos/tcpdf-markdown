<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Tcpdf;

use seretos\TCPDFMarkdown\Tcpdf\Elements\TcpdfLine;
use seretos\TCPDFMarkdown\Tcpdf\Elements\TcpdfText;
use seretos\TCPDFMarkdown\Tcpdf\Elements\TcpdfTextFormat;
use seretos\TCPDFMarkdown\TransformNodeInterface;

class TcpdfCell implements TransformNodeInterface
{
    /**
     * @var TcpdfCell[]
     */
    protected array $children = [];

    public function __construct(
        protected Pdf $pdf,
        protected float $x,
        protected float $y,
        protected float $width,
        protected TcpdfStyle $style = new TcpdfStyle()
    )
    {
    }

    public function getX(): float {
        return $this->x + $this->style->getMarginLeft();
    }

    public function getY(): float {
        return $this->y + $this->style->getMarginTop();
    }

    public function addChild(TcpdfCell $child): static {
        if($this instanceof TcpdfTextFormat && $child instanceof TcpdfText) {
            $this->children[] = $child;
            return $this;
        }

        if($child instanceof TcpdfTextFormat) {
            foreach ($child->children as $grandChild) {
                $this->addChild($grandChild);
            }
            return $this;
        }

        if(!$this instanceof TcpdfLine && $child instanceof TcpdfText) {
            if(count($this->children) === 0 ||
                !$this->children[count($this->children)-1] instanceof TcpdfLine ||
                !$this->children[count($this->children)-1]->canAddText($child)) {
                $this->children[] = new TcpdfLine($this->pdf, $this->getX(), $this->getY() + $this->getHeight(), $this->getWidth(), $child->style);
            }
            $this->children[count($this->children)-1]->addChild($child);
            return $this;
        }

        $this->children[] = $child;
        return $this;
    }

    public function getHeight(): float {
        $height = 0;
        foreach ($this->children as $child) {
            $height += $child->getHeight() + $child->style->getMarginBottom();
        }
        return $height;
    }

    public function getWidth(): float {
        return $this->width - $this->style->getMarginRight() - $this->style->getMarginLeft();
    }

    public function render(): void {
        if($this->style->getBackgroundColor() !== null) {
            $this->pdf->rectangle(
                $this->x,
                $this->y,
                $this->getWidth() + $this->style->getMarginRight() + $this->style->getMarginLeft(),
                $this->getHeight() + $this->style->getMarginBottom(),
                $this->style->getRectangleStyle(),
                $this->style->getBackgroundColor(),
                $this->style->getRectangleBorderStyle()
            );
        }

        foreach ($this->children as $child) {
            $child->render();
        }
    }
}