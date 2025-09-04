<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit\Plugin;

use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\Plugin\CheckboxPlugin;

class CheckboxPluginTest extends TestCase
{
    private CheckboxPlugin $plugin;

    protected function setUp(): void
    {
        $this->plugin = new CheckboxPlugin(1);
    }

    public function testSupports() {
        $this->assertFalse($this->plugin->supports());
        $this->assertTrue($this->plugin->supports(new MarkdownToken('SORTED_LIST_ELEMENT_CONTENT')));
        $this->assertTrue($this->plugin->supports(new MarkdownToken('UNSORTED_LIST_ELEMENT_CONTENT')));
    }

    public function testParseNoResult() {
        $result = $this->plugin->parse(' test [ ] checkbox', 0);
        $this->assertNull($result);
    }

    public function testParseNotChecked() {
        $result = $this->plugin->parse(' test [ ] checkbox', 6);
        $this->assertNotNull($result);
        $this->assertCount(1,$result->getTokens());
        $this->assertEquals(9, $result->getPosition());
        $this->assertEquals('CHECKBOX', $result->getTokens()[0]->getType());
        $this->assertFalse($result->getTokens()[0]->getContent());
    }

    public function testParseChecked() {
        $result = $this->plugin->parse(' test [x] checkbox', 6);
        $this->assertNotNull($result);
        $this->assertCount(1,$result->getTokens());
        $this->assertEquals(9, $result->getPosition());
        $this->assertEquals('CHECKBOX', $result->getTokens()[0]->getType());
        $this->assertTrue($result->getTokens()[0]->getContent());
    }
}