<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown;

class MarkdownLexer
{
    /**
     * @var PluginInterface[]
     */
    private array $plugins = [];

    public function addPlugin(PluginInterface $plugin): static
    {
        $this->plugins[] = $plugin;
        usort($this->plugins, fn(PluginInterface $a, PluginInterface $b) => $a->getPriority() <=> $b->getPriority());
        return $this;
    }

    /**
     * @return MarkdownToken[]
     */
    public function parse(string $markdown, ?MarkdownToken $type = null): array
    {
        $tokens = [];
        $pos = 0;

        foreach ($this->plugins as $plugin) {
            $plugin->init($markdown);
        }

        while ($pos < strlen($markdown)) {
            //$bestMatch = null;
            $bestResult = null;

            foreach ($this->plugins as $plugin) {
                if(!$plugin->supports($type))
                    continue;

                $bestResult = $plugin->parse($markdown, $pos);
                if($bestResult !== null)
                    break;
            }

            if ($bestResult !== null) {
                foreach ($bestResult->getTokens() as $token) {
                    if ($token->isParseable())
                        $tokens = array_merge($tokens, $this->parse($token->getContent(), $token));
                    else
                        $tokens[] = $token;
                }
                $pos = $bestResult->getPosition();
            } else {
                $tokens[] = new MarkdownToken('CHAR', $markdown[$pos]);
                $pos++;
            }
        }

        return $tokens;
    }
}