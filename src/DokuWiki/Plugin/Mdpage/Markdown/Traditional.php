<?php

namespace DokuWiki\Plugin\Mdpage\Markdown;

use DokuWiki\Plugin\Mdpage\MarkdownRendererTrait;

class Traditional extends \cebe\markdown\Markdown {
    use MarkdownRendererTrait;

    protected $renderer = null;
    protected $rendererContext = null;

    public function __construct($renderer, $rendererContext) {
        $this->renderer = $renderer;
        $this->rendererContext = $rendererContext;
    }
}
