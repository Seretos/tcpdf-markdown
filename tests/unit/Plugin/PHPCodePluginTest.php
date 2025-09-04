<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit\Plugin;

use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\Plugin\PHPCodePlugin;

class PHPCodePluginTest extends TestCase
{
    private PHPCodePlugin $plugin;

    protected function setUp(): void
    {
        $this->plugin = new PHPCodePlugin(1);
    }

    public function testSupported() {
        $this->assertFalse($this->plugin->supports());
        $this->assertFalse($this->plugin->supports(new MarkdownToken('CONTENT')));
        $this->assertTrue($this->plugin->supports(new MarkdownToken('CODE_CONTENT','',true, ['type' => 'php'])));
    }

    public function testParseNoResult() {
        $result = $this->plugin->parse('  public function hello($world)',0);
        $this->assertNull($result);
    }

    public function testParseKeyword() {
        $result = $this->plugin->parse('  public function hello($world)',2);
        $this->assertNotNull($result);
        $this->assertCount(1, $result->getTokens());
        $this->assertEquals(8, $result->getPosition());
        $this->assertEquals('CODE_KEYWORD', $result->getTokens()[0]->getType());
        $this->assertEquals('public', $result->getTokens()[0]->getContent());
    }

    public function testParseVariable() {
        $result = $this->plugin->parse('  public function hello($world)',24);
        $this->assertNotNull($result);
        $this->assertCount(1, $result->getTokens());
        $this->assertEquals(30, $result->getPosition());
        $this->assertEquals('CODE_VARIABLE', $result->getTokens()[0]->getType());
        $this->assertEquals('$world', $result->getTokens()[0]->getContent());
    }

    public function testParseString() {
        $result = $this->plugin->parse('print "hello world";',6);
        $this->assertNotNull($result);
        $this->assertCount(3, $result->getTokens());
        $this->assertEquals(19, $result->getPosition());
        $this->assertEquals('CODE_STRING_START', $result->getTokens()[0]->getType());
        $this->assertEquals('"', $result->getTokens()[0]->getContent());
        $this->assertEquals('CONTENT', $result->getTokens()[1]->getType());
        $this->assertEquals('"hello world"', $result->getTokens()[1]->getContent());
        $this->assertEquals('CODE_STRING_END', $result->getTokens()[2]->getType());
    }
}