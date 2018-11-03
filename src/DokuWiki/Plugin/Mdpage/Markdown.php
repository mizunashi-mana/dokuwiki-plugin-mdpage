<?php

namespace DokuWiki\Plugin\Mdpage;

class Markdown {
    const GITHUB_FLAVORED = 'GFM';
    const MARKDOWN_EXTRA = 'MarkdownExtra';
    const TRADITIONAL = 'Traditional';

    public static function parseWithRenderer(
        $renderer,
        $content,
        $flavor,
        $context
    ) {
        switch ($flavor) {
            case self::GITHUB_FLAVORED:
                $parser = new Markdown\GitHubFlavored($renderer, $context);
                break;
            case self::MARKDOWN_EXTRA:
                $parser = new Markdown\MarkdownExtra($renderer, $context);
                break;
            default:
                $parser = new Markdown\Traditional($renderer, $context);
                break;
        }

        return $parser->parseOnce($content);
    }
}
