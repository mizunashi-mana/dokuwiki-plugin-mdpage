<?php

if(!defined('DOKU_INC')) die();

require_once __DIR__.'/src/bootstrap.php';

use DokuWiki\Plugin\Mdpage\Markdown;

class syntax_plugin_mdpage extends DokuWiki_Syntax_Plugin {

    public function getType() {
        return 'protected';
    }

    public function getPType() {
        return 'block';
    }

    public function getSort() {
        return 69;
    }

    public function getPluginName() {
        return $this->getInfo()['base'];
    }

    public function connectTo($mode) {
        $this->Lexer->addEntryPattern('<markdown>(?=.*</markdown>)', $mode, 'plugin_' . $this->getPluginName());
    }

    public function postConnect() {
        $this->Lexer->addExitPattern('</markdown>', 'plugin_' . $this->getPluginName());
    }

    public function handle($match, $state, $pos, Doku_Handler $handler) {
        switch ($state) {
            case DOKU_LEXER_UNMATCHED:
                return [
                    'render' => true,
                    'match' => $match,
                    'pos' => $pos - strlen('<markdown>'),
                ];
            default:
                return [
                    'render' => false,
                ];
        }
    }

    public function render($format, Doku_Renderer $renderer, $data) {
        if (!$data['render']) {
            return true;
        }

        $match = $data['match'];
        return $this->renderWithRenderer($renderer, $match, $data);
    }

    protected function renderWithRenderer(Doku_Renderer $renderer, $match, $data) {
        switch ($this->getConf('flavor')) {
            case 'github-flavored':
                $flavor = Markdown::GITHUB_FLAVORED;
                break;
            case 'markdown-extra':
                $flavor = Markdown::MARKDOWN_EXTRA;
                break;
            case 'traditional':
                $flavor = Markdown::TRADITIONAL;
                break;
            default:
                $flavor = Markdown::GITHUB_FLAVORED;
                break;
        }

        $result = Markdown::parseWithRenderer($renderer, $match, $flavor, $data);
        /*echo '<pre>';
        var_dump($match);
        var_dump(htmlspecialchars($result));
        echo '</pre>';*/

        return true;
    }

    protected function _debug($message, $err, $line, $file = __FILE__) {
        if ($this->getConf('debug')) {
            msg($message, $err, $line, $file);
        }
    }

}
