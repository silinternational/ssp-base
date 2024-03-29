start: ssp

ssp: clean
	docker-compose up -d ssp

hub: clean
	docker-compose up -d ssp-hub.local ssp-sp1.local sp2 ssp-idp1.local idp2

clean:
	docker-compose kill
	docker-compose rm -f

composer:
	docker-compose run --rm composer bash -c "./update-composer-deps.sh"

test:
	docker-compose run --rm ssp-hub.local ./run-metadata-tests.sh
	docker-compose run --rm ssp-idp1.local ./run-metadata-tests.sh
	docker-compose run --rm ssp-sp1.local ./run-metadata-tests.sh
	docker-compose run --rm test

test-integration:
	docker-compose run --rm test ./run-integration-tests.sh
