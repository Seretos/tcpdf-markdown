<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit\Plugin;

use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\Plugin\ListPlugin;

class ListPluginTest extends TestCase
{
    public function testSupports() {
        $plugin = new ListPlugin(1);
        $this->assertTrue($plugin->supports());
        $this->assertFalse($plugin->supports(new MarkdownToken('CONTENT')));
        $this->assertTrue($plugin->supports(new MarkdownToken('UNSORTED_LIST_ELEMENT_CONTENT')));
        $this->assertTrue($plugin->supports(new MarkdownToken('SORTED_LIST_ELEMENT_CONTENT')));
    }

    public function testNoResult() {
        $plugin = new ListPlugin(1);
        $result = $plugin->parse("test:\n* first unsorted\n  * first unsorted subelement\n* second unsorted", 0);
        $this->assertNull($result);
    }

    public function testUnsorted() {
        $plugin = new ListPlugin(1);
        $result = $plugin->parse("test:\n* first unsorted\n  * first unsorted subelement\n* second unsorted", 6);
        $this->assertNotNull($result);
        $this->assertCount(4, $result->getTokens());
        $this->assertEquals(71, $result->getPosition());
        $this->assertEquals('UNSORTED_LIST_START', $result->getTokens()[0]->getType());
        $this->assertEquals('UNSORTED_LIST_CONTENT', $result->getTokens()[1]->getType());
        $this->assertEquals("* first unsorted\n  * first unsorted subelement", $result->getTokens()[1]->getContent());
        $this->assertEquals('UNSORTED_LIST_CONTENT', $result->getTokens()[2]->getType());
        $this->assertEquals("* second unsorted\n", $result->getTokens()[2]->getContent());
        $this->assertEquals('UNSORTED_LIST_END', $result->getTokens()[3]->getType());
    }

    public function testSorted() {
        $plugin = new ListPlugin(1, "SORTED");
        $result = $plugin->parse("test:\n1. first unsorted\n   1. first unsorted subelement\n2. second unsorted", 6);
        $this->assertNotNull($result);
        $this->assertCount(4, $result->getTokens());
        $this->assertEquals(75, $result->getPosition());
        $this->assertEquals('SORTED_LIST_START', $result->getTokens()[0]->getType());
        $this->assertEquals('SORTED_LIST_CONTENT', $result->getTokens()[1]->getType());
        $this->assertEquals("1. first unsorted\n   1. first unsorted subelement", $result->getTokens()[1]->getContent());
        $this->assertEquals('SORTED_LIST_CONTENT', $result->getTokens()[2]->getType());
        $this->assertEquals("2. second unsorted\n", $result->getTokens()[2]->getContent());
        $this->assertEquals('SORTED_LIST_END', $result->getTokens()[3]->getType());
    }
}