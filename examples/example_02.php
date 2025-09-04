<?php

use seretos\TCPDFMarkdown\Factory;

require __DIR__ . '/../vendor/autoload.php';

// generate tokens from markdown text
$lexer = Factory::createLexer();
$tokens = $lexer->parse('At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Test');

// generate AST Tree from tokens
$tokenParser = Factory::createTokenParser();
$node = $tokenParser->parse($tokens);

// generate Pdf from AST Tree
$tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$tcpdf->setPrintFooter(false);
$tcpdf->setPrintHeader(false);
$tcpdf->addPage();
$tcpdf->setXY(10,267.97000);
$tcpdfTransformer = Factory::createTcpdfTransformer($tcpdf);
$tcpdfNode = $tcpdfTransformer->transform($node);

//$tcpdfTransformer->createDelta($tcpdfNode);
while($tcpdfNode->draw() === false) {
    $tcpdf->addPage();
}

$tcpdf->output(__DIR__.'/example_02.pdf','F');