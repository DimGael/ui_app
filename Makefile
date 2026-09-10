.PHONY: help start encore symfonyserver stop cache

help: ## Display this help message
	@echo "Available commands:"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2}'

start: ## Start all services (Encore, Docker, Symfony server)
	@$(MAKE) -j3 encore docker symfonyserver

encore: ## Start Webpack Encore in watch mode
	npm run watch

docker: ## Start Docker containers (MySQL database)
	docker compose up

symfonyserver: ## Start the Symfony development server
	symfony server:start

stop: ## Stop Docker containers
	docker compose stop

cache: ## Clear the Symfony cache
	php bin/console c:c
