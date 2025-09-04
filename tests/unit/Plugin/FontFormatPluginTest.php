<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit\Plugin;

use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\Plugin\FontFormatPlugin;

class FontFormatPluginTest extends TestCase
{
    private FontFormatPlugin $plugin;

    protected function setUp(): void
    {
        $this->plugin = new FontFormatPlugin(1);
    }

    public function testParseNoResult() {
        $result = $this->plugin->parse('test ***bold** italic* ~~strikethrough~~',0);
        $this->assertNull($result);
    }

    public function testParseBold() {
        $result = $this->plugin->parse('test ***italic* bold** ~~strikethrough~~',5);
        $this->assertNotNull($result);
        $this->assertCount(3, $result->getTokens());
        $this->assertEquals(22, $result->getPosition());
        $this->assertEquals('BOLD_START', $result->getTokens()[0]->getType());
        $this->assertEquals('CONTENT', $result->getTokens()[1]->getType());
        $this->assertEquals('*italic* bold', $result->getTokens()[1]->getContent());
        $this->assertEquals('BOLD_END', $result->getTokens()[2]->getType());
    }

    public function testParseItalic() {
        $result = $this->plugin->parse('test ***italic* bold** ~~strikethrough~~',7);
        $this->assertNotNull($result);
        $this->assertCount(3, $result->getTokens());
        $this->assertEquals(15, $result->getPosition());
        $this->assertEquals('ITALIC_START', $result->getTokens()[0]->getType());
        $this->assertEquals('CONTENT', $result->getTokens()[1]->getType());
        $this->assertEquals('italic', $result->getTokens()[1]->getContent());
        $this->assertEquals('ITALIC_END', $result->getTokens()[2]->getType());
    }

    public function testParseStrikethrough() {
        $result = $this->plugin->parse('test ***italic* bold** ~~strikethrough~~',23);
        $this->assertNotNull($result);
        $this->assertCount(3, $result->getTokens());
        $this->assertEquals(40, $result->getPosition());
        $this->assertEquals('STRIKETHROUGH_START', $result->getTokens()[0]->getType());
        $this->assertEquals('CONTENT', $result->getTokens()[1]->getType());
        $this->assertEquals('strikethrough', $result->getTokens()[1]->getContent());
        $this->assertEquals('STRIKETHROUGH_END', $result->getTokens()[2]->getType());
    }
}