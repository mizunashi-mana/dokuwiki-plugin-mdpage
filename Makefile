build:
	composer install --no-dev --no-scripts --no-suggest
	./scripts/archive.bash
