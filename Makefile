MAKEFLAGS += --silent

COMPOSER_COMMAND = docker-compose run --rm examples composer

# Run code checks
.PHONY: check
check:
	${COMPOSER_COMMAND} check

# Run the libraries tests
.PHONY: test
test:
	${COMPOSER_COMMAND} test

# Install all of the libraries composer dependencies
.PHONY: install
install:
	${COMPOSER_COMMAND} install

# Upgrade the libraries composer dependencies
.PHONY: upgrade
upgrade:
	${COMPOSER_COMMAND} upgrade

# Require a new composer dependency | PACKAGE?
.PHONY: require
require:
	test -n "$(PACKAGE)"
	${COMPOSER_COMMAND} require --prefer-source $(PACKAGE)

# Tag a new version of the library | VERSION!
.PHONY: version
version:
	test -n "$(VERSION)"
	git show --oneline -s
	read -p "Are you sure you want to force tag v$(VERSION)? Y [enter] / N [ctrl]+[c]"
	git tag -fsam ':gift: Version $(VERSION)' v$(VERSION) && \
	git push -f origin v$(VERSION)

# Log in to the development container
.PHONY: shell
shell:
	docker-compose run --rm examples sh

# List supported commands
.PHONY: help
help:
	@echo "\`make help\` is not supported by native Make"
	@echo "Download Modern Make to gain access to a dynamic command list"
	@echo "\033[92m»»\033[0m https://github.com/tj/mmake"
