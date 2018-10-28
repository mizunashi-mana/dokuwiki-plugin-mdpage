<?php

namespace DokuWiki\Plugin\Mdpage\Markdown;

class MarkdownExtra extends \cebe\markdown\MarkdownExtra {

    use MarkdownRendererTrait;

    protected $renderer = null;
    protected $context = null;

    public function __construct($renderer, $context) {
        $this->renderer = $renderer;
        $this->context = $context;
    }

}
