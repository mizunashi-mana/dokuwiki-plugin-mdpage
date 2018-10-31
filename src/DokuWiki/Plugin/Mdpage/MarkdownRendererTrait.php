<?php

namespace DokuWiki\Plugin\Mdpage;

trait MarkdownRendererTrait {
    private $isParsed = false;
    private $renderPos = 0;
    private $listLevel = 0;

    abstract protected function renderAbsy($blocks);

    abstract protected function parse($content);

    public function parseOnce($content) {
        if ($this->isParsed) {
            return false;
        }

        $this->isParsed = true;
        $this->renderPos = strlen($this->renderer->doc);

        return $this->parse($content);
    }

    private function getRenderResult($escapedPos = null) {
        if ($escapedPos === null) {
            $renderPos = $this->renderPos;
        } else {
            $renderPos = $escapedPos;
        }

        $result = substr($this->renderer->doc, $renderPos);
        $this->renderPos = strlen($this->renderer->doc);

        return $result;
    }

    protected function collectText($blocks) {
        $result = '';

        foreach ($blocks as $block) {
            if ($block[0] == 'text') {
                $result .= $block[1];
            }
        }

        return $result;
    }

    // Parser

    protected function renderParagraph($block) {
        $escapedPos = $this->renderPos;

        $this->renderer->p_open();
        $this->renderAbsy($block['content']);
        $this->renderer->p_close();

        return $this->getRenderResult($escapedPos);
    }

    // Markdown

    protected function renderText($block) {
        $this->renderer->cdata($block[1]);

        return $this->getRenderResult();
    }

    // block\CodeTrait

    protected function renderCode($block) {
        $lang = null;
        if (array_key_exists('language', $block)) {
            $lang = $block['language'];
        }

        $this->renderer->code($block['content'], $lang);

        return $this->getRenderResult();
    }

    // block\HeadlineTrait

    protected function renderHeadline($block) {
        $content = $this->collectText($block['content']);

        $this->renderer->header($content, $block['level'], $this->rendererContext['pos']);

        return $this->getRenderResult();
    }

    // block\HtmlTrait

    protected function renderHtml($block) {
        $content = $block['content']."\n";

        // FIXME: support HTML render mode
        $this->renderer->cdata($content);

        return $this->getRenderResult();
    }

    protected function renderInlineHtml($block) {
        // FIXME: support HTML render mode
        $this->renderer->cdata($block[1]);

        return $this->getRenderResult();
    }

    // block\ListTrait

    protected function renderList($block) {
        $escapedPos = $this->renderPos;

        if ($block['list'] == 'ol') {
            $this->renderer->listo_open();
        } else {
            $this->renderer->listu_open();
        }

        foreach ($block['items'] as $item => $itemLines) {
            $this->renderer->listitem_open($this->listLevel);
            $this->listLevel = $this->listLevel + 1;

            $this->renderer->listcontent_open();
            $this->renderAbsy($itemLines);
            $this->renderer->listcontent_close();

            $this->listLevel = $this->listLevel - 1;
            $this->renderer->listitem_close();
        }

        if ($block['list'] == 'ol') {
            $this->renderer->listo_close();
        } else {
            $this->renderer->listu_close();
        }

        return $this->getRenderResult($escapedPos);
    }

    // block\QuoteTrait

    protected function renderQuote($block) {
        $escapedPos = $this->renderPos;

        $this->renderer->quote_open();
        $this->renderAbsy($block['content']);
        $this->renderer->quote_close();

        return $this->getRenderResult($escapedPos);
    }

    // block\RuleTrait

    protected function renderHr($block) {
        $this->renderer->hr();

        return $this->getRenderResult();
    }

    // block\TableTrait

