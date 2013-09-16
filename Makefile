cs:
	phpcs --ignore=/vendor/*,/Tests/Resources/app/* --extensions=php --encoding=utf-8 --standard=PSR2 -np .

server:
	vendor/symfony-cmf/testing/bin/server

test:
	phpunit -c .

coverage:
	phpunit --coverage-text