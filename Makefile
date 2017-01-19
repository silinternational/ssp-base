start: ssp

ssp: clean
	docker-compose up -d ssp

hub: clean
	docker-compose up -d hub sp1 sp2 idp1 idp2

clean:
	docker-compose kill
	docker-compose rm -f

composer:
	docker-compose run --rm composer bash -c "composer self-update && composer update --no-scripts --ignore-platform-reqs"
