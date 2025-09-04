<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit\Plugin;

use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\Plugin\LinkPlugin;

class LinkPluginTest extends TestCase
{
    private LinkPlugin $plugin;

    protected function setUp(): void
    {
        $this->plugin = new LinkPlugin(1);
    }

    public function testSupports() {
        $this->assertFalse($this->plugin->supports());
        $this->assertTrue($this->plugin->supports(new MarkdownToken('CONTENT')));
        $this->assertTrue($this->plugin->supports(new MarkdownToken('UNSORTED_LIST_ELEMENT_CONTENT')));
        $this->assertTrue($this->plugin->supports(new MarkdownToken('SORTED_LIST_ELEMENT_CONTENT')));
    }

    public function testParseNoResult() {
        $result = $this->plugin->parse('test: [test](https://www.google.com)',0);
        $this->assertNull($result);
    }

    public function testParse() {
        $result = $this->plugin->parse('test: [test](https://www.google.com)',6);
        $this->assertNotNull($result);
        $this->assertCount(3, $result->getTokens());
        $this->assertEquals(36, $result->getPosition());
        $this->assertEquals('LINK_START', $result->getTokens()[0]->getType());
        $this->assertEquals('https://www.google.com', $result->getTokens()[0]->getContent());
        $this->assertEquals('LINK_CONTENT', $result->getTokens()[1]->getType());
        $this->assertEquals('test', $result->getTokens()[1]->getContent());
        $this->assertEquals('LINK_END', $result->getTokens()[2]->getType());
    }
}