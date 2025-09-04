<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown;

use seretos\TCPDFMarkdown\Html\HtmlNodeFactoryInterface;
use seretos\TCPDFMarkdown\Html\HtmlTransformer;
use seretos\TCPDFMarkdown\Plugin\CheckboxPlugin;
use seretos\TCPDFMarkdown\Plugin\CodePlugin;
use seretos\TCPDFMarkdown\Plugin\DefinitionListPlugin;
use seretos\TCPDFMarkdown\Plugin\DividerPlugin;
use seretos\TCPDFMarkdown\Plugin\FontFormatPlugin;
use seretos\TCPDFMarkdown\Plugin\GreaterQuotePlugin;
use seretos\TCPDFMarkdown\Plugin\HeaderPlugin;
use seretos\TCPDFMarkdown\Plugin\ImagePlugin;
use seretos\TCPDFMarkdown\Plugin\InlineCodePlugin;
use seretos\TCPDFMarkdown\Plugin\LinkPlugin;
use seretos\TCPDFMarkdown\Plugin\NewlinePlugin;
use seretos\TCPDFMarkdown\Plugin\PHPCodePlugin;
use seretos\TCPDFMarkdown\Plugin\ReferencePlugin;
use seretos\TCPDFMarkdown\Plugin\SeTextPlugin;
use seretos\TCPDFMarkdown\Plugin\TablePlugin;
use seretos\TCPDFMarkdown\Plugin\TabQuotePlugin;
use seretos\TCPDFMarkdown\Plugin\TextBlockPlugin;
use seretos\TCPDFMarkdown\Plugin\TextPlugin;
use seretos\TCPDFMarkdown\Plugin\ListElementPlugin;
use seretos\TCPDFMarkdown\Plugin\ListPlugin;
use seretos\TCPDFMarkdown\Plugin\WhitespacePlugin;

class Factory implements HtmlNodeFactoryInterface
{
    public static function createLexer(): MarkdownLexer {
        $lexer = new MarkdownLexer();

        $lexer->addPlugin(new PHPCodePlugin(1));

        $lexer->addPlugin(new ImagePlugin(1));

        $lexer->addPlugin(new CodePlugin(1));
        $lexer->addPlugin(new TabQuotePlugin(1));
        $lexer->addPlugin(new GreaterQuotePlugin(2));
        $lexer->addPlugin(new DefinitionListPlugin(2));

        $lexer->addPlugin(new TablePlugin(3));

        $lexer->addPlugin(new SeTextPlugin(4)); //h1
        $lexer->addPlugin(new SeTextPlugin(5, '-',2)); //h2
        $lexer->addPlugin(new HeaderPlugin(6));

        $lexer->addPlugin(new DividerPlugin(7));
        $lexer->addPlugin(new DividerPlugin(7,'*'));
        $lexer->addPlugin(new DividerPlugin(7,'_'));

        $lexer->addPlugin(new ListPlugin(8));
        $lexer->addPlugin(new ListElementPlugin(8));

        $lexer->addPlugin(new ListPlugin(8, 'SORTED'));
        $lexer->addPlugin(new ListElementPlugin(8, 'SORTED'));

        $lexer->addPlugin(new TextBlockPlugin(9));

        $lexer->addPlugin(new ReferencePlugin(92));
        $lexer->addPlugin(new LinkPlugin(93));
        $lexer->addPlugin(new CheckboxPlugin(94));
        $lexer->addPlugin(new FontFormatPlugin(95));

        $lexer->addPlugin(new InlineCodePlugin(97));
        $lexer->addPlugin(new TextPlugin(98));
        $lexer->addPlugin(new NewlinePlugin(99));
        $lexer->addPlugin(new WhitespacePlugin(100));

        return $lexer;
    }

    public static function createTokenParser(): TokenParser {
        return new TokenParser();
    }

    public static function createHtmlTransformer(): HtmlTransformer {
        return new HtmlTransformer();
    }

    private function getNodeName(Node $node): string {
        $type = strtolower($node->getType());
        $parts = explode('_', $type);
        $parts = array_map('ucfirst', $parts);
        return implode('', $parts);
    }

    public function findHtmlNodeClass(Node $node): string {
        return 'seretos\\TCPDFMarkdown\\Html\\Elements\\Html' . $this->getNodeName($node);
    }
}