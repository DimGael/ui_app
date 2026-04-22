.PHONY: start encore symfonyserver stop cache

start:
	@$(MAKE) -j3 encore docker symfonyserver

encore:
	npm run watch

docker:
	docker compose up

symfonyserver:
	symfony server:start

stop:
	docker compose stop

cache:
	php bin/console c:c
