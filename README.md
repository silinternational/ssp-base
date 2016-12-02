# ssp-hub-base
Base image for IdP Hub based on SimpleSAMLphp

# Prerequisite software
[Docker](https://www.docker.com/products/overview) and [docker-compose](https://docs.docker.com/compose/install)
must be installed.

[Make](https://www.gnu.org/software/make) is optional but simplifies the build process.

[Vagrant](https://www.vagrantup.com) for Windows users.

# Local testing

1. `cp local.env.dist local.env` within project root and make adjustments as needed.
2. Add your github token to the `COMPOSER_AUTH` variable in the `local.env` file.
3. `make` or `docker-compose up -d` within the project root.
4. Add the following aliases to your hosts file:
  ```
  ssp-hub.local
  ssp-hub-sp.local
  ssp-hub-sp2.local
  ssp-hub-idp.local
  ssp-hub-idp2.local
  ```  
  > For Vagrant users, these aliases **must** be attached to `192.168.35.10`.

Upon successful build, the following should be available:

  * [Hub](http://ssp-hub.local)
    * Click the *Authentication tab*
    * Click on *Test configured authentication sources*
  * [First SP](http://ssp-hub-sp.local:8080)
  * [Second SP](http://ssp-hub-sp2.local:8081)
  * [First IdP](http://ssp-hub-idp.local:8085)
    * username and password are both `a`
  * [Second IdP](http://ssp-hub-idp2.local:8086)
    * username and password are both `b`

## Setup PhpStorm for remote debugging with Docker

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
