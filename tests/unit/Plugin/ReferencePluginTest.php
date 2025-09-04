<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit\Plugin;

use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\Plugin\ReferencePlugin;

class ReferencePluginTest extends TestCase
{
    private ReferencePlugin $plugin;

    protected function setUp(): void
    {
        $this->plugin = new ReferencePlugin(1);
        $this->plugin->init("[reference_1]: https://www.google.com\n[reference_2]: https://www.youtube.com\n[reference_3]: https://www.youtube.com/test.png");
    }

    public function testNoResult() {
        $result = $this->plugin->parse("test:\n[reference_3]: https://example.com/image.png\n[link][reference_1]\n![img][reference_3]",0);
        $this->assertNull($result);
    }

    public function testReference() {
        $result = $this->plugin->parse("test:\n[reference_3]: https://example.com/image.png\n[link][reference_1]\n![img][reference_3]",6);
        $this->assertNotNull($result);
        $this->assertCount(0, $result->getTokens());
        $this->assertEquals(51, $result->getPosition());
    }

    public function testLink() {
        $result = $this->plugin->parse("test:\n[reference_3]: https://example.com/image.png\n[link][reference_1]\n![img][reference_3]",51);
        $this->assertNotNull($result);
        $this->assertCount(3, $result->getTokens());
        $this->assertEquals(70, $result->getPosition());
        $this->assertEquals('LINK_START', $result->getTokens()[0]->getType());
        $this->assertEquals('https://www.google.com', $result->getTokens()[0]->getContent());
        $this->assertEquals('LINK_CONTENT', $result->getTokens()[1]->getType());
        $this->assertEquals('link', $result->getTokens()[1]->getContent());
        $this->assertEquals('LINK_END', $result->getTokens()[2]->getType());
    }

    public function testImage() {
        $result = $this->plugin->parse("test:\n[reference_3]: https://example.com/image.png\n[link][reference_1]\n![img][reference_3]",71);
        $this->assertNotNull($result);
        $this->assertCount(1, $result->getTokens());
        $this->assertEquals(90, $result->getPosition());
        $this->assertEquals('IMAGE', $result->getTokens()[0]->getType());
        $this->assertEquals('https://www.youtube.com/test.png', $result->getTokens()[0]->getContent());
    }
}