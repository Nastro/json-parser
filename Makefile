test:
	./vendor/bin/phpcs src/ --standard=PSR12 \
	&& ./vendor/bin/phpunit --coverage-clover=.build/logs/coverage.xml --whitelist=src/

fix:
	./vendor/bin/php-cs-fixer fix src/

install:
	composer install

.PHONY: test fix install
