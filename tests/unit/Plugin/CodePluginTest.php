<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit\Plugin;

use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\Plugin\CodePlugin;

class CodePluginTest extends TestCase
{
    private CodePlugin $plugin;

    protected function setUp(): void
    {
        $this->plugin = new CodePlugin(1);
    }

    public function testSupports() {
        $this->assertTrue($this->plugin->supports());
        $this->assertFalse($this->plugin->supports(new MarkdownToken('CONTENT')));
    }

    public function testParseNoResult() {
        $result = $this->plugin->parse("test\n```\ncode without language\n```",0);
        $this->assertNull($result);
    }

    public function testParseNoLanguage() {
        $result = $this->plugin->parse("test\n```\ncode without language\n```",5);
        $this->assertNotNull($result);
        $this->assertCount(3, $result->getTokens());
        $this->assertEquals(34, $result->getPosition());
        $this->assertEquals('CODE_START', $result->getTokens()[0]->getType());
        $this->assertEquals('', $result->getTokens()[0]->getContent());
        $this->assertEquals('CODE_CONTENT', $result->getTokens()[1]->getType());
        $this->assertEquals("code without language\n", $result->getTokens()[1]->getContent());
        $this->assertEquals('CODE_END', $result->getTokens()[2]->getType());
    }

    public function testParse() {
        $result = $this->plugin->parse("test\n```php\ncode without language\n```",5);
        $this->assertNotNull($result);
        $this->assertCount(3, $result->getTokens());
        $this->assertEquals(37, $result->getPosition());
        $this->assertEquals('CODE_START', $result->getTokens()[0]->getType());
        $this->assertEquals('php', $result->getTokens()[0]->getContent());
        $this->assertEquals('CODE_CONTENT', $result->getTokens()[1]->getType());
        $this->assertEquals("code without language\n", $result->getTokens()[1]->getContent());
        $this->assertEquals('CODE_END', $result->getTokens()[2]->getType());
    }
}