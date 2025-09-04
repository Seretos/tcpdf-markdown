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
use seretos\TCPDFMarkdown\Plugin\DividerPlugin;
use seretos\TCPDFMarkdown\Plugin\TextPlugin;
use seretos\TCPDFMarkdown\Plugin\WhitespacePlugin;
use seretos\TCPDFMarkdown\TokenParser;

class DividerTest extends TestCase
{
    public static function dividerProvider(): array
    {
        return [
            'divider1' => ['---', '<hr/>'],
            'divider2' => ['***', '<hr/>'],
            'divider3' => ['___', '<hr/>'],
            'no divider' => ['--- test', '--- test'],
            'divider with text' => ["___\ntest", '<hr/>test'],

            'long hyphens' => ['------', '<hr/>'],
            'long asterisks' => ['********', '<hr/>'],
            'long underscores' => ['________', '<hr/>'],

            'spaces hyphens' => ['- - -', '<hr/>'],
            'spaces asterisks' => ['* * *', '<hr/>'],
            'spaces underscores' => ['_ _ _', '<hr/>'],

            'leading space' => ['  ---', '<hr/>'],
            'trailing space' => ['***  ', '<hr/>'],

            'divider with trailing text' => ['--- test', '--- test'],
            'divider with leading text' => ['test ---', 'test ---'],

            'two hyphens' => ['--', '--'],
            'two asterisks' => ['**', '**'],
            'two underscores' => ['__', '__'],

            'mixed chars' => ['-*_', '-*_'],
        ];
    }

    #[DataProvider('dividerProvider')]
    public function testDividerVariants(string $markdown, string $expectedHtml)
    {
        $lexer = new MarkdownLexer();
        $lexer->addPlugin(new DividerPlugin(1));
        $lexer->addPlugin(new DividerPlugin(1,'*'));
        $lexer->addPlugin(new DividerPlugin(1,'_'));
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