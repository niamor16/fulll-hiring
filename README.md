# Requirements

- docker compose
- (MAKE)

# Start
```bash
make build
-- OR
docker compose build

make up
-- OR
docker compose up -d
```

# Structure
- container with PHP 8.4 for backend test (*backend-php*)
```bash
make backend
-- OR
docker exec -it backend-php bash
```

- container with postgreSQL 18 for backend test (*backend-db*)
```bash
make db
-- OR
docker exec -it backend-db bash
```

- container with PHP 8.4 for algo test (*algo-php*)
```bash
make algo
-- OR
docker exec -it algo-php bash
```

- Makefile with some helpfull commands

# Test Algo
[Algo](./Algo)

I wrote a functionnal and an Object implementations with same logic inside:
- FizzBuzz.php (class implementation)
- fizzbuzz_fn.php (functionnal implementation)

You can run a test for number 15 :
```bash
make fizzbuzz_function
make fizzbuzz_class
-- OR
docker exec -it algo-php bash
> php fizzbuzz_fn.php
> php FizzBuzz.php
```

# Test Backend
[Backend/PHP](./Backend/PHP)

## Step 1

## Step 2

Run tests :
```bash
make test-backend
-- OR
docker exec -it backend-php bash
> vendor/behat/behat/bin/behat
```

## Step 3

- *For code quality, you can use some tools : which one and why (in a few words) ?*  
  PHP Stan


- *you can consider to setup a ci/cd process : describe the necessary actions in a few words*  
  