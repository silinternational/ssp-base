start: web

web:
	docker-compose up -d

clean:
	docker-compose kill
	docker-compose rm -f



