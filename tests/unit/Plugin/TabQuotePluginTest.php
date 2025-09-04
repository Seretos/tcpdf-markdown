<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit\Plugin;

use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\Plugin\TabQuotePlugin;

class TabQuotePluginTest extends TestCase
{
    private TabQuotePlugin $plugin;

    protected function setUp(): void
    {
        $this->plugin = new TabQuotePlugin(1);
    }

    public function testSupports() {
        $this->assertTrue($this->plugin->supports());
        $this->assertTrue($this->plugin->supports(new MarkdownToken('GREATER_QUOTE_CONTENT')));
        $this->assertFalse($this->plugin->supports(new MarkdownToken('CONTENT')));
    }

    public function testParseWithoutResult() {
        $result = $this->plugin->parse("test\n\n    this is a quoted text\n    and another quoted text",0);
        $this->assertNull($result);
    }

    public function testParse() {
        $result = $this->plugin->parse("test\n\n    this is a quoted text\n    and another quoted text",6);
        $this->assertNotNull($result);
        $this->assertCount(3, $result->getTokens());
        $this->assertEquals(59, $result->getPosition());
        $this->assertEquals('QUOTE_START',$result->getTokens()[0]->getType());
        $this->assertEquals('TAB_QUOTE_CONTENT',$result->getTokens()[1]->getType());
        $this->assertEquals("this is a quoted text\nand another quoted text",$result->getTokens()[1]->getContent());
        $this->assertEquals('QUOTE_END',$result->getTokens()[2]->getType());
    }
}