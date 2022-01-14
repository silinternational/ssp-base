# ssp-base 
Base image for simpleSAMLphp

Docker image: [silintl/ssp-base](https://hub.docker.com/r/silintl/ssp-base/)

## Prerequisite software
[Docker](https://www.docker.com/products/overview) and [docker-compose](https://docs.docker.com/compose/install)
must be installed.

[Make](https://www.gnu.org/software/make) is optional but simplifies the build process.

[Vagrant](https://www.vagrantup.com) for Windows users.

## Local testing

1. `cp local.env.dist local.env` within project root and make adjustments as needed.
2. Add your github token to the `COMPOSER_AUTH` variable in the `local.env` file.
3. `make` or `docker-compose up -d` within the project root.
4. Visit http://localhost to see SSP running

### Setup PhpStorm for remote debugging with Docker

1. Make sure you're running PhpStorm 2016.3 or later
2. Setup Docker server by going to `Preferences` (or `Settings` on Windows) -> `Build, Execution, Deployment`
   -> Click `+` to add a new server. Use settings:
 - Name it `Docker`
 - API URL should be `tcp://localhost:2375`
 - Certificates folder should be empty
 - Docker Compose executable should be full path to docker-compose script

3. Hit `Apply`
4. Next in `Preferences` -> `Languages & Frameworks` -> `PHP` click on the `...`
   next to the `CLI Interpreter` and click `+` to add a new interpreter. Use
   settings:
 - Name: Remote PHP7
 - Remote: Docker
 - Server: chose the Docker server we added
 - Debugger extension: `/usr/lib/php/20151012/xdebug.so`

5. Hit `Apply` and `OK`
6. On `PHP` for Path mappings edit it so the project root folder maps to /data on remote server
7. Hit `Apply` and `OK`
8. Click on `Run` and then `Edit Configurations`
9. Click on `+` and select `PHP Web Application`
10. Name it `Debug via Docker`
11. Click on `...` next to Server and then `+` to add a server.
12. Name it `Localhost` and use settings:
 - Host: localhost
 - Port: 80
 - Debugger: Xdebug
 - Check use path mappings and add map from project root to `/data`
 
13. Hit `Apply` and `OK`
14. Click on `Run` and then `Debug 'Debug on Docker'`

## Overriding translations / dictionaries

If you use this Docker image but want to change some of the translations, you
can do so by providing identically-named dictionary files into an "overrides"
subfolder with just the desired changes, then running the
"apply-dictionaries-overrides.php" script.

Example Dockerfile (overriding text in the MFA module's material theme):
```dockerfile
FROM silintl/ssp-base:7.1.0

# ... do your other custom Docker stuff...

# Copy your translation changes into an "overrides" subfolder:
COPY ./dictionaries/* /data/vendor/simplesamlphp/simplesamlphp/modules/material/dictionaries/overrides/

# Merge those changes into the existing translation files:
RUN cd /data/vendor/simplesamlphp/simplesamlphp/modules/material/dictionaries/overrides/ \
 && php /data/apply-dictionaries-overrides.php
```

## Misc. Notes

* Use of sildisco's LogUser module is optional and triggered via an authproc.
