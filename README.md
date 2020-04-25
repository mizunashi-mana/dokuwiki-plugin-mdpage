# Markdown plugin for DokuWiki

[![CircleCI](https://circleci.com/gh/mizunashi-mana/dokuwiki-plugin-mdpage/tree/master.svg?style=svg)](https://circleci.com/gh/mizunashi-mana/dokuwiki-plugin-mdpage/tree/master)
[![Maintainability](https://api.codeclimate.com/v1/badges/b43a73f03fca36f12742/maintainability)](https://codeclimate.com/github/mizunashi-mana/dokuwiki-plugin-mdpage/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/b43a73f03fca36f12742/test_coverage)](https://codeclimate.com/github/mizunashi-mana/dokuwiki-plugin-mdpage/test_coverage)

## Installation / Usage

See <https://www.dokuwiki.org/plugin:mdpage>.

## Report a bug / Contribute a fix

See [Contributing Guide](https://github.com/mizunashi-mana/dokuwiki-plugin-mdpage/blob/master/CONTRIBUTING.md).

## Release flow

1. Bump the date of `plugin.info.txt`
2. Run `./scripts/push-release-tag.bash [VERSION]`
3. Fix `lastupdate` on https://www.dokuwiki.org/plugin:mdpage
4. Wait deploying and update the release note for new version

## License

This work is dual-licensed under [the Apache 2.0 License](https://github.com/mizunashi-mana/dokuwiki-plugin-mdpage/blob/master/LICENSE.Apache-2.0) and [the GPL 2.0 (or any later version)](https://github.com/mizunashi-mana/dokuwiki-plugin-mdpage/blob/master/LICENSE.GPL-2.0-or-later).
You are free to pick which one suits your needs.

* Using [cebe/markdown](https://github.com/cebe/markdown) to parse Markdown texts under [the MIT License](https://github.com/cebe/markdown/blob/master/LICENSE).

Notice: DokuWiki is licensed under [the GPL 2.0](https://github.com/splitbrain/dokuwiki/blob/master/COPYING).  This license cannot be compatible with the Apache 2.0 License and the GPL 3.0.  See also: https://www.gnu.org/licenses/license-list.html .
