<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit\Plugin;

use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\Plugin\ImagePlugin;

class ImagePluginTest extends TestCase
{
    private ImagePlugin $plugin;

    protected function setUp(): void
    {
        $this->plugin = new ImagePlugin(1);
    }

    public function testSupports()
    {
        $this->assertTrue($this->plugin->supports());
        $this->assertTrue($this->plugin->supports(new MarkdownToken('CONTENT')));
        $this->assertTrue($this->plugin->supports(new MarkdownToken('LINK_CONTENT')));
    }

    public function testParseNoResult()
    {
        $result = $this->plugin->parse('test: ![img](https://example.com/test.png)', 0);
        $this->assertNull($result);
    }

    public function testParseMarkdownImage()
    {
        $result = $this->plugin->parse('test: ![img](https://example.com/test.png)', 6);
        $this->assertNotNull($result);
        $this->assertCount(1, $result->getTokens());
        $this->assertEquals(42, $result->getPosition());
        $this->assertEquals('IMAGE', $result->getTokens()[0]->getType());
        $this->assertEquals('https://example.com/test.png', $result->getTokens()[0]->getContent());
    }

    public function testParseHtmlImage()
    {
        $result = $this->plugin->parse('test: <img src="https://example.com/test.png" width="400" height="300"/>', 6);
        $this->assertNotNull($result);
        $this->assertCount(1, $result->getTokens());
        $this->assertEquals(72, $result->getPosition());
        $this->assertEquals('IMAGE', $result->getTokens()[0]->getType());
        $this->assertEquals('https://example.com/test.png', $result->getTokens()[0]->getContent());
        $this->assertSame(['src' => 'https://example.com/test.png', 'width' => '400', 'height' => '300'], $result->getTokens()[0]->getMetaData());
    }
}