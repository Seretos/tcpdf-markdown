<?php
declare(strict_types=1);
/**
 * author:  aappen
 * project: tcpdf-markdown
 */

namespace seretos\TCPDFMarkdown\Plugin;

use seretos\TCPDFMarkdown\MarkdownToken;
use seretos\TCPDFMarkdown\PluginTokenResult;

class TablePlugin extends AbstractPlugin
{

    public function parse(string $markdown, int $position): ?PluginTokenResult
    {
        [$sol, $indentPos] = $this->lineStartAndIndent($markdown, $position);
        if ($position > $indentPos) {
            return null;
        }

        $slice = substr($markdown, $position);
        if (preg_match('/^(?:[ \t]*\r?\n)*(?:[ \t]*\|.*\|[ \t]*\r?\n?)+/', $slice, $m)) {
            $content = trim($m[0]);
            $newPos  = $position + strlen($m[0]);

            $rows = explode("\n", $content);

            if(count($rows) > 1 && preg_match('/^[ \t]*\|[ \t]*[-:| \t]+[ \t]*\|?[ \t]*$/', $rows[1])) {
                $columnAlignments = $this->getColumnAlignments($rows[1]);

                $tokens = [new MarkdownToken('TABLE_START'), new MarkdownToken('TABLE_HEADER_ROW_START')];

                $columns = array_map('trim', explode('|', trim($rows[0], '|')));
                for ($i=0;$i<count($columns);$i++) {
                    $tokens[] = new MarkdownToken('TABLE_HEADER_COL_START', $columnAlignments[$i] ?? 'left');
                    $tokens[] = new MarkdownToken('CONTENT', $columns[$i], true);
                    $tokens[] = new MarkdownToken('TABLE_HEADER_COL_END');
                }

                $tokens[] = new MarkdownToken('TABLE_HEADER_ROW_END');

                for ($i = 2; $i<count($rows);$i++) {
                    $tokens[] = new MarkdownToken('TABLE_ROW_START');

                    $columns = array_map('trim', explode('|', trim($rows[$i], '|')));
                    for ($y=0;$y<count($columns);$y++) {
                        $tokens[] = new MarkdownToken('TABLE_COL_START', $columnAlignments[$y] ?? 'left');
                        $tokens[] = new MarkdownToken('CONTENT', $columns[$y], true);
                        $tokens[] = new MarkdownToken('TABLE_COL_END');
                    }

                    $tokens[] = new MarkdownToken('TABLE_ROW_END');
                }

                $tokens[] = new MarkdownToken('TABLE_END');

                return new PluginTokenResult($tokens, $newPos);
            }
        }

        return null;
    }

    private function getColumnAlignments(string $row): array {
        $cols = array_map('trim', explode('|', trim($row, '|')));

        return array_map(function($col) {
            if (preg_match('/^:\-+:\s*$/', $col)) {
                return 'center';
            } elseif (preg_match('/^:\-+\s*$/', $col)) {
                return 'left';
            } elseif (preg_match('/^\-+:\s*$/', $col)) {
                return 'right';
            }
            return 'left';
        }, $cols);
    }
}