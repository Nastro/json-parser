test:
	./vendor/bin/phpcs src/ --standard=PSR12 && ./vendor/bin/phpunit

fix:
	./vendor/bin/php-cs-fixer fix src/

install:
	composer install

.PHONY: test fix install
