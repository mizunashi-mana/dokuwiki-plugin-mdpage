<?php

namespace DokuWiki\Plugin\Mdpage\Markdown;

use DokuWiki\Plugin\Mdpage\MarkdownRendererTrait;

class Common extends \cebe\markdown\Markdown {

    use MarkdownRendererTrait;

    protected $renderer = null;
    protected $context = null;

    public function __construct($renderer, $context) {
        $this->renderer = $renderer;
        $this->context = $context;
    }

}
