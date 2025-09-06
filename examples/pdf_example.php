<?php

use seretos\TCPDFMarkdown\Factory;
use seretos\TCPDFMarkdown\Tcpdf\TcpdfTransformer;

require __DIR__ . '/../vendor/autoload.php';

// generate tokens from markdown text
$lexer = Factory::createLexer();
$tokens = $lexer->parse(file_get_contents(__DIR__ . '/example_02.md'));

// generate AST Tree from tokens
$tokenParser = Factory::createTokenParser();
$node = $tokenParser->parse($tokens);

// generate Pdf from AST Tree
$tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$tcpdf->setPrintFooter(false);
$tcpdf->setPrintHeader(false);
$tcpdf->addPage();

$pdf = new \seretos\TCPDFMarkdown\Tcpdf\Pdf($tcpdf);
$tcpdfTransformer = new TcpdfTransformer($pdf);
$tcpdfNode = $tcpdfTransformer->transform($node,
    $pdf->getX(),
    $pdf->getY(),
    $pdf->getPageWidth() - $pdf->getMarginRight() - $pdf->getX()
);

$tcpdfNode->render();

$tcpdf->output(__DIR__.'/example_01.pdf','F');