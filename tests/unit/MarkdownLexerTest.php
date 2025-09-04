<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace unit;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use seretos\TCPDFMarkdown\MarkdownLexer;
use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginInterface;
use seretos\TCPDFMarkdown\PluginTokenResult;

class MarkdownLexerTest extends TestCase
{
    private MarkdownLexer $lexer;
    private MockObject|PluginInterface $mockPlugin1;
    private MockObject|PluginInterface $mockPlugin2;
    private MockObject|PluginInterface $mockPlugin3;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->lexer = new MarkdownLexer();

        $this->mockPlugin1 = $this->createMock(PluginInterface::class);
        $this->mockPlugin1->expects($this->any())->method('getPriority')->willReturn(1);
        $this->mockPlugin1->expects($this->any())->method('supports')->willReturnCallback(function($t){
            return $t === null;
        });

        $this->mockPlugin2 = $this->createMock(PluginInterface::class);
        $this->mockPlugin2->expects($this->any())->method('getPriority')->willReturn(2);
        $this->mockPlugin2->expects($this->any())->method('supports')->willReturnCallback(function($t){
            return $t === null;
        });

        $this->mockPlugin3 = $this->createMock(PluginInterface::class);
        $this->mockPlugin3->expects($this->any())->method('getPriority')->willReturn(3);
    }

    public function testEmptyParse() {
        $tokens = $this->lexer->parse('a');
        $this->assertCount(1, $tokens);
        $this->assertSame('CHAR', $tokens[0]->getType());
        $this->assertSame('a', $tokens[0]->getContent());
    }

    public function testParse() {
        $this->lexer->addPlugin($this->mockPlugin2);
        $this->lexer->addPlugin($this->mockPlugin1);
        $this->lexer->addPlugin($this->mockPlugin3);

        $order = [];

        $this->mockPlugin1->expects($this->exactly(2))->method('init') ->willReturnCallback(function () use (&$order) {
            if(!in_array('plugin1', $order))
                $order[] = 'plugin1';
        });
        $this->mockPlugin2->expects($this->exactly(2))->method('init')->willReturnCallback(function () use (&$order) {
            if(!in_array('plugin2', $order))
                $order[] = 'plugin2';
        });
        $this->mockPlugin3->expects($this->exactly(2))->method('init')->willReturnCallback(function () use (&$order) {
            if(!in_array('plugin3', $order))
                $order[] = 'plugin3';
        });

        $textToken1 = new MarkdownToken('TEXT', ' ');
        $textToken2 = new MarkdownToken('TEXT', 'a');
        $contentToken = new MarkdownToken('CONTENT', 'a', true);

        $this->mockPlugin3->expects($this->any())->method('supports')->willReturnCallback(function ($t) use ($contentToken){
            return $t === $contentToken;
        });

        $this->mockPlugin1->expects($this->any())->method('parse')->willReturnCallback(function ($str, $pos) use($textToken1) {
            if($pos === 0) return new PluginTokenResult([$textToken1],1);
            else return null;
        });

        $this->mockPlugin2->expects($this->once())->method('parse')->with(' a', 1)
            ->willReturn(new PluginTokenResult([$contentToken],2));
        $this->mockPlugin3->expects($this->once())->method('parse')->with('a',0)
            ->willReturn(new PluginTokenResult([$textToken2], 1));

        $resultTokens = $this->lexer->parse(' a');

        $this->assertSame([$textToken1, $textToken2], $resultTokens);
        $this->assertSame(['plugin1', 'plugin2', 'plugin3'], $order);
    }
}