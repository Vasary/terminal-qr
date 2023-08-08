DOCKER_COMPOSE = docker-compose -f docker-compose.yml -f build/docker-compose.yml
COMPOSE_PROJECT_NAME=terminal

# Targets
.PHONY: up down exec test build
up: build
	$(DOCKER_COMPOSE) up -d

down:
	$(DOCKER_COMPOSE) down

build:
	$(DOCKER_COMPOSE) build

exec:
	$(DOCKER_COMPOSE) exec qr sh

test:
	$(DOCKER_COMPOSE) exec qr vendor/bin/pest
