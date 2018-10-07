<?php

if(!defined('DOKU_INC')) die();

require __DIR__.'/src/bootstrap.php';

class syntax_plugin_mdpage extends DokuWiki_Syntax_Plugin {

    function getType() {
        return 'protected';
    }

    function getPType() {
        return 'block';
    }

    function getSort(){
        return 69;
    }

    function connectTo($mode) {
        $this->Lexer->addEntryPattern('<markdown>(?=.*</markdown>)', $mode, 'plugin_mdpage');
    }

    function postConnect() {
        $this->Lexer->addExitPattern('</markdown>', 'plugin_mdpage');
    }

    function handle($match, $state, $pos, Doku_Handler $handler) {
        switch ($state) {
            case DOKU_LEXER_ENTER:
                return array($state, '');
            case DOKU_LEXER_EXIT:
                return array($state, '');
            case DOKU_LEXER_UNMATCHED:
                return array($state, Markdown($match));
        }
        return array($state, '');
    }

    function render($format, Doku_Renderer $renderer, $data) {
    }

    function _initRender($mode, &$renderer) {
        $rc = false;
        $this->rdr         =& $renderer;
        $this->rdrMode     = $mode;
    }

}
