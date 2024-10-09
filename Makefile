
## include local .env

ifneq (,$(wildcard .env))
    include .env
    export
endif

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | sed 's/^[^:]*://g' | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'


for-linux-env: ## Run if you are on a unix based operating system to prepare the .env
	cp .env.example .env
	echo "UID=$$(id -u)" >> .env
	echo "GID=$$(id -g)" >> .env
	

install: ## will prepare, install and serve the backend
	@make build
	@make up
	docker compose exec app composer install
	docker compose exec app php artisan key:generate
	docker compose exec app php artisan storage:link
	docker compose exec app chmod -R 777 storage bootstrap/cache
	@make serve
	@make fresh
create-project: ## will create a project from scratch
	@make build
	@make up
	docker compose exec app composer create-project --prefer-dist laravel/laravel .
	docker compose exec app php artisan key:generate
	docker compose exec app php artisan storage:link
	docker compose exec app chmod -R 777 storage bootstrap/cache
	docker compose exec app php artisan serve
	@make fresh
build: ## will build the containers
	docker compose build
up: ## will start the containers if they have been previously stopped via @make stop or docker compose stop
	docker compose up --detach
stop: ## will stop the containers
	docker compose stop
down: ## will stop and delete the containers
	docker compose down --remove-orphans
down-v: ## will stop and delete the containers and their volumes
	docker compose down --remove-orphans --volumes
restart: ## will run @make down && @make up
	@make down
	@make up
destroy: ## will stop and delete all containers and all volumes
	docker compose down --rmi all --volumes --remove-orphans
remake: ## will run @make destroy && @make install
	@make destroy
	@make install
ps: ## will run docker compose ps
	docker compose ps
app:## will open a bash shell in the php container
	docker compose exec app bash
serve: ## will run php artisan serve in the php container
	docker compose exec app php artisan serve
tinker: ## will run php artisan tinker in the php container
	docker compose exec app php artisan tinker
dump: ## will run php artisan dump-server in the php container
	docker compose exec app php artisan dump-server
test: ## will run php artisan test in the php container
	docker compose exec app php artisan test
migrate: ## will run php artisan migrate in the php container
	docker compose exec app php artisan migrate
fresh: ## will run php artisan migrate:fresh --seed in the php container
	docker compose exec app php artisan migrate:fresh --seed
seed: ## will run php artisan artisan db:seed in the php container
	docker compose exec app php artisan db:seed
rollback-test: ## will run php artisan migrate:fresh && php artisan migrate:refresh in the php container
	docker compose exec app php artisan migrate:fresh
	docker compose exec app php artisan migrate:refresh
optimize: ## will run php artisan optimize in the php container
	docker compose exec app php artisan optimize
optimize-clear: ## will run php artisan optimize:clear in the php container
	docker compose exec app php artisan optimize:clear
pest-debug: ## will run php ./vendor/pestphp/pest/bin/pest --debug in the php container
	docker compose exec app php ./vendor/pestphp/pest/bin/pest --debug
pest: ## will run php ./vendor/pestphp/pest/bin/pest in the php container
	docker compose exec app php ./vendor/pestphp/pest/bin/pest
rl: ## will run php artisan route:list in the php container
	docker compose exec app php artisan route:list
cda: ## will run composer dump-autoload --optimize && @make optimize in the php container
	docker compose exec app composer dump-autoload --optimize
	@make optimize
cache: ## will run @make cda && cache the views and events in the php container
	@make cda
	docker compose exec app php artisan event:cache
	docker compose exec app php artisan view:cache
cache-clear: ## will clear all caches in the php container
	docker compose exec app composer clear-cache
	@make optimize-clear
	docker compose exec app php artisan event:clear
	docker compose exec app php artisan view:clear
db: ## will open a bash shell in the db container
	docker compose exec db bash
sql: ## will open a mysql shell in the db container
	docker compose exec db bash -c 'mysql -u $$DB_USERNAME -p$$DB_PASSWORD $$DB_DATABASE'

ide-helper: ## will generate helper code for PHPStorm more info https://github.com/barryvdh/laravel-ide-helper
	docker compose exec app php artisan clear-compiled
	docker compose exec app php artisan ide-helper:generate
	docker compose exec app php artisan ide-helper:meta
	docker compose exec app php artisan ide-helper:models --write --reset
pint: ## will run ./vendor/bin/pint --verbose in the php container
	docker compose exec app ./vendor/bin/pint --verbose
pint-test:  ## will run ./vendor/bin/pint --verbose --test in the php container
	docker compose exec app ./vendor/bin/pint --verbose --test
