up:
	docker compose up -d --build

down:
	docker compose down

ps:
	docker compose ps

install:
	docker compose exec php composer install

migrate:
	docker compose exec php php artisan migrate

fresh:
	docker compose exec php php artisan migrate:fresh

routes:
	docker compose exec php php artisan route:list

shell:
	docker compose exec php bash

logs:
	docker compose logs -f
