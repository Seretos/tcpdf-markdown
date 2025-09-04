<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit\Plugin;

use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\Plugin\GreaterQuotePlugin;

class GreaterQuotePluginTest extends TestCase
{
    private GreaterQuotePlugin $plugin;

    protected function setUp(): void
    {
        $this->plugin = new GreaterQuotePlugin(1);
    }

    public function testSupports() {
        $this->assertTrue($this->plugin->supports());
        $this->assertTrue($this->plugin->supports(new MarkdownToken('GREATER_QUOTE_CONTENT')));
        $this->assertTrue($this->plugin->supports(new MarkdownToken('CONTENT')));
        $this->assertTrue($this->plugin->supports(new MarkdownToken('SORTED_LIST_ELEMENT_CONTENT')));
        $this->assertTrue($this->plugin->supports(new MarkdownToken('UNSORTED_LIST_ELEMENT_CONTENT')));
    }

    public function testParseNoResult() {
        $result = $this->plugin->parse("test\n> quota", 0);
        $this->assertNull($result);
    }

    public function testParseResult() {
        $result = $this->plugin->parse("test\n> quota\n>quota2\nno quota", 5);
        $this->assertNotNull($result);
        $this->assertCount(3, $result->getTokens());
        $this->assertEquals(21, $result->getPosition());
        $this->assertEquals('QUOTE_START', $result->getTokens()[0]->getType());
        $this->assertEquals('GREATER_QUOTE_CONTENT', $result->getTokens()[1]->getType());
        $this->assertEquals("quota\nquota2", $result->getTokens()[1]->getContent());
        $this->assertEquals('QUOTE_END', $result->getTokens()[2]->getType());
    }
}