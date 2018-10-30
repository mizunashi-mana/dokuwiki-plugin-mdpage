<?php

namespace DokuWiki\Plugin\Mdpage\Markdown;

class GitHubFlavored extends \cebe\markdown\GithubMarkdown {

    use MarkdownRendererTrait;

    protected $renderer = null;
    protected $rendererContext = null;

    public function __construct($renderer, $rendererContext) {
        $this->renderer = $renderer;
        $this->rendererContext = $rendererContext;
    }

}
