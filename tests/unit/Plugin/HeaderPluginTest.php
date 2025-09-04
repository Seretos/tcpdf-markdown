<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit\Plugin;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\Plugin\HeaderPlugin;

class HeaderPluginTest extends TestCase
{
    private HeaderPlugin $plugin;

    protected function setUp(): void
    {
        $this->plugin = new HeaderPlugin(1);
    }

    public function testSupports() {
        $this->assertTrue($this->plugin->supports());
        $this->assertFalse($this->plugin->supports(new MarkdownToken('CONTENT')));
    }

    public function testParseNoResult() {
        $result = $this->plugin->parse("test\n# header",0);
        $this->assertNull($result);
    }

    public static function headerProvider(): array {
        return [
            'h1' => ['#', 13, '1'],
            'h2' => ['##', 14, '2'],
            'h3' => ['###', 15, '3'],
            'h4' => ['####', 16, '4'],
            'h5' => ['#####', 17, '5'],
            'h6' => ['######', 18, '6'],
        ];
    }

    #[DataProvider("headerProvider")]
    public function testParseHeader(string $r = '#', int $pos = 13, string $lvl = '1') {
        $result = $this->plugin->parse("test\n" . $r . " header",5);
        $this->assertNotNull($result);
        $this->assertCount(3, $result->getTokens());
        $this->assertEquals($pos, $result->getPosition());
        $this->assertEquals('HEADER_START', $result->getTokens()[0]->getType());
        $this->assertEquals($lvl, $result->getTokens()[0]->getContent());
        $this->assertEquals('CONTENT', $result->getTokens()[1]->getType());
        $this->assertEquals('header', $result->getTokens()[1]->getContent());
        $this->assertEquals('HEADER_END', $result->getTokens()[2]->getType());
    }
}