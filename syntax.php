<?php

if(!defined('DOKU_INC')) die();

require_once __DIR__.'/src/bootstrap.php';

use DokuWiki\Plugin\Mdpage\Markdown;

class syntax_plugin_mdpage extends DokuWiki_Syntax_Plugin {
    protected $dokuwikiVersion = null;

    private function getDokuWikiVersion() {
        if ($this->dokuwikiVersion == null) {
            $this->dokuwikiVersion = getVersionData()['date'];
        }

        return $this->dokuwikiVersion;
    }

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

    public function getPluginMode() {
        return 'plugin_' . $this->getPluginName();
    }

    public function connectTo($mode) {
        if ($this->getConf('markdown_default')) {
            $this->Lexer->addEntryPattern('\\A(?!.*</script>).', $mode, $this->getPluginMode());
            $this->Lexer->addEntryPattern('<!DOCTYPE markdown>', $mode, $this->getPluginMode());
            $this->Lexer->addEntryPattern('</script>', $mode, $this->getPluginMode());
        } else {
            $this->Lexer->addEntryPattern('<markdown>(?=.*</markdown>)', $mode, $this->getPluginMode());
        }
    }

    public function postConnect() {
        $pluginBaseMode = 'plugin_' . $this->getPluginName();

        if ($this->getConf('markdown_default')) {
            $this->Lexer->addExitPattern('\\z', $this->getPluginMode());
            $this->Lexer->addExitPattern('<script type="text/x-dokuwiki">', $this->getPluginMode());
        } else {
            $this->Lexer->addExitPattern('</markdown>', $pluginBaseMode);
        }
    }

    public function handle($match, $state, $pos, Doku_Handler $handler) {
        switch ($state) {
            case DOKU_LEXER_UNMATCHED:
                $new_pos = $pos;
                if ($this->getConf('markdown_default')) {
                    if (preg_match('|^</script>|', $match)) {
                        $new_pos = $new_pos - strlen('</script>');
                    } else if (preg_match('|^<!DOCTYPE markdown>|', $match)) {
                        $new_pos = $new_pos - strlen('<!DOCTYPE markdown>');
                    }
                } else {
                    $new_pos = $new_pos - strlen('<markdown>');
                }

                return [
                    'render' => true,
                    'match' => $match,
                    'pos' => $new_pos,
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

        $context = [
            'dokuwiki_version' => $this->dokuwikiVersion,
            'flavor' => $flavor,
        ];

        $result = Markdown::parseWithRenderer($renderer, $match, $data, $context);
        /*
        echo '<pre>';
        var_dump(htmlspecialchars($match));
        var_dump(htmlspecialchars($result));
        echo '</pre>';
        */

        return true;
    }

    protected function _debug($message, $err, $line, $file = __FILE__) {
        if ($this->getConf('debug')) {
            msg($message, $err, $line, $file);
        }
    }

}
