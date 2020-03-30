# IT-Connectors Build Status
[![Build Status](https://travis-ci.org/lrdgz/it-connectors-laravel.svg?branch=master)](https://travis-ci.org/lrdgz/it-connectors-laravel)

## Installation
Use the package manager [composer](https://getcomposer.org/) to install packages.

## Curl Api Examples    

```bash

    //Register new user     
    - curl -i -H "Accept: application/json" -H "Content-Type: application/json" -v -X POST -d '{"email":"dev@dev.com","password":"123456", "password_confirm":"123456"}' http://it-connectors.test/api/register

    //Login user
    - curl -i -H "Accept: application/json" -H "Content-Type: application/json" -v -X POST -d '{"email":"dev@dev.com","password":"123456"}' http://it-connectors.test/api/login


    - curl -H 'content-type: application/json' -v -X GET http://localhost:8000/api/tasks 
    - curl -H 'content-type: application/json' -v -X GET http://localhost:8000/api/task/:id
    - curl -H 'content-type: application/json' -v -X POST -d '{"name":"Test api","description":"I am gonna test apis","user_id":1}' http://localhost:8000/api/task
    - curl -H 'content-type: application/json' -v -X PUT -d '{"name":"Test All APIs","description":"I am gonna test apis","user_id":1}}' http://localhost:8000/api/task/:id
    - curl -H 'content-type: application/json' -v -X DELETE http://localhost:8000/api/task/:id    
```  

## Artisan Commands
```bash
    - php artisan key:generate
    - php artisan migrate
    - php artisan migrate:fresh --seed
    - php artisan migrate --force
    - php artisan migrate --database=mysql_test
    - php artisan migrate --database=mysql_test --env=testing
```

## Run tests
```bash
    - vendor\bin\phpunit
    - vendor\bin\phpunit --filter=LoginTest
    - vendor\bin\phpunit --filter=a_user_can_correct_login
```

## Utils Alias
```bash
    - alias lphpunit="vendor/bin/phpunit"
    - alias lphpunitf="lphpunit --filter="
```


    
