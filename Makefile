hub: clean
	docker compose up -d ssp-hub.local

clean:
	docker compose kill
	docker compose rm -f

composer:
	docker compose run --rm composer bash -c "./update-composer-deps.sh"

test:
	docker compose run --rm ssp-hub.local ./run-metadata-tests.sh
	docker compose run --rm ssp-idp1.local ./run-metadata-tests.sh
	docker compose run --rm ssp-sp1.local ./run-metadata-tests.sh
	docker compose run --rm test

test-integration:
	docker compose run --rm test ./run-integration-tests.sh

copyJsLib:
	cp ./node_modules/@simplewebauthn/browser/dist/bundle/index.umd.min.js ./modules/mfa/www/simplewebauthn/browser.js
	cp ./node_modules/@simplewebauthn/browser/LICENSE.md ./modules/mfa/www/simplewebauthn/LICENSE.md

deps:
	docker compose run --rm node npm install --ignore-scripts
	make copyJsLib

depsupdate:
	docker compose run --rm node npm update --ignore-scripts
	make copyJsLib
