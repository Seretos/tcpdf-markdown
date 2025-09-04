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
use seretos\TCPDFMarkdown\Plugin\FontFormatPlugin;
use seretos\TCPDFMarkdown\Plugin\LinkPlugin;
use seretos\TCPDFMarkdown\Plugin\NewlinePlugin;
use seretos\TCPDFMarkdown\Plugin\ReferencePlugin;
use seretos\TCPDFMarkdown\Plugin\TablePlugin;
use seretos\TCPDFMarkdown\Plugin\TextPlugin;
use seretos\TCPDFMarkdown\Plugin\WhitespacePlugin;
use seretos\TCPDFMarkdown\TokenParser;

class TableTest extends TestCase
{
    public static function tableProvider(): array
    {
        return [
            // Basic table, left-aligned by default
            'single word' => [
                "|header1|header2|\n|-|-|\n|column1|column2|",
                '<table><tr><th align="left">header1</th><th align="left">header2</th></tr><tr><td align="left">column1</td><td align="left">column2</td></tr></table>'
            ],

            // Table with center and right alignment
            'alignment' => [
                "|left|center|right|\n|:--|:--:|--:|\n|a|b|c|",
                '<table><tr><th align="left">left</th><th align="center">center</th><th align="right">right</th></tr><tr><td align="left">a</td><td align="center">b</td><td align="right">c</td></tr></table>'
            ],

            // Table with bold, italic, and strikethrough
            'formatting' => [
                "|**bold**|*italic*|~~strike~~|\n|-|-|-|\n|1|2|3|",
                '<table><tr><th align="left"><b>bold</b></th><th align="left"><i>italic</i></th><th align="left"><s>strike</s></th></tr><tr><td align="left">1</td><td align="left">2</td><td align="left">3</td></tr></table>'
            ],

            // Table with links
            'links' => [
                "|[Google](https://google.com)|[PHP](https://php.net)|\n|-|-|\n|Link1|Link2|",
                '<table><tr><th align="left"><a href="https://google.com">Google</a></th><th align="left"><a href="https://php.net">PHP</a></th></tr><tr><td align="left">Link1</td><td align="left">Link2</td></tr></table>'
            ],

            // Table with spaces around cells
            'spaces' => [
                "| header1 | header2 |\n| - | - |\n| col1 | col2 |",
                '<table><tr><th align="left">header1</th><th align="left">header2</th></tr><tr><td align="left">col1</td><td align="left">col2</td></tr></table>'
            ],

            // Table with mixed formatting in cells
            'mixed formatting' => [
                "|**bold** text|*italic* text|~~strike~~ text|\n|-|-|-|\n|A|B|C|",
                '<table><tr><th align="left"><b>bold</b> text</th><th align="left"><i>italic</i> text</th><th align="left"><s>strike</s> text</th></tr><tr><td align="left">A</td><td align="left">B</td><td align="left">C</td></tr></table>'
            ],

            // Table with special characters and empty cells
            'special chars & empty' => [
                "| col1 | col2 |\n| - | - |\n| !@#$% |  |",
                '<table><tr><th align="left">col1</th><th align="left">col2</th></tr><tr><td align="left">!@#$%</td><td align="left"></td></tr></table>'
            ],
        ];
    }

    #[DataProvider('tableProvider')]
    public function testTableVariants(string $markdown, string $expectedHtml)
    {
        $lexer = new MarkdownLexer();
        $lexer->addPlugin(new TablePlugin(1));

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