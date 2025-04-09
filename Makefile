IMAGE_NAME=pergamin-recruitment-task-php

build:
	docker compose up -d --build
	docker compose exec php bash -c "composer setup && php artisan key:generate && php artisan migrate:fresh --seed"

start:
	docker compose up -d

exec:
	docker compose exec php bash

cs_fix:
	docker compose exec php bash -c "vendor/bin/pint"

test:
	docker compose exec php bash -c "vendor/bin/pint --test && vendor/bin/phpstan analyse app tests && php artisan test"

cs_fix_and_test: cs_fix test

stop:
	docker compose stop

down:
	docker compose down

restart:
	docker compose restart

purge:
	docker compose down -v
	docker rmi $(docker images -q $(IMAGE_NAME))

clear_cache:
	docker compose exec php bash -c "php artisan config:clear"
	docker compose exec php bash -c "php artisan cache:clear"
	docker compose exec php bash -c "php artisan event:clear"
	docker compose exec php bash -c "php artisan queue:clear"
	docker compose exec php bash -c "php artisan route:clear"
	docker compose exec php bash -c "php artisan view:clear"
