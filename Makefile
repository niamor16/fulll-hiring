.PHONY: build up sh

build:
	docker compose build

up:
	docker compose up -d

backend-sh:
	docker exec -it backend-php sh

test-backend:
	docker compose run --rm backend-php vendor/behat/behat/bin/behat