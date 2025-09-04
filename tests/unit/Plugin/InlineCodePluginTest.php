<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit\Plugin;

use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\Plugin\InlineCodePlugin;

class InlineCodePluginTest extends TestCase
{
    private InlineCodePlugin $plugin;

    protected function setUp(): void
    {
        $this->plugin = new InlineCodePlugin(1);
    }

    public function testSupports() {
        $this->assertTrue($this->plugin->supports());
        $this->assertTrue($this->plugin->supports(new MarkdownToken('CONTENT')));
        $this->assertFalse($this->plugin->supports(new MarkdownToken('TAB_QUOTE_CONTENT')));
    }

    public function testParseNoResult() {
        $result = $this->plugin->parse('his is `a code`', 0);
        $this->assertNull($result);
    }

    public function testParse() {
        $result = $this->plugin->parse('his is `a code`', 7);
        $this->assertNotNull($result);
        $this->assertCount(1, $result->getTokens());
        $this->assertEquals(15, $result->getPosition());
        $this->assertEquals('INLINE_CODE', $result->getTokens()[0]->getType());
        $this->assertEquals('a code', $result->getTokens()[0]->getContent());
    }
}