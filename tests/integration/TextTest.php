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
use seretos\TCPDFMarkdown\Plugin\TextBlockPlugin;
use seretos\TCPDFMarkdown\Plugin\TextPlugin;
use seretos\TCPDFMarkdown\Plugin\WhitespacePlugin;
use seretos\TCPDFMarkdown\TokenParser;

class TextTest extends TestCase
{
    public static function textProvider(): array
    {
        return [
            // Basic text
            'single word' => ['hello', '<p>hello</p>'],
            'multiple words' => ['hello world', '<p>hello world</p>'],
            'leading/trailing spaces' => ['  hello  ', '<p>hello</p>'],

            // Formatting
            'bold' => ['**bold**', '<p><b>bold</b></p>'],
            'italic' => ['*italic*', '<p><i>italic</i></p>'],
            'bold italic' => ['***bold and italic***', '<p><b><i>bold and italic</i></b></p>'],
            'strikethrough' => ['~~strikethrough~~', '<p><s>strikethrough</s></p>'],
            'nested formatting' => ['**bold *italic* bold**', '<p><b>bold <i>italic</i> bold</b></p>'],
            'escaped bold' => ['\\*\\*not bold**', '<p>**not bold**</p>'],
            'escaped italic' => ['\\*not italic*', '<p>*not italic*</p>'],
            'escaped strikethrough' => ['\\~~not strike~~', '<p>~~not strike~~</p>'],

            // Links
            'simple link' => ['[test](https://google.de)', '<p><a href="https://google.de">test</a></p>'],
            'bold link' => ['[**test**](https://google.de)', '<p><a href="https://google.de"><b>test</b></a></p>'],
            'italic link' => ['[*test*](https://google.de)', '<p><a href="https://google.de"><i>test</i></a></p>'],
            'bold italic link' => ['[***test***](https://google.de)', '<p><a href="https://google.de"><b><i>test</i></b></a></p>'],
            'link with trailing text' => ['[test](https://google.de) here', '<p><a href="https://google.de">test</a> here</p>'],

            // References
            'reference link' => ["[link][id]\n[id]: https://example.com", '<p><a href="https://example.com">link</a></p>'],

            // Escaping
            'escaped mixed' => ['\\*\\*bold\\*\\* and \\*italic*', '<p>**bold** and *italic*</p>'],

            // Newlines
            'single newline' => ["hello\nworld", '<p>hello world</p>'],
            'multi newline' => ["hello\n\nworld", '<p>hello</p><p>world</p>'],
            'trailing newline' => ["hello\n", '<p>hello</p>'],
            'leading newline' => ["\nhello", '<p>hello</p>'],

            // Mixed content
            'text with bold and link' => ['This is **bold** and [link](https://google.de)', '<p>This is <b>bold</b> and <a href="https://google.de">link</a></p>'],
            'text with italic and strikethrough' => ['This is *italic* and ~~strike~~', '<p>This is <i>italic</i> and <s>strike</s></p>'],
            'text with multiple formats' => ['**Bold** *italic* ~~strike~~', '<p><b>Bold</b> <i>italic</i> <s>strike</s></p>'],
            'complex mixed' => ['Hello **bold [link](https://google.de)** world', '<p>Hello <b>bold <a href="https://google.de">link</a></b> world</p>'],

            // More complex escaping + formatting
            'escaped inside link' => ['[\\*\\*bold**](https://google.de)', '<p><a href="https://google.de">**bold**</a></p>'],
            'escaped bold inside italic' => ['*\\*\\*italic***', '<p><i>**italic**</i></p>'],
            'escaped bold and strikethrough' => ['\\*\\*bold** and \\~~strike~~', '<p>**bold** and ~~strike~~</p>'],
        ];
    }

    #[DataProvider('textProvider')]
    public function testHeadline1Variants(string $markdown, string $expectedHtml)
    {
        $lexer = new MarkdownLexer();
        $lexer->addPlugin(new TextBlockPlugin(1));

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