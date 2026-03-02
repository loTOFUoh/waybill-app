setup:
	docker run --rm -v $(PWD):/app composer create-project laravel/laravel .
	sudo chown -R $$USER:$$USER .
	make up

up:
	docker compose up -d

down:
	docker compose down

migrate:
	docker compose exec app php artisan migrate

bash:
	docker compose exec app sh
