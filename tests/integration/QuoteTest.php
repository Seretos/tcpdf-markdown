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
use seretos\TCPDFMarkdown\Plugin\CodePlugin;
use seretos\TCPDFMarkdown\Plugin\FontFormatPlugin;
use seretos\TCPDFMarkdown\Plugin\GreaterQuotePlugin;
use seretos\TCPDFMarkdown\Plugin\InlineCodePlugin;
use seretos\TCPDFMarkdown\Plugin\ListElementPlugin;
use seretos\TCPDFMarkdown\Plugin\ListPlugin;
use seretos\TCPDFMarkdown\Plugin\NewlinePlugin;
use seretos\TCPDFMarkdown\Plugin\PHPCodePlugin;
use seretos\TCPDFMarkdown\Plugin\TabQuotePlugin;
use seretos\TCPDFMarkdown\Plugin\TextPlugin;
use seretos\TCPDFMarkdown\Plugin\WhitespacePlugin;
use seretos\TCPDFMarkdown\TokenParser;

class QuoteTest extends TestCase
{
    public static function quoteProvider(): array
    {
        return [
            'quote1' => ['    test', '<div class="quote">test</div>'],
            'quote2' => ['> test','<div class="quote">test</div>'],
            'quote3' => ["> test\n > > test", '<div class="quote">test<br/><div class="quote">test</div></div>'],
            'quote4' => ["```\ntest\n```", '<div class="quote">test<br/></div>'],
            'php' => [
                "```php\nprint \"hello world\";\n```",
                '<div class="quote"><span class="code_keyword">print</span> <span class="code_string">"hello world"</span>;<br/></div>'
            ],

            'quote with leading space' => ['   > test', '<div class="quote">test</div>'],

            'multiline quote' => ["> line1\n> line2", '<div class="quote">line1<br/>line2</div>'],

            'quote with formatting' => ["> *italic* and **bold**", '<div class="quote"><i>italic</i> and <b>bold</b></div>'],

            'empty quote line' => ["> line1\n>\n> line2", '<div class="quote">line1<br/><br/>line2</div>'],

            'quote with inline code' => ["> here is `code`", '<div class="quote">here is <span class="inline_code">code</span></div>'],

            'deep nested quote' => ["> a\n>> b\n>>> c", '<div class="quote">a<br/><div class="quote">b<br/><div class="quote">c</div></div></div>'],

            'codeblock inside quote' => [
                "> ```\n> test\n> ```",
                '<div class="quote"><div class="quote">test<br/></div></div>'
            ],

            'text then quote' => [
                "before\n> inside",
                'before <div class="quote">inside</div>'
            ],
            'quote inside list' => [
                "* item1\n  > quoted\n* item2",
                '<ul><li>item1 <div class="quote">quoted</div></li><li>item2</li></ul>'
            ],
        ];
    }

    #[DataProvider('quoteProvider')]
    public function testQuoteVariants(string $markdown, string $expectedHtml)
    {
        $lexer = new MarkdownLexer();
        $lexer->addPlugin(new TabQuotePlugin(1));
        $lexer->addPlugin(new GreaterQuotePlugin(1));
        $lexer->addPlugin(new CodePlugin(1));
        $lexer->addPlugin(new InlineCodePlugin(1));
        $lexer->addPlugin(new PHPCodePlugin(1));

        $lexer->addPlugin(new ListPlugin(1));
        $lexer->addPlugin(new ListElementPlugin(2));

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