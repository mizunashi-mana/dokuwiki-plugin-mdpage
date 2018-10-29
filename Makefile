build:
	composer install \
		--no-interaction \
		--optimize-autoloader \
		--no-dev --no-scripts --no-suggest
	./scripts/archive.bash
