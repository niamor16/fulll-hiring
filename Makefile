.PHONY: build up backend-sh test-backend

build:
	docker compose build

up:
	docker compose up -d

down:
	docker compose down -v

backend:
	docker exec -it backend-php bash

db:
	docker exec -it backend-db bash

test-backend:
	docker compose run --rm backend-php vendor/behat/behat/bin/behat

create-fleet:
	docker compose run --rm backend-php bin/fleet create

php-stan:
	docker compose run --rm backend-php vendor/bin/phpstan analyse src

fizzbuzz_function:
	 docker compose run --rm algo-php php fizzbuzz_fn.php

fizzbuzz_class:
	 docker compose run --rm algo-php php FizzBuzz.php

algo:
	docker exec -it algo-php bash