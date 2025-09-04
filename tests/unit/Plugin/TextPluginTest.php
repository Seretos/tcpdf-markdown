<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit\Plugin;

use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\Plugin\TextPlugin;

class TextPluginTest extends TestCase
{
    private TextPlugin $plugin;

    protected function setUp(): void
    {
        $this->plugin = new TextPlugin(1);
    }

    public function testNoResult() {
        $result = $this->plugin->parse(' a', 0);
        $this->assertNull($result);
    }

    public function testWord() {
        $result = $this->plugin->parse(' hello world', 1);
        $this->assertNotNull($result);
        $this->assertCount(1, $result->getTokens());
        $this->assertEquals(6, $result->getPosition());
        $this->assertEquals('TEXT', $result->getTokens()[0]->getType());
        $this->assertEquals('hello', $result->getTokens()[0]->getContent());
    }

    public function testSplit() {
        $result = $this->plugin->parse(' hello*world', 1);
        $this->assertNotNull($result);
        $this->assertCount(1, $result->getTokens());
        $this->assertEquals(6, $result->getPosition());
        $this->assertEquals('TEXT', $result->getTokens()[0]->getType());
        $this->assertEquals('hello', $result->getTokens()[0]->getContent());
    }
}