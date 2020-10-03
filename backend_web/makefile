#!/bin/bash
help: ## Show this help message
	@echo "usage:\n\tmake [target]"
	@echo "targets:"
	@egrep "^(.+)\:\ ##\ (.+)" ${MAKEFILE_LIST} | column -t -c 2 -s ":#"

rmlogs: ## remove all logs
	rm -fr ./logs/*

start: ## Start server in socket 0.0.0.0:3100
	php -S 0.0.0.0:3100 -t ./public

startpriv: ## Start server in socket localhost:3100
  php -S localhost:3100 -t ./public

refillna: ## Search 50 app_ip.whois=na
	php ./public/refillwhoisna.php

test: ## Run tests
	php ./vendor/bin/phpunit ./tests

t: ## Run tests
	php ./vendor/bin/phpunit ./tests

t.unit:
	php ./vendor/bin/phpunit ./tests/Unit

t.unit.helpers:
	php ./vendor/bin/phpunit ./tests/Unit/Helpers

t.unit.components:
	php ./vendor/bin/phpunit ./tests/Unit/Components

t.unit.providers:
	php ./vendor/bin/phpunit ./tests/Unit/Providers

t.unit.services:
	php ./vendor/bin/phpunit ./tests/Unit/Services

t.unit.controllers:
	php ./vendor/bin/phpunit --stderr ./tests/Unit/Controllers
