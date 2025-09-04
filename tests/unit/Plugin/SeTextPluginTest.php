<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit\Plugin;

use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\Plugin\SeTextPlugin;

class SeTextPluginTest extends TestCase
{
    public function testNoResult() {
        $plugin = new SeTextPlugin(1);
        $result = $plugin->parse("this is a normal test\nheadline 1\n=",0);
        $this->assertNull($result);
    }

    public function testHeadline1Result() {
        $plugin = new SeTextPlugin(1);
        $result = $plugin->parse("this is a normal test\nheadline 1\n=\nheadline 2\n---",22);
        $this->assertNotNull($result);
        $this->assertCount(3,$result->getTokens());
        $this->assertEquals(35,$result->getPosition());
        $this->assertEquals('HEADER_START', $result->getTokens()[0]->getType());
        $this->assertEquals(1, $result->getTokens()[0]->getContent());

        $this->assertEquals('CONTENT', $result->getTokens()[1]->getType());
        $this->assertEquals('headline 1', $result->getTokens()[1]->getContent());

        $this->assertEquals('HEADER_END', $result->getTokens()[2]->getType());
    }

    public function testHeadline2Result() {
        $plugin = new SeTextPlugin(1,'-',2);
        $result = $plugin->parse("this is a normal test\nheadline 1\n=\nheadline 2\n---",35);
        $this->assertNotNull($result);
        $this->assertCount(3,$result->getTokens());
        $this->assertEquals(49,$result->getPosition());
        $this->assertEquals('HEADER_START', $result->getTokens()[0]->getType());
        $this->assertEquals(2, $result->getTokens()[0]->getContent());

        $this->assertEquals('CONTENT', $result->getTokens()[1]->getType());
        $this->assertEquals('headline 2', $result->getTokens()[1]->getContent());

        $this->assertEquals('HEADER_END', $result->getTokens()[2]->getType());
    }
}