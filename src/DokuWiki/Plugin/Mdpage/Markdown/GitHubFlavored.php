<?php

namespace DokuWiki\Plugin\Mdpage\Markdown;

use DokuWiki\Plugin\Mdpage\MarkdownRendererTrait;

class GitHubFlavored extends \cebe\markdown\GithubMarkdown {
    use MarkdownRendererTrait;

    protected $renderer = null;
    protected $rendererData = null;
    protected $rendererContext = null;

    public function __construct($renderer, $data, $context) {
        $this->renderer = $renderer;
        $this->rendererData = $data;
        $this->rendererContext = $context;
    }

    protected function getDokuWikiVersion() {
        return $rendererContext['dokuwiki_version'];
    }
}
