.PHONY: build
build:
	composer install \
		--no-interaction \
		--optimize-autoloader \
		--no-dev --no-scripts --no-suggest
	./scripts/archive.bash

.PHONY: setup-dev
setup-dev:
	composer install --prefer-source

.PHONY: test
test:
	composer test

.PHONY: coverage
coverage:
	composer test -- \
		--coverage-clover coverage/clover.xml \
		--coverage-html coverage/html
