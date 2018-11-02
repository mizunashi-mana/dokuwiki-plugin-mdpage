<?php

namespace DokuWiki\Test\Plugin\Mdpage;

use DOMDocument;
use DokuWikiTest;
use Doku_Renderer_xhtml;
use DokuWiki\Plugin\Mdpage\Markdown;

class MarkdownSpecTest extends DokuWikiTest {
    protected $renderer;
    protected $specsDirPath;
    protected $defaultFlavors;

    public function setup() {
        parent::setUp();
        $this->specsDirPath = PROJECT_ROOT_DIR_PATH.'/tests/specs';
        $this->defaultFlavors = [
            Markdown::GITHUB_FLAVORED,
            Markdown::MARKDOWN_EXTRA,
            Markdown::TRADITIONAL,
        ];
    }

    public function testFeatures() {
        $this->assertSpec(
            'features/Content',
            $this->defaultFlavors
        );
    }

    public function testIssue24() {
        $this->assertSpec(
            'issue-24/Content',
            $this->defaultFlavors
        );
    }

    private function renderMarkdownToXHTML($text, $flavor) {
        $renderer = new Doku_Renderer_xhtml();
        $data = [
            'pos' => 0,
        ];

        $result = Markdown::parseWithRenderer($renderer, $text, $flavor, $data);
        $this->assertEquals($renderer->doc, $result);

        return $renderer->doc;
    }

    private function assertXHTML($target_md, $expected_xhtml, $flavor) {
        $expected = new DOMDocument();
        $expected->loadHTML($expected_xhtml);

        $actual = new DOMDocument();
        $actual->loadHTML($this->renderMarkdownToXHTML($target_md, $flavor));

        $this->assertEquals(
            $expected,
            $actual,
            'Failed asserting markdown and XHTML for '.$flavor
        );
    }

    private function assertSpec($basename, $flavors) {
        $basepath = $this->specsDirPath.'/'.$basename;

        $content_md = file_get_contents($basepath.'.md');
        foreach ($flavors as $flavor) {
            $content_xhtml = file_get_contents($basepath.'_'.$flavor.'.html');
            $this->assertXHTML($content_md, $content_xhtml, $flavor);
        }
    }
}
