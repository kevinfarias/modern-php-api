#!/bin/bash
# Requires docker CLI and heroku CLI
docker-compose push
docker_image=kevinfarias/php-cleanarch-microservice
heroku_app=php-cleanarch-microservice

id=`docker images | grep $docker_image | awk '{print $3}'`
docker tag $id registry.heroku.com/$heroku_app/web
docker push registry.heroku.com/$heroku_app/web
heroku container:release web --app=$heroku_app