<?php

namespace DokuWiki\Test\Plugin\Mdpage;

use Doku_Renderer_xhtml;
use Doku_Renderer_metadata;
use DokuWiki\Plugin\Mdpage\Markdown;
use DokuWikiTest;
use DOMDocument;

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

    public function testHtmlok() {
        global $conf;
        $conf['htmlok'] = 1;

        $this->assertSpec(
            'htmlok/Content',
            $this->defaultFlavors
        );
    }

    public function testWithoutHtmlok() {
        $this->assertSpec(
            'without-htmlok/Content',
            $this->defaultFlavors
        );
    }

    public function testDokuwikiInternal() {
        $this->assertSpec(
            'dokuwiki-internal/Content',
            $this->defaultFlavors
        );
    }

    public function testCannotLookupRef() {
        $this->assertSpec(
            'cannot-lookup-ref/Content',
            $this->defaultFlavors
        );
    }

    public function testIssue24() {
        $this->assertSpec(
            'issue-24/Content',
            $this->defaultFlavors
        );
    }

    public function testIssue35() {
        $this->assertSpec(
            'issue-35/Content',
            $this->defaultFlavors
        );
    }

    public function testIssue40() {
        $this->assertSpec(
            'issue-40/Content',
            $this->defaultFlavors
        );
    }

    public function testIssue50() {
        $this->assertSpec(
            'issue-50/Content',
            $this->defaultFlavors
        );
    }

    public function testMetadata() {
        $this->assertMetaSpec(
            'metadata/Content',
            [
                Markdown::GITHUB_FLAVORED,
            ]
        );
    }

    private function getDokuWikiVersion() {
        return getVersionData()['date'];
    }

    private function renderMarkdownToXHTML($text, $flavor) {
        $renderer = new Doku_Renderer_xhtml();
        $data = [
            'pos' => 0,
        ];
        $context = [
            'dokuwiki_version' => $this->getDokuWikiVersion(),
            'flavor' => $flavor,
        ];

        $result = Markdown::parseWithRenderer($renderer, $text, $data, $context);
        $this->assertEquals($renderer->doc, $result);

        return $result;
    }

    private function renderMarkdownToMetadata($text, $flavor) {
        $renderer = new Doku_Renderer_metadata();
        $data = [
            'pos' => 0,
        ];
        $context = [
            'dokuwiki_version' => $this->getDokuWikiVersion(),
            'flavor' => $flavor,
        ];

        $result = Markdown::parseWithRenderer($renderer, $text, $data, $context);
        $this->assertEquals($renderer->doc, $result);

        return $result;
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

    private function assertMetadata($target_md, $expected_metadata, $flavor) {
        $expected = $expected_metadata;

        $actual = $this->renderMarkdownToMetadata($target_md, $flavor);

        $this->assertEquals(
            $expected,
            $actual,
            'Failed asserting markdown and Metadata for '.$flavor
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

    private function assertMetaSpec($basename, $flavors) {
        $basepath = $this->specsDirPath.'/'.$basename;

        $content_md = file_get_contents($basepath.'.md');
        foreach ($flavors as $flavor) {
            $content_xhtml = file_get_contents($basepath.'_'.$flavor.'.meta.txt');
            $this->assertMetadata($content_md, $content_xhtml, $flavor);
        }
    }
}
