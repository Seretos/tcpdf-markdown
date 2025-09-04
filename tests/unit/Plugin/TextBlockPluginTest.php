<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit\Plugin;

use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\Plugin\TextBlockPlugin;

class TextBlockPluginTest extends TestCase
{
    private TextBlockPlugin $plugin;

    protected function setUp(): void
    {
        $this->plugin = new TextBlockPlugin(1);
    }

    public function testResult() {
        $result = $this->plugin->parse(" hello world\nthis is a test\n\nand another test  ",0);
        $this->assertNotNull($result);
        $this->assertCount(3,$result->getTokens());
        $this->assertEquals(29, $result->getPosition());

        $this->assertEquals('BLOCK_START', $result->getTokens()[0]->getType());
        $this->assertEquals('CONTENT', $result->getTokens()[1]->getType());
        $this->assertEquals("hello world\nthis is a test",$result->getTokens()[1]->getContent());
        $this->assertEquals('BLOCK_END',$result->getTokens()[2]->getType());
    }

    public function testSupports() {
        $this->assertTrue($this->plugin->supports());
        $this->assertFalse($this->plugin->supports(new MarkdownToken('CONTENT')));
    }
}