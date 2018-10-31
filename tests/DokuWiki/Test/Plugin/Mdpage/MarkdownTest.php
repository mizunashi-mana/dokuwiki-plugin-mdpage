<?php

namespace DokuWiki\Test\Plugin\Mdpage;

use DOMDocument;
use DokuWikiTest;
use Doku_Renderer_xhtml;
use DokuWiki\Plugin\Mdpage\Markdown;

class SyntaxPluginTest extends DokuWikiTest {
    protected $renderer;
    protected $markdowns;

    public function setup() {
        parent::setUp();
        $this->markdowns = [];
        $this->markdowns[0] = <<<MD
# Header

simple paragraph: *emph*, **strong** and `mono`.

<html>
<p>inline html</p>
</html>

> quote

```
code block
```

[link](https://www.dokuwiki.org)

![image](https://secure.php.net/images/php.gif)

* List
    - Sub item 1
    - Sub item 2
* Item 2
    - Sub item 1

1. Ordered List
    - Sub item 1
2. Item 2
    - Sub item 1
MD;
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

    private function assertXHTMLs($expected_xhtmls, $flavor) {
        for ($i = 0, $l = count($this->markdowns); $i < $l; ++$i) {
            $expected_xhtml = $expected_xhtmls[$i];
            $markdown = $this->markdowns[$i];

            $expected = new DOMDocument();
            $expected->loadHTML($expected_xhtml);

            $actual = new DOMDocument();
            $actual->loadHTML($this->renderMarkdownToXHTML($markdown, $flavor));

            $this->assertEquals($expected, $actual);
        }
    }

    public function testParseGFM() {
        $flavor = Markdown::GITHUB_FLAVORED;

        $rendered_xhtmls = [];
        $rendered_xhtmls[0] = <<<XHTML
<h1 class="sectionedit1" id="header">Header</h1>

<p>
simple paragraph: <em>emph</em>, <strong>strong</strong> and <code>mono</code>.
</p>
&lt;html&gt;
&lt;p&gt;inline html&lt;/p&gt;
&lt;/html&gt;
<blockquote><div class="no">

<p>
quote
</p>
</div></blockquote>
<pre class="code">code block</pre>

<p>
<a href="https://www.dokuwiki.org" class="urlextern" title="https://www.dokuwiki.org" rel="nofollow">link</a>
</p>

<p>
<a href="/./lib/exe/fetch.php?cache=&amp;tok=5895fa&amp;media=https%3A%2F%2Fsecure.php.net%2Fimages%2Fphp.gif" class="media" title="https://secure.php.net/images/php.gif"><img src="/./lib/exe/fetch.php?cache=&amp;tok=5895fa&amp;media=https%3A%2F%2Fsecure.php.net%2Fimages%2Fphp.gif" class="media" title="image" alt="image" /></a>
</p>
<ul>
<li class="level0"><div class="li">List<ul>
<li class="level1"><div class="li">Sub item 1</div>
</li>
<li class="level1"><div class="li">Sub item 2</div>
</li>
</ul>
</div>
</li>
<li class="level0"><div class="li">Item 2<ul>
<li class="level1"><div class="li">Sub item 1</div>
</li>
</ul>
</div>
</li>
</ul>
<ol>
<li class="level0"><div class="li">Ordered List<ul>
<li class="level1"><div class="li">Sub item 1</div>
</li>
</ul>
</div>
</li>
<li class="level0"><div class="li">Item 2<ul>
<li class="level1"><div class="li">Sub item 1</div>
</li>
</ul>
</div>
</li>
</ol>

XHTML;

        $this->assertXHTMLs($rendered_xhtmls, $flavor);
    }

    public function testParseTraditional() {
        $flavor = Markdown::TRADITIONAL;

        $rendered_xhtmls = [];
        $rendered_xhtmls[0] = <<<XHTML
<h1 class="sectionedit1" id="header">Header</h1>

<p>
simple paragraph: <em>emph</em>, <strong>strong</strong> and <code>mono</code>.
</p>
&lt;html&gt;
&lt;p&gt;inline html&lt;/p&gt;
&lt;/html&gt;
<blockquote><div class="no">

<p>
quote
</p>
</div></blockquote>

<p>
<code>code block</code>
</p>

<p>
<a href="https://www.dokuwiki.org" class="urlextern" title="https://www.dokuwiki.org" rel="nofollow">link</a>
</p>

<p>
<a href="/./lib/exe/fetch.php?cache=&amp;tok=5895fa&amp;media=https%3A%2F%2Fsecure.php.net%2Fimages%2Fphp.gif" class="media" title="https://secure.php.net/images/php.gif"><img src="/./lib/exe/fetch.php?cache=&amp;tok=5895fa&amp;media=https%3A%2F%2Fsecure.php.net%2Fimages%2Fphp.gif" class="media" title="image" alt="image" /></a>
</p>
<ul>
<li class="level0"><div class="li">List<ul>
<li class="level1"><div class="li">Sub item 1</div>
</li>
<li class="level1"><div class="li">Sub item 2</div>
</li>
</ul>
</div>
</li>
<li class="level0"><div class="li">Item 2<ul>
<li class="level1"><div class="li">Sub item 1</div>
</li>
</ul>
</div>
</li>
</ul>
<ol>
<li class="level0"><div class="li">Ordered List<ul>
<li class="level1"><div class="li">Sub item 1</div>
</li>
</ul>
</div>
</li>
<li class="level0"><div class="li">Item 2<ul>
<li class="level1"><div class="li">Sub item 1</div>
</li>
</ul>
</div>
</li>
</ol>

XHTML;

        $this->assertXHTMLs($rendered_xhtmls, $flavor);
    }

    public function testParseMarkdownExtra() {
        $flavor = Markdown::MARKDOWN_EXTRA;

        $rendered_xhtmls = [];
        $rendered_xhtmls[0] = <<<XHTML
<h1 class="sectionedit1" id="header">Header</h1>

<p>
simple paragraph: <em>emph</em>, <strong>strong</strong> and <code>mono</code>.
</p>
&lt;html&gt;
&lt;p&gt;inline html&lt;/p&gt;
&lt;/html&gt;
<blockquote><div class="no">

<p>
quote
</p>
</div></blockquote>
<pre class="code">code block</pre>

<p>
<a href="https://www.dokuwiki.org" class="urlextern" title="https://www.dokuwiki.org" rel="nofollow">link</a>
</p>

<p>
<a href="/./lib/exe/fetch.php?cache=&amp;tok=5895fa&amp;media=https%3A%2F%2Fsecure.php.net%2Fimages%2Fphp.gif" class="media" title="https://secure.php.net/images/php.gif"><img src="/./lib/exe/fetch.php?cache=&amp;tok=5895fa&amp;media=https%3A%2F%2Fsecure.php.net%2Fimages%2Fphp.gif" class="media" title="image" alt="image" /></a>
</p>
<ul>
<li class="level0"><div class="li">List<ul>
<li class="level1"><div class="li">Sub item 1</div>
</li>
<li class="level1"><div class="li">Sub item 2</div>
</li>
</ul>
</div>
</li>
<li class="level0"><div class="li">Item 2<ul>
<li class="level1"><div class="li">Sub item 1</div>
</li>
</ul>
</div>
</li>
</ul>
<ol>
<li class="level0"><div class="li">Ordered List<ul>
<li class="level1"><div class="li">Sub item 1</div>
</li>
</ul>
</div>
</li>
<li class="level0"><div class="li">Item 2<ul>
<li class="level1"><div class="li">Sub item 1</div>
</li>
</ul>
</div>
</li>
</ol>

XHTML;

        $this->assertXHTMLs($rendered_xhtmls, $flavor);
    }
}