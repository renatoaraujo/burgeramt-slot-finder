.PHONY: help build startup vendor reload-permissions shutdown destroy find-anmeldung-slots

GREEN  := $(shell tput -Txterm setaf 2)
WHITE  := $(shell tput -Txterm setaf 7)
YELLOW := $(shell tput -Txterm setaf 3)
RESET  := $(shell tput -Txterm sgr0)

HELP_FUN = \
	%help; \
	while(<>) { push @{$$help{$$2 // 'options'}}, [$$1, $$3] if /^([a-zA-Z\-]+)\s*:.*\#\#(?:@([a-zA-Z\-]+))?\s(.*)$$/ }; \
	print "usage: make [target]\n\n"; \
	for (sort keys %help) { \
	print "${WHITE}$$_:${RESET}\n"; \
	for (@{$$help{$$_}}) { \
	$$sep = " " x (32 - length $$_->[0]); \
	print "  ${YELLOW}$$_->[0]${RESET}$$sep${GREEN}$$_->[1]${RESET}\n"; \
	}; \
	print "\n"; }

help: ##@other Show this help.
	@perl -e '$(HELP_FUN)' $(MAKEFILE_LIST)


setup: build startup reload-permissions vendor ## Setup application

build: ## Build the docker image
	docker-compose build

startup: ## Start the docker containers using docker-compose up command
	docker-compose up -d

shutdown: ## Shutdown the images using docker-compose kill command
	docker-compose kill

destroy: ## Destroy the docker containers and network using docker-compose down command
	docker-compose down

vendor:	## Install PHP composer dependencies
	docker-compose exec app composer install --no-interaction -d /app

reload-permissions: ## Reload the permissions for symfony folders on Docker container
	docker-compose exec app chmod -R 777 /app/var

find-anmeldung-slots: ## Execute the command to check slots available for Anmeldung
	docker-compose exec app bin/console app:find-anmeldung-slots
