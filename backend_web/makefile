#!/bin/bash
help: ## Show this help message
	@echo "usage:\n\tmake [target]"
	@echo "targets:"
	@egrep "^(.+)\:\ ##\ (.+)" ${MAKEFILE_LIST} | column -t -c 2 -s ":#"

start: ## Start server in socket 0.0.0.0:3100
	php -S 0.0.0.0:3100 -t ./public

startpriv: ## Start server in socket localhost:3100
  php -S localhost:3100 -t ./public

test: ## Run tests
	./vendor/bin/phpunit ./tests/

test.unit:
	./vendor/bin/phpunit ./tests/unit