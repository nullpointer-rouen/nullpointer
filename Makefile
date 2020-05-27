
DOCKER_PHP = nullpointer_php

up:
	@docker-compose up

down:
	@docker-compose down

migrate:
	docker exec -it $(DOCKER_PHP) bash -c "php /var/www/html/app/bin/console make:migration"
	docker exec -it $(DOCKER_PHP) bash -c "php /var/www/html/app/bin/console doctrine:migration:migrate -n"
