export COMPOSE_PROJECT_NAME = $(shell basename $(shell pwd))

APP ?= app
IMAGE = ${COMPOSE_PROJECT_NAME}_${APP}:latest

CONTAINER_COMMAND = docker-compose run --rm ${APP}
COMPOSER_COMMAND = ${CONTAINER_COMMAND} composer --verbose

# Run the application's quality check
quality:
	${COMPOSER_COMMAND} quality

# Run the application's tests
test:
	${COMPOSER_COMMAND} test

# Run the application's PHP Insights
insights:
	${COMPOSER_COMMAND} insights

# Update the application's composer dependencies
update:
	${COMPOSER_COMMAND} update

# Install all of the application's composer dependencies
install:
	${COMPOSER_COMMAND} install

# Refresh the application environment -- clean dependencies
refresh:
	make clean && make

# Log in to the container
shell:
	docker-compose run ${APP} sh

# Clean the docker-composer environment by removing all containers, images and
# volumes
clean:
	docker-compose down -v --rmi all --remove-orphans
