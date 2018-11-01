# Contribution Guide

Read the section for your purpose.

## Report a bug

1. Check FAQ on https://www.dokuwiki.org/plugin:mdpage#faq
2. Check known issues on https://github.com/mizunashi-mana/dokuwiki-plugin-mdpage/issues
3. [Open an issue](https://github.com/mizunashi-mana/dokuwiki-plugin-mdpage/issues/new) according to the template

## Contribute a fix

1. `composer install --prefer-source --dev`
2. `./bin/php-cs-fixer fix`
3. `composer test`
4. `git commit` with message
5. Open a pull request and fix any failing tests

NOTICE:

This plugin includes source codes of dependent libraries.
When we add a new library to depend, we should check license compatibles.
