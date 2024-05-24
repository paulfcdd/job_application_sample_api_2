# JBSA2 (Job Application Sample Api 2) project

## Requirements:
Docker and docker compose - last versions

## Setup:
- clone repo
- run `make build`
- after install container run `make ssh`
- run `composr install`
- run `composer dump-env dev` to generate env file (db connection mysql://root:mysql@db:3306/app_db?serverVersion=5.7&charset=utf8mb4)
- run `bin/console doctrine:database:create`
- run `bin/console doctrine:migrations:migrate`

## Usage:
- import postman collection to test API
- GET parameters for list endpoints:
    
  - page=1 - number of page
  - sort=createdAt - column from `job_application` to sort by 
  - order=DESC - sort order
  - position=2 - id of position from `postition` table
