<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit\Plugin;

use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\Plugin\NewlinePlugin;

class NewLinePluginTest extends TestCase
{
    private NewlinePlugin $plugin;

    protected function setUp(): void
    {
        $this->plugin = new NewlinePlugin(1);
    }

    public function testSupports() {
        $this->assertFalse($this->plugin->supports());
        $this->assertFalse($this->plugin->supports(new MarkdownToken('CONTENT')));
        $this->assertTrue($this->plugin->supports(new MarkdownToken('TAB_QUOTE_CONTENT')));
        $this->assertTrue($this->plugin->supports(new MarkdownToken('GREATER_QUOTE_CONTENT')));
        $this->assertTrue($this->plugin->supports(new MarkdownToken('CODE_CONTENT')));
    }

    public function testParseNoResult() {
        $result = $this->plugin->parse(" \n ",0);
        $this->assertNull($result);
    }

    public function testNewline() {
        $result = $this->plugin->parse(" \n ", 1);
        $this->assertNotNull($result);
        $this->assertCount(1, $result->getTokens());
        $this->assertEquals(2, $result->getPosition());
        $this->assertEquals('NEWLINE', $result->getTokens()[0]->getType());
        $this->assertEquals("\n", $result->getTokens()[0]->getContent());
    }
}