<?php

namespace DokuWiki\Plugin\Mdpage\Markdown;

class MarkdownExtra extends \cebe\markdown\MarkdownExtra {

    use MarkdownRendererTrait;

    protected $renderer = null;
    protected $rendererContext = null;

    public function __construct($renderer, $rendererContext) {
        $this->renderer = $renderer;
        $this->rendererContext = $rendererContext;
    }

}
