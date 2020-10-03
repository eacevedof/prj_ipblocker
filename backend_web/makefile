#!/bin/bash
OS := $(shell uname)

ifeq ($(OS),Linux)
	PHPEXE="php7.4-cli"
else
	PHPEXE="php"
endif

help: ## Show this help message
	@echo "usage:\n\tmake [target]"
	@echo "targets:"
	@egrep "^(.+)\:\ ##\ (.+)" ${MAKEFILE_LIST} | column -t -c 2 -s ":#"

rmlogs: ## remove all logs
	rm -fr ./logs/*

start: ## Start server in socket 0.0.0.0:3100
	$(PHPEXE) -S 0.0.0.0:3100 -t ./public

startpriv: ## Start server in socket localhost:3100
  $(PHPEXE) -S localhost:3100 -t ./public

refillna: ## Search 50 app_ip.whois=na
	$(PHPEXE) ./public/refillwhoisna.php

test: ## Run tests production
	${PHPEXE} ./vendor/bin/phpunit ./tests

t: ## Run tests
	$(PHPEXE) ./vendor/bin/phpunit ./tests

t.unit:
	$(PHPEXE) ./vendor/bin/phpunit ./tests/Unit

t.unit.helpers:
	$(PHPEXE) ./vendor/bin/phpunit ./tests/Unit/Helpers

t.unit.components:
	$(PHPEXE) ./vendor/bin/phpunit ./tests/Unit/Components

t.unit.providers:
	$(PHPEXE) ./vendor/bin/phpunit ./tests/Unit/Providers

t.unit.services:
	$(PHPEXE) ./vendor/bin/phpunit ./tests/Unit/Services

t.unit.controllers:
	$(PHPEXE) ./vendor/bin/phpunit --stderr ./tests/Unit/Controllers
