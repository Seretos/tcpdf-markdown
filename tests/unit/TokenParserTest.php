<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit;

use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\TokenParser;

class TokenParserTest extends TestCase
{
    public function testParse() {
        $tokens = [
            new MarkdownToken('TEXT_BLOCK_START'),
            new MarkdownToken('BOLD_START','**'),
            new MarkdownToken('TEXT', 'test'),
            new MarkdownToken('BOLD_END'),
            new MarkdownToken('ITALIC_START','*'),
            new MarkdownToken('ITALIC_END'),
            new MarkdownToken('TEXT_BLOCK_END')
        ];

        $parser = new TokenParser();

        $node = $parser->parse($tokens);

        $this->assertSame('DOCUMENT', $node->getType());
        $this->assertSame(null, $node->getValue());
        $this->assertCount(1, $node->getChildren());

        $this->assertSame('TEXT_BLOCK', $node->getChildren()[0]->getType());
        $this->assertCount(2, $node->getChildren()[0]->getChildren());

        $this->assertSame('BOLD',$node->getChildren()[0]->getChildren()[0]->getType());
        $this->assertSame('**',$node->getChildren()[0]->getChildren()[0]->getValue());

        $this->assertCount(1, $node->getChildren()[0]->getChildren()[0]->getChildren());

        $this->assertSame('TEXT',$node->getChildren()[0]->getChildren()[0]->getChildren()[0]->getType());
        $this->assertSame('test',$node->getChildren()[0]->getChildren()[0]->getChildren()[0]->getValue());

        $this->assertSame('ITALIC',$node->getChildren()[0]->getChildren()[1]->getType());
        $this->assertSame('*',$node->getChildren()[0]->getChildren()[1]->getValue());
    }
}