<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Tcpdf;

use seretos\TCPDFMarkdown\Node;
use seretos\TCPDFMarkdown\NodeTransformerInterface;
use seretos\TCPDFMarkdown\Tcpdf\Elements\TcpdfText;
use seretos\TCPDFMarkdown\Tcpdf\Elements\TcpdfTextFormat;

class TcpdfTransformer implements NodeTransformerInterface
{
    public function __construct(private readonly Pdf $pdf)
    {
    }

    protected function createTransformStyle(Node $node, ?TcpdfStyle $parentStyle = null): TcpdfStyle
    {
        $parent = $parentStyle ?? new TcpdfStyle();
        $default = clone $parent;
        $default->setMargin(0,0,0,0);
        switch ($node->getType()) {
            case 'DOCUMENT':
                $default->setBackgroundColor('#222222')->setTrimLines()->setForegroundColor('#FFFFFF');
                break;
            case 'HEADER':
                $default->setSize($default->getSize() + 6 - $node->getValue())->setBold();
                $default->setMargin(2,2,2,3);
                break;
            case 'BOLD':
                $default->setBold();
                break;
            case 'ITALIC':
                $default->setItalic();
                break;
            case 'STRIKETHROUGH':
                $default->setStrikethrough();
                break;
            case 'BLOCK':
                $default->setMargin(2,2,2,3);
                break;
            case 'LINK':
                $default->setUnderline()->setForegroundColor('#0000FF')->setLink($node->getValue());
                break;
            default:
                $default->setBackgroundColor(null);
        }
        return $default;
    }

    public function transform(Node $node, float $x = .0, float $y = .0, float $width = .0, ?TcpdfStyle $parentStyle = null): TcpdfCell
    {
        if ($width === .0)
            $width = $this->pdf->getPageWidth() - $this->pdf->getMarginLeft() - $this->pdf->getMarginRight();

        $resultStyle = $this->createTransformStyle($node, $parentStyle);
        switch ($node->getType()) {
            case 'DOCUMENT':
            case 'BLOCK':
            case 'HEADER':
                $result = new TcpdfCell($this->pdf, $x, $y, $width, $resultStyle);
                foreach ($node->getChildren() as $child) {
                    $result->addChild($this->transform($child, $result->getX(), $result->getY() + $result->getHeight(), $width, $resultStyle));
                }
                return $result;

            case 'BOLD':
            case 'ITALIC':
            case 'STRIKETHROUGH':
            case 'LINK':
                $result = new TcpdfTextFormat($this->pdf, $x, $y, $width, $resultStyle);
                foreach ($node->getChildren() as $child) {
                    $result->addChild($this->transform($child, $result->getX(), $result->getY() + $result->getHeight(), $width, $resultStyle));
                }
                return $result;

            case 'TEXT':
            case 'WHITESPACE':
            case 'CHAR':
                return new TcpdfText($this->pdf, $node->getValue(), $x, $y, $width, $resultStyle);

            default:
                $tmp = $node;
                break;
        }
    }
}