#!/bin/sh

cd ..
#php console db:database:drop --withConfirm=0 --env=test
#php console db:migrate:down --withConfirm=0 --env=test
#php console db:migrate:up --withConfirm=0 --env=test
cd ..

#cd public
#command php -S localhost:8001 -t &
#cd ..

php vendor/phpunit/phpunit/phpunit