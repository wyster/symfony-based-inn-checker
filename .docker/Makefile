-include .env

help:
	@echo ""
	@echo "usage: make COMMAND"
	@echo ""
	@echo "Commands:"
	@echo "  setup               Setup default setting for simple run"
	@echo "  up                  Run all containers in detached mode"
	@echo "  stop                Stop all containers"
	@echo "  restart             Restart all containers"
	@echo "  rm                  Stop and remove all containers"
	@echo "  logs                Follow logs"
	@echo "  tests               Run tests"
	@echo "  coverage            Run code coverage"

up: setup build
	@docker-compose up -d

build: setup
	@docker-compose build

stop:
	@docker-compose stop

restart:
	@docker-compose restart

rm:
	@docker-compose rm --stop --force

logs:
	@docker-compose logs -f -t --tail=1000

setup:
	@cp -n .env.dist .env
	@cp -n docker-compose.override.yml.dist docker-compose.override.yml

tests: setup
	@docker-compose -p ${PROJECT_NAME}_testing -f docker-compose.yml -f docker-compose.testing.yml up -d
	@docker-compose -p ${PROJECT_NAME}_testing exec -T web bash /wait-for.sh --timeout=30 localhost:80 -- echo "Webserver started"
	@docker-compose -p ${PROJECT_NAME}_testing exec -T php ./vendor/bin/codecept run
	@docker-compose -p ${PROJECT_NAME}_testing stop

coverage: setup
	@docker-compose -p ${PROJECT_NAME}_testing -f docker-compose.yml -f docker-compose.testing.yml up -d
	@docker-compose -p ${PROJECT_NAME}_testing exec -T web bash /wait-for.sh --timeout=30 localhost:80 -- echo "Webserver started"
	@docker-compose -p ${PROJECT_NAME}_testing exec -T php ./vendor/bin/codecept run --coverage --coverage-xml
	@docker-compose -p ${PROJECT_NAME}_testing stop

ci: setup
	@docker-compose -p ${PROJECT_NAME}_testing -f docker-compose.yml -f docker-compose.testing.yml up -d
	@docker-compose -p ${PROJECT_NAME}_testing exec -T web bash /wait-for.sh --timeout=30 localhost:80 -- echo "Webserver started"
	@docker-compose -p ${PROJECT_NAME}_testing exec -T php ./vendor/bin/codecept run --coverage --coverage-xml
	@docker-compose -p ${PROJECT_NAME}_testing exec -T php ./vendor/bin/grumphp run --testsuite=ci
	@docker-compose -p ${PROJECT_NAME}_testing stop
