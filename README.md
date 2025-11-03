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

I wrote a functional and an object-oriented implementations with same logic inside:
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

Run tests :
```bash
make test-backend
-- OR
docker exec -it backend-php bash
> vendor/behat/behat/bin/behat
```

## Step 2

To excecute CLI commands you have to connect to container :
```bash
make backend
-- OR
docker exec -it backend-php bash
```
And go to *bin* directory (the entry `fleet` file is here)
```bash
cd bin
```

Now you can run commands :

```shell
./fleet create <userId> # returns fleetId on the standard output
./fleet register-vehicle <fleetId> <vehiclePlateNumber>
./fleet localize-vehicle <fleetId> <vehiclePlateNumber> lat lng [alt]
```

## Step 3

*For code quality, you can use some tools : which one and why (in a few words) ?*  

I use PHPStan to ensure code quality.
It helps detect type and logic errors before runtime and enforces consistent, reliable code.
It can be triggered with multiple levels according to the strictness quality we want.
Also it can be configured to match team coding standards.

*you can consider to setup a ci/cd process : describe the necessary actions in a few words*  

- Set up env variables
- Run code analysis (PHPStan)
- Execute tests (phpUnit, behat, etc.)
- Deploy to server and/or build release/artifact
- Install dependencies (node, composer, etc)
- Execute some scripts (like Database migrations)
  