<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit\Plugin;

use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\Plugin\TablePlugin;

class TablePluginTest extends TestCase
{
    private TablePlugin $plugin;

    protected function setUp(): void
    {
        $this->plugin = new TablePlugin(1);
    }

    public function testParseNoResult() {
        $result = $this->plugin->parse("now comes a table:\n | header1 | header2 | header 3 |",0);
        $this->assertNull($result);
    }

    public function testParseTableNoContent() {
        $result = $this->plugin->parse("now comes a table:\n | header1 | header2 | header 3 |",19);
        $this->assertNull($result);
    }

    public function testParseTableWidthContent() {
        $result = $this->plugin->parse("now comes a table:\n | header1 | header2 | header 3 |\n|-|-|-|\n| column1 | column2 |column3|",19);
        $this->assertNotNull($result);
        $this->assertCount(24, $result->getTokens());
        $this->assertEquals(90,$result->getPosition());

        $this->assertEquals('TABLE_START', $result->getTokens()[0]->getType());
        $this->assertEquals('TABLE_HEADER_ROW_START', $result->getTokens()[1]->getType());
        $this->assertEquals('TABLE_HEADER_COL_START', $result->getTokens()[2]->getType());
        $this->assertEquals('left', $result->getTokens()[2]->getContent());
        $this->assertEquals('CONTENT', $result->getTokens()[3]->getType());
        $this->assertEquals('header1', $result->getTokens()[3]->getContent());
        $this->assertEquals('TABLE_HEADER_COL_END', $result->getTokens()[4]->getType());
        $this->assertEquals('TABLE_HEADER_COL_START', $result->getTokens()[5]->getType());
        $this->assertEquals('left', $result->getTokens()[5]->getContent());
        $this->assertEquals('CONTENT', $result->getTokens()[6]->getType());
        $this->assertEquals('header2', $result->getTokens()[6]->getContent());
        //...
        $this->assertEquals('TABLE_HEADER_ROW_END', $result->getTokens()[11]->getType());
        $this->assertEquals('TABLE_ROW_START', $result->getTokens()[12]->getType());
        $this->assertEquals('TABLE_COL_START', $result->getTokens()[13]->getType());
        $this->assertEquals('left', $result->getTokens()[13]->getContent());
        $this->assertEquals('CONTENT', $result->getTokens()[14]->getType());
        $this->assertEquals('column1', $result->getTokens()[14]->getContent());
        //...
    }
}