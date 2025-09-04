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
use seretos\TCPDFMarkdown\Plugin\ImagePlugin;
use seretos\TCPDFMarkdown\Plugin\LinkPlugin;
use seretos\TCPDFMarkdown\Plugin\ReferencePlugin;
use seretos\TCPDFMarkdown\Plugin\TextBlockPlugin;
use seretos\TCPDFMarkdown\Plugin\TextPlugin;
use seretos\TCPDFMarkdown\Plugin\WhitespacePlugin;
use seretos\TCPDFMarkdown\TokenParser;

class ImageTest extends TestCase
{
    public static function imageProvider(): array
    {
        return [
            'standard image' => [
                '![image test](./testimg)',
                '<p><img src="./testimg" /></p>'
            ],

            'linked image' => [
                '[![image test](./testimg)](https://www.google.de)',
                '<p><a href="https://www.google.de"><img src="./testimg" /></a></p>'
            ],

            'html image' => [
                '<img src="./testing" width="20"/>',
                '<p><img src="./testing" width="20" /></p>'
            ],

            'multiple images' => [
                '![img1](a.png) ![img2](b.png)',
                '<p><img src="a.png" /> <img src="b.png" /></p>'
            ],

            'reference style' => [
                "![ref][1]\n[1]: url_from_reference",
                '<p><img src="url_from_reference" /></p>' // assuming ReferencePlugin resolves [1] to url_from_reference
            ],
        ];
    }

    #[DataProvider('imageProvider')]
    public function testImageVariants(string $markdown, string $expectedHtml)
    {
        $lexer = new MarkdownLexer();
        $lexer->addPlugin(new TextBlockPlugin(1));
        $lexer->addPlugin(new LinkPlugin(2));
        $lexer->addPlugin(new ImagePlugin(3));
        $lexer->addPlugin(new ReferencePlugin(3));
        $lexer->addPlugin(new TextPlugin(4));
        $lexer->addPlugin(new WhitespacePlugin(5));

        $tokens = $lexer->parse($markdown);

        $parser = new TokenParser();
        $ast = $parser->parse($tokens);

        $transformer = new HtmlTransformer();
        $htmlNode = $transformer->transform($ast);

        $this->assertEquals($expectedHtml, implode($htmlNode->getChildren()));
    }
}