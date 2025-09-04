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
use seretos\TCPDFMarkdown\Plugin\DividerPlugin;

class DividerPluginTest extends TestCase
{
    public function testSupports() {
        $plugin = new DividerPlugin(1);
        $this->assertTrue($plugin->supports());
        $this->assertFalse($plugin->supports(new MarkdownToken('CONTENT')));
    }

    public function testParseNoResult() {
        $plugin = new DividerPlugin(1);
        $result = $plugin->parse("test\n\n---",0);
        $this->assertNull($result);
    }

    public static function dividerProvider(): array {
        return [
            '---' => ['-','---',9],
            '***' => ['*','***',9],
            '___' => ['_','___',9],
        ];
    }

    #[DataProvider("dividerProvider")]
    public function testParseResult(string $divider = '-', string $usedDivider = '---', int $pos = 9) {
        $plugin = new DividerPlugin(1,$divider);
        $result = $plugin->parse("test\n\n" . $usedDivider,6);
        $this->assertNotNull($result);
        $this->assertCount(1, $result->getTokens());
        $this->assertEquals($pos, $result->getPosition());
        $this->assertEquals('DIVIDER', $result->getTokens()[0]->getType());
    }
}