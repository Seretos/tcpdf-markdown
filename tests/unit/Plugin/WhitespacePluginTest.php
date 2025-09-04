<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit\Plugin;

use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\Plugin\WhitespacePlugin;

class WhitespacePluginTest extends TestCase
{
    private WhitespacePlugin $plugin;

    protected function setUp(): void
    {
        $this->plugin = new WhitespacePlugin(1);
    }

    public function testNoResult() {
        $this->assertNull($this->plugin->parse("a \n", 0));
    }

    public function testNewLine() {
        $result = $this->plugin->parse("a \n\n", 2);
        $this->assertNotNull($result);
        $this->assertCount(1, $result->getTokens());
        $this->assertEquals(4, $result->getPosition());
        $this->assertEquals('NEWLINE', $result->getTokens()[0]->getType());
        $this->assertEquals("\n\n", $result->getTokens()[0]->getContent());
    }

    public function testNewLineToWhitespace() {
        $result = $this->plugin->parse("a\nb", 1);
        $this->assertNotNull($result);
        $this->assertCount(1, $result->getTokens());
        $this->assertEquals(2, $result->getPosition());
        $this->assertEquals('WHITESPACE', $result->getTokens()[0]->getType());
        $this->assertEquals(" ", $result->getTokens()[0]->getContent());
    }

    public function testWhitespace() {
        $result = $this->plugin->parse("a b", 1);
        $this->assertNotNull($result);
        $this->assertCount(1, $result->getTokens());
        $this->assertEquals(2, $result->getPosition());
        $this->assertEquals('WHITESPACE', $result->getTokens()[0]->getType());
        $this->assertEquals(" ", $result->getTokens()[0]->getContent());
    }
}