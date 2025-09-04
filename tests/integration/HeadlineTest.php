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
use seretos\TCPDFMarkdown\Plugin\HeaderPlugin;
use seretos\TCPDFMarkdown\Plugin\SeTextPlugin;
use seretos\TCPDFMarkdown\Plugin\TextPlugin;
use seretos\TCPDFMarkdown\Plugin\WhitespacePlugin;
use seretos\TCPDFMarkdown\TokenParser;

class HeadlineTest extends TestCase
{
    public static function headlineProvider(): array
    {
        return [
            // H1
            'H1 standard' => ['# Headline 1', '<h1>Headline 1</h1>'],
            'H1 no space after #' => ['#Headline 1', '#Headline 1'],
            'H1 leading spaces' => ['   # Headline 1', '<h1>Headline 1</h1>'],
            'H1 trailing spaces' => ['# Headline 1   ', '<h1>Headline 1</h1>'],
            'H1 multiple spaces after #' => ['#    Headline 1', '<h1>Headline 1</h1>'],
            'H1 hash at end' => ['# Headline 1 # extra', '<h1>Headline 1 # extra</h1>'],
            'H1 setext underline' => ["Headline 1\n======", '<h1>Headline 1</h1>'],
            'H1 setext underline with trailing space' => ["Headline 1\n======   ", '<h1>Headline 1</h1>'],

            // H2
            'H2 standard' => ['## Headline 2', '<h2>Headline 2</h2>'],
            'H2 no space after ##' => ['##Headline 2', '##Headline 2'],
            'H2 leading spaces' => ['   ## Headline 2', '<h2>Headline 2</h2>'],
            'H2 trailing spaces' => ['## Headline 2   ', '<h2>Headline 2</h2>'],
            'H2 multiple spaces after ##' => ['##    Headline 2', '<h2>Headline 2</h2>'],
            'H2 hash at end' => ['## Headline 2 ## extra', '<h2>Headline 2 ## extra</h2>'],
            'H2 setext underline' => ["Headline 2\n------", '<h2>Headline 2</h2>'],
            'H2 setext underline with trailing space' => ["Headline 2\n------   ", '<h2>Headline 2</h2>'],

            // H3
            'H3 standard' => ['### Headline 3', '<h3>Headline 3</h3>'],
            'H3 no space after ###' => ['###Headline 3', '###Headline 3'],
            'H3 leading spaces' => ['   ### Headline 3', '<h3>Headline 3</h3>'],
            'H3 trailing spaces' => ['### Headline 3   ', '<h3>Headline 3</h3>'],
            'H3 multiple spaces after ###' => ['###    Headline 3', '<h3>Headline 3</h3>'],
            'H3 hash at end' => ['### Headline 3 ### extra', '<h3>Headline 3 ### extra</h3>'],

            // H4
            'H4 standard' => ['#### Headline 4', '<h4>Headline 4</h4>'],
            'H4 no space after ####' => ['####Headline 4', '####Headline 4'],
            'H4 leading spaces' => ['   #### Headline 4', '<h4>Headline 4</h4>'],
            'H4 trailing spaces' => ['#### Headline 4   ', '<h4>Headline 4</h4>'],
            'H4 multiple spaces after ####' => ['####    Headline 4', '<h4>Headline 4</h4>'],
            'H4 hash at end' => ['#### Headline 4 #### extra', '<h4>Headline 4 #### extra</h4>'],

            // H5
            'H5 standard' => ['##### Headline 5', '<h5>Headline 5</h5>'],
            'H5 no space after #####' => ['#####Headline 5', '#####Headline 5'],
            'H5 leading spaces' => ['   ##### Headline 5', '<h5>Headline 5</h5>'],
            'H5 trailing spaces' => ['##### Headline 5   ', '<h5>Headline 5</h5>'],
            'H5 multiple spaces after #####' => ['#####    Headline 5', '<h5>Headline 5</h5>'],
            'H5 hash at end' => ['##### Headline 5 ##### extra', '<h5>Headline 5 ##### extra</h5>'],

            // H6
            'H6 standard' => ['###### Headline 6', '<h6>Headline 6</h6>'],
            'H6 no space after ######' => ['######Headline 6', '######Headline 6'],
            'H6 leading spaces' => ['   ###### Headline 6', '<h6>Headline 6</h6>'],
            'H6 trailing spaces' => ['###### Headline 6   ', '<h6>Headline 6</h6>'],
            'H6 multiple spaces after ######' => ['######    Headline 6', '<h6>Headline 6</h6>'],
            'H6 hash at end' => ['###### Headline 6 ###### extra', '<h6>Headline 6 ###### extra</h6>'],
        ];
    }


    #[DataProvider('headlineProvider')]
    public function testHeadline1Variants(string $markdown, string $expectedHtml)
    {
        $lexer = new MarkdownLexer();
        $lexer->addPlugin(new HeaderPlugin(1));
        $lexer->addPlugin(new SeTextPlugin(2));
        $lexer->addPlugin(new SeTextPlugin(3, '-', 2));
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