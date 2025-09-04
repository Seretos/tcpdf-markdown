<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit\Plugin;

use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\Plugin\DefinitionListPlugin;

class DefinitionListPluginTest extends TestCase
{
    private DefinitionListPlugin $plugin;

    protected function setUp(): void
    {
        $this->plugin = new DefinitionListPlugin(1);
    }

    public function testSupports() {
        $this->assertTrue($this->plugin->supports());
        $this->assertFalse($this->plugin->supports(new MarkdownToken('CONTENT')));
    }

    public function testParseNoResult() {
        $result = $this->plugin->parse("test\ntest2\n: test3: test4\n: test5\n: test6", 0);
        $this->assertNull($result);
    }

    public function testParse() {
        $result = $this->plugin->parse("test\ntest2\n: test3: test4\n: test5\n: test6", 5);
        $this->assertNotNull($result);
        $this->assertCount(5, $result->getTokens());
        $this->assertEquals(41, $result->getPosition());
        $this->assertEquals('DEFINITION_LIST_START', $result->getTokens()[0]->getType());
        $this->assertEquals('test2', $result->getTokens()[0]->getContent());
        $this->assertEquals('DEFINITION', $result->getTokens()[1]->getType());
        $this->assertEquals('test3: test4', $result->getTokens()[1]->getContent());
        $this->assertEquals('DEFINITION', $result->getTokens()[2]->getType());
        $this->assertEquals('test5', $result->getTokens()[2]->getContent());
        $this->assertEquals('DEFINITION', $result->getTokens()[3]->getType());
        $this->assertEquals('test6', $result->getTokens()[3]->getContent());
        $this->assertEquals('DEFINITION_LIST_END', $result->getTokens()[4]->getType());
    }
}