<?php

namespace DokuWiki\Plugin\Mdpage\Markdown;

class GitHubFlavored extends \cebe\markdown\GithubMarkdown {

    use MarkdownRendererTrait;

    protected $renderer = null;
    protected $context = null;

    public function __construct($renderer, $context) {
        $this->renderer = $renderer;
        $this->context = $context;
    }

}
