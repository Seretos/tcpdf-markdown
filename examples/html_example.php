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