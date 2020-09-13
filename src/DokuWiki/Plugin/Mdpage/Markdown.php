<?php

namespace DokuWiki\Plugin\Mdpage;

class Markdown {
    const GITHUB_FLAVORED = 'GFM';
    const MARKDOWN_EXTRA = 'MarkdownExtra';
    const TRADITIONAL = 'Traditional';

    public static function parseWithRenderer(
        $renderer,
        $content,
        $data,
        $context
    ) {
        switch ($context['flavor']) {
            case self::GITHUB_FLAVORED:
                $parser = new Markdown\GitHubFlavored($renderer, $data, $context);
                break;
            case self::MARKDOWN_EXTRA:
                $parser = new Markdown\MarkdownExtra($renderer, $data, $context);
                break;
            default:
                $parser = new Markdown\Traditional($renderer, $data, $context);
                break;
        }

        return $parser->parseOnce($content);
    }
}
