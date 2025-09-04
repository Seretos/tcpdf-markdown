<?php

use seretos\TCPDFMarkdown\Factory;

require __DIR__ . '/../vendor/autoload.php';

// generate tokens from markdown text
$lexer = Factory::createLexer();
$tokens = $lexer->parse(file_get_contents(__DIR__ . '/example_01.md'));

// generate AST Tree from tokens
$tokenParser = Factory::createTokenParser();
$node = $tokenParser->parse($tokens);

// generate HTML from AST Tree
$htmlTransformer = Factory::createHtmlTransformer();
$htmlNode = $htmlTransformer->transform($node);
file_put_contents(__DIR__.'/example_01.html', (string)$htmlNode);

// generate Pdf from AST Tree
$tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$tcpdf->setPrintFooter(false);
$tcpdf->setPrintHeader(false);
$tcpdf->addPage();
$tcpdfTransformer = Factory::createTcpdfTransformer($tcpdf);
$tcpdfNode = $tcpdfTransformer->transform($node);
$tcpdfNode->draw();
//while(!$tcpdfNode->draw()) {
//    $tcpdf->addPage();
//}
$tcpdf->output(__DIR__.'/example_01.pdf','F');