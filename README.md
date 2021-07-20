# StreetGroupTechTest
Street Group Tech Test

Please note, this is an idea of how I would code. It is more psuedo coding and an idea as to how I like to structure my code.
Regex is not my strongest point.

Requirements to run this tech test

- php 7.4
- Composer
- Command Line Interface

## How it is built
- Slim framework version 4

## How to get started
- Run `composer install`

Tom McFarlane's Tech Test is running off the local php server. To run the server, 
- `php -S localhost:8000 public/index.php`

All the requests come through public/index.php

## Useful commands
- `composer install` - install composer
- `composer phpstan` - run PHP Stan (Static Analyser tool)
- `composer phpcs` - Run PHP Coding Standard and ensure they are being met
- `php ./vendor/bin/phpunit tests/unit` - Run Unit tests

### General Comments 

I think it is self-explanatory how I have set up the project and every single Class has its own function. 
It is all split out into their own directories with relevant naming.
To upload a CSV you will need to look at requirements in `public/endpoints.md`. 
Note - I would normally use Swagger for this. 

### Just to give an overview

- public - anything that is public facing
- src - all logic and code belongs here
- tests - all tests are here
- vendor - all composer and third party dependencies

#####Inside src directory

- Controllers - all the endpoints are configured to accept data from a request and to then return a response
- Entities - map of the database tables
- Exceptions - custom exceptions to give a better knowledge of what is going
- Processes - Where a "process" should have it's logic and should always return a form of a result. 
Makes the code more reusable if it was to be used through the CLI
- Repositories - The section that communicates with the Database
- Repositories/Interfaces - Where all Repository interfaces are
- Services - Where all business logic is handled

Please note for the API side, I would have added in bearer token validation, ensuring it came from the right IP address.
The same for the unit tests. I only wrote tests for the Service just to demonstrate the point that I can do it.