    protected function renderTable($block) {
        $escapedPos = $this->renderPos;

        $this->renderer->table_open();

        $cols = $block['cols'];
        $first = true;
        foreach ($block['rows'] as $row) {
            if ($first) {
                $first = false;

                $this->renderer->tablethead_open();
                foreach ($row as $c => $cell) {
                    $align = empty($cols[$c]) ? null : $cols[$c];
                    $this->renderer->tableheader_open(1, $align);
                    $this->renderAbsy($cell);
                    $this->renderer->tableheader_close();
                }
                $this->renderer->tablethead_close();

                continue;
            }

            $this->renderer->tablerow_open();
            foreach ($row as $c => $cell) {
                $align = empty($cols[$c]) ? null : $cols[$c];
                $this->renderer->tablecell_open(1, $align);
                $this->renderAbsy($cell);
                $this->renderer->tablecell_close();
            }
            $this->renderer->tablerow_close();
        }

        $this->renderer->table_close();

        return $this->getRenderResult($escapedPos);
    }

    // inline\CodeTrait

    protected function renderInlineCode($block) {
        $this->renderer->monospace_open();
        $this->renderer->cdata($block[1]);
        $this->renderer->monospace_close();

        return $this->getRenderResult();
    }

    // inline\EmphStrongTrait

    protected function renderStrong($block) {
        $escapedPos = $this->renderPos;

        $this->renderer->strong_open();
        $this->renderAbsy($block[1]);
        $this->renderer->strong_close();

        return $this->getRenderResult($escapedPos);
    }

    protected function renderEmph($block) {
        $escapedPos = $this->renderPos;

        $this->renderer->emphasis_open();
        $this->renderAbsy($block[1]);
        $this->renderer->emphasis_close();

        return $this->getRenderResult($escapedPos);
    }

    // inline\LinkTrait

    protected function renderEmail($block) {
        $this->renderer->emaillink($block[1]);

        return $this->getRenderResult();
    }

    protected function renderUrl($block) {
        $this->renderer->externallink($block[1]);

        return $this->getRenderResult();
    }

    abstract protected function lookupReference($key);

    protected function renderLink($block) {
        $escapedPos = $this->renderPos;

        if (isset($block['refkey'])) {
            if (($ref = $this->lookupReference($block['refkey'])) !== false) {
                $block = array_merge($block, $ref);
            } else {
                if (strncmp($block['orig'], '[', 1) === 0) {
                    $this->renderer->cdata('[');
                    $this->renderAbsy($this->parseInline(substr($block['orig'], 1)));
                } else {
                    $this->renderer->cdata($block['orig']);
                }

                return $this->getRenderResult($escapedPos);
            }
        }

        $url = $block['url'];
        if (strpos($url, '/') === false) {
            $this->renderer->internallink($url, $this->collectText($block['text']));
        } elseif (empty($block['title'])) {
            $this->renderer->externallink($url, $this->collectText($block['text']));
        } else {
            $this->renderer->footnote_open();
            $this->renderAbsy($block['text']);
            $this->renderer->footnote_close();
        }

        return $this->getRenderResult($escapedPos);
    }

    protected function renderImage($block) {
        $escapedPos = $this->renderPos;

        if (isset($block['refkey'])) {
            if (($ref = $this->lookupReference($block['refkey'])) !== false) {
                $block = array_merge($block, $ref);
            } else {
                if (strncmp($block['orig'], '![', 2) === 0) {
                    $this->renderer->cdata('![');
                    $this->renderAbsy($this->parseInline(substr($block['orig'], 2)));
                } else {
                    $this->renderer->cdata($block['orig']);
                }

                return $this->getRenderResult($escapedPos);
            }
        }

        $url = $block['url'];
        if (strpos($url, '/') === false) {
            $this->renderer->internalmedia($url, $block['text']);
        } else {
            $this->renderer->externalmedia($url, $block['text']);
        }

        return $this->getRenderResult($escapedPos);
    }

    // inline\StrikeoutTrait

    protected function renderStrike($block) {
        $escapedPos = $this->renderPos;

        $this->renderer->deleted_open();
        $this->renderAbsy($block[1]);
        $this->renderer->deleted_close();

        return $this->getRenderResult($escapedPos);
    }

    // inline\UrlLinkTrait

    protected function renderAutoUrl($block) {
        $this->renderer->externallink($block[1]);

        return $this->getRenderResult();
    }
}
