<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace integration;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\Html\HtmlTransformer;
use seretos\TCPDFMarkdown\MarkdownLexer;
use seretos\TCPDFMarkdown\Plugin\CheckboxPlugin;
use seretos\TCPDFMarkdown\Plugin\FontFormatPlugin;
use seretos\TCPDFMarkdown\Plugin\LinkPlugin;
use seretos\TCPDFMarkdown\Plugin\ListElementPlugin;
use seretos\TCPDFMarkdown\Plugin\ListPlugin;
use seretos\TCPDFMarkdown\Plugin\NewlinePlugin;
use seretos\TCPDFMarkdown\Plugin\ReferencePlugin;
use seretos\TCPDFMarkdown\Plugin\TextPlugin;
use seretos\TCPDFMarkdown\Plugin\WhitespacePlugin;
use seretos\TCPDFMarkdown\TokenParser;

class ListTest extends TestCase
{
    public static function listProvider(): array {
        return [
            // Unsorted basic list
            'unsorted' => [
                "* element 1\n  * element 1.1\n* element 2",
                '<ul><li>element 1 <ul><li>element 1.1</li></ul></li><li>element 2</li></ul>'
            ],

            // Sorted basic list
            'sorted' => [
                "1. element 1\n   1. element 1.1\n2. element 2",
                '<ol><li>element 1 <ol><li>element 1.1</li></ol></li><li>element 2</li></ol>'
            ],

            // checkbox list
            'sorted' => [
                "1. [ ] unchecked element 1\n   1. [x] checked element\n2. [x] checked element",
                '<ol><li><input type="checkbox" /> unchecked element 1 <ol><li><input type="checkbox" checked/> checked element</li></ol></li><li><input type="checkbox" checked/> checked element</li></ol>'
            ],

            // Unsorted list with bold, italic, strikethrough
            'unsorted formatting' => [
                "* **bold**\n* *italic*\n* ~~strike~~",
                '<ul><li><b>bold</b></li><li><i>italic</i></li><li><s>strike</s></li></ul>'
            ],

            // Sorted list with links
            'sorted links' => [
                "1. [Google](https://google.com)\n2. [PHP](https://php.net)",
                '<ol><li><a href="https://google.com">Google</a></li><li><a href="https://php.net">PHP</a></li></ol>'
            ],

            // Nested mixed lists
            'nested mixed' => [
                "* element 1\n  1. sub1\n  2. sub2\n* element 2",
                '<ul><li>element 1 <ol><li>sub1</li><li>sub2</li></ol></li><li>element 2</li></ul>'
            ],

            // List with spaces and empty items
            'spaces & empty' => [
                "* item 1\n*  \n* item 3",
                '<ul><li>item 1</li><li></li><li>item 3</li></ul>'
            ],

            // Sorted list with different indentation
            'sorted indentation' => [
                "1. item 1\n    1. item 1.1\n        1. item 1.1.1\n2. item 2",
                '<ol><li>item 1 <ol><li>item 1.1 <ol><li>item 1.1.1</li></ol></li></ol></li><li>item 2</li></ol>'
            ],

            // Mixed formatting in nested lists
            'nested formatting' => [
                "* **bold**\n  * *italic*\n  * ~~strike~~",
                '<ul><li><b>bold</b> <ul><li><i>italic</i></li><li><s>strike</s></li></ul></li></ul>'
            ],
        ];
    }

    #[DataProvider('listProvider')]
    public function testListVariants(string $markdown, string $expectedHtml)
    {
        $lexer = new MarkdownLexer();
        $lexer->addPlugin(new ListPlugin(1));
        $lexer->addPlugin(new ListElementPlugin(2));

        $lexer->addPlugin(new ListPlugin(1, 'SORTED'));
        $lexer->addPlugin(new ListElementPlugin(2, 'SORTED'));

        $lexer->addPlugin(new CheckboxPlugin(3));

        $lexer->addPlugin(new ReferencePlugin(2));
        $lexer->addPlugin(new LinkPlugin(3));

        $lexer->addPlugin(new FontFormatPlugin(4));

        $lexer->addPlugin(new TextPlugin(7));
        $lexer->addPlugin(new NewlinePlugin(8));
        $lexer->addPlugin(new WhitespacePlugin(9));

        $tokens = $lexer->parse($markdown);

        $parser = new TokenParser();
        $ast = $parser->parse($tokens);

        $transformer = new HtmlTransformer();
        $htmlNode = $transformer->transform($ast);

        $this->assertEquals($expectedHtml, trim(implode($htmlNode->getChildren())));
    }
}