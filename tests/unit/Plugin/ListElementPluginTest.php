<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit\Plugin;

use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\Plugin\ListElementPlugin;

class ListElementPluginTest extends TestCase
{
    public function testSupports() {
        $plugin = new ListElementPlugin(1);

        $this->assertFalse($plugin->supports());
        $this->assertFalse($plugin->supports(new MarkdownToken('CONTENT')));
        $this->assertFalse($plugin->supports(new MarkdownToken('SORTED_LIST_CONTENT')));
        $this->assertTrue($plugin->supports(new MarkdownToken('UNSORTED_LIST_CONTENT')));

        $plugin = new ListElementPlugin(1, 'SORTED');

        $this->assertFalse($plugin->supports());
        $this->assertFalse($plugin->supports(new MarkdownToken('CONTENT')));
        $this->assertTrue($plugin->supports(new MarkdownToken('SORTED_LIST_CONTENT')));
        $this->assertFalse($plugin->supports(new MarkdownToken('UNSORTED_LIST_CONTENT')));
    }

    public function testParseNoResult() {
        $plugin = new ListElementPlugin(1);
        $result = $plugin->parse('test: * test1', 0);
        $this->assertNull($result);
    }

    public function testParseUnsortedResult() {
        $plugin = new ListElementPlugin(1);
        $result = $plugin->parse("* test1\n  * test2", 0);
        $this->assertNotNull($result);
        $this->assertCount(3, $result->getTokens());
        $this->assertEquals(17, $result->getPosition());
        $this->assertEquals('UNSORTED_LIST_ELEMENT_START', $result->getTokens()[0]->getType());
        $this->assertEquals('*', $result->getTokens()[0]->getContent());
        $this->assertEquals('UNSORTED_LIST_ELEMENT_CONTENT', $result->getTokens()[1]->getType());
        $this->assertEquals("test1\n  * test2", $result->getTokens()[1]->getContent());
        $this->assertEquals('UNSORTED_LIST_ELEMENT_END', $result->getTokens()[2]->getType());
    }

    public function testParseSortedResult() {
        $plugin = new ListElementPlugin(1, 'SORTED');
        $result = $plugin->parse("1. test1\n  2. test2", 0);
        $this->assertNotNull($result);
        $this->assertCount(3, $result->getTokens());
        $this->assertEquals(19, $result->getPosition());
        $this->assertEquals('SORTED_LIST_ELEMENT_START', $result->getTokens()[0]->getType());
        $this->assertEquals('1.', $result->getTokens()[0]->getContent());
        $this->assertEquals('SORTED_LIST_ELEMENT_CONTENT', $result->getTokens()[1]->getType());
        $this->assertEquals("test1\n  2. test2", $result->getTokens()[1]->getContent());
        $this->assertEquals('SORTED_LIST_ELEMENT_END', $result->getTokens()[2]->getType());
    }
}