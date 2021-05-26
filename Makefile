start: ssp

ssp: clean
	docker-compose up -d ssp

hub: clean
	docker-compose up -d hub sp1 sp2 idp1 idp2

clean:
	docker-compose kill
	docker-compose rm -f

composer:
	docker-compose run --rm composer bash -c "./update-composer-deps.sh"

test:
	docker-compose run --rm ssp bash -c "HUB_MODE=true /data/run-tests.sh"
