<?php

namespace DokuWiki\Plugin\Mdpage\Markdown;

use DokuWiki\Plugin\Mdpage\MarkdownRendererTrait;

class GitHubFlavored extends \cebe\markdown\GithubMarkdown {
    use \Kirra\Markdown\TaskListsTrait, MarkdownRendererTrait {
        MarkdownRendererTrait::renderCheckbox insteadof \Kirra\Markdown\TaskListsTrait;
    }

    protected $renderer = null;
    protected $rendererContext = null;

    public function __construct($renderer, $rendererContext) {
        $this->renderer = $renderer;
        $this->rendererContext = $rendererContext;
    }
}
