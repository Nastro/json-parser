test:
	./vendor/bin/phpcs src/ --standard=PSR12 && ./vendor/bin/phpunit

.PHONY: test
