# Makefile — shae-php-blog-cms
#
# SHAE Blog CMS (PHP). No compiled artifacts. Targets lint the PHP sources
# (requires php on PATH) and package a release zip of src/.
#
# NOTE: PHP is not installed in this environment, so `make test` cannot be
# run locally. See README.md and scripts/install.sh.

.PHONY: build test release clean install help

SRC_DIR   := src
RELEASE   := shae-php-blog-cms-v0.1.0.zip
PHP       ?= php

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-22s\033[0m %s\n", $$1, $$2}'

build: ## Build (no-op for PHP; validates sources exist)
	@test -f $(SRC_DIR)/index.php
	@test -f $(SRC_DIR)/post.php
	@test -f $(SRC_DIR)/storage.php
	@test -f $(SRC_DIR)/styles.css
	@echo "Build OK: PHP sources present."

test: ## Lint/check PHP syntax (requires php)
	@if command -v $(PHP) >/dev/null 2>&1; then \
		for f in $(SRC_DIR)/*.php; do \
			$(PHP) -l "$$f"; \
		done; \
	else \
		echo "PHP not found on PATH. Skipping syntax check."; \
		echo "Install PHP to run: make test"; \
	fi

release: build ## Create a release zip of src/
	@rm -f $(RELEASE)
	zip -r $(RELEASE) $(SRC_DIR) scripts Makefile README.md DESIGN.md LICENSE docs
	@echo "Release artifact: $(RELEASE)"

clean: ## Remove release artifacts
	rm -f $(RELEASE)
	rm -rf data

install: ## Notes on installing (see scripts/install.sh)
	@./scripts/install.sh
