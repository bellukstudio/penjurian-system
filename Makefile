.PHONY: help build up down restart logs shell db-migrate db-fresh cache-clear

help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build: ## Build PenjurianDemo containers
	docker compose build --no-cache

up: ## Start all containers
	docker compose up -d

down: ## Stop all containers
	docker compose down

restart: ## Restart all containers
	docker compose restart

logs: ## Show PenjurianDemo logs
	docker compose logs -f app

shell: ## Access PenjurianDemo shell
	docker compose exec app sh

db-migrate: ## Run database migrations
	docker compose exec app php artisan migrate

db-fresh: ## Fresh database with seeders
	docker compose exec app php artisan migrate:fresh --seed

cache-clear: ## Clear all caches
	docker compose exec app php artisan cache:clear
	docker compose exec app php artisan config:clear
	docker compose exec app php artisan route:clear
	docker compose exec app php artisan view:clear

optimize: ## Optimize application for production
	docker compose exec app php artisan config:cache
	docker compose exec app php artisan route:cache
	docker compose exec app php artisan view:cache

queue-work: ## Start queue worker
	docker compose exec app php artisan queue:work

tinker: ## Access Laravel Tinker
	docker compose exec app php artisan tinker

key-generate: ## Generate application key
	docker compose exec app php artisan key:generate

storage-link: ## Create storage link
	docker compose exec app php artisan storage:link
