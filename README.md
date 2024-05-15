# ssp-base 
Base image for simpleSAMLphp

Docker image: [silintl/ssp-base](https://hub.docker.com/r/silintl/ssp-base/)

## Prerequisite software
[Docker](https://www.docker.com/products/overview) and [docker-compose](https://docs.docker.com/compose/install)
must be installed.

[Make](https://www.gnu.org/software/make) is optional but simplifies the build process.

[Vagrant](https://www.vagrantup.com) for Windows users.

## Configuration
By default, configuration is read from environment variables. These are documented
in the `local.env.dist` file. Optionally, you can define configuration in AWS AppConfig.
To do this, set the following environment variables to point to the configuration in
AWS:

* `AWS_REGION` - the AWS region in use
* `APP_ID` - the application ID or name
* `CONFIG_ID` - the configuration profile ID or name
* `ENV_ID` - the environment ID or name

In addition, the AWS API requires authentication. It is best to use an access role
such as an [ECS Task Role](https://docs.aws.amazon.com/AmazonECS/latest/developerguide/task-iam-roles.html).
If that is not an option, you can specify an access token using the `AWS_ACCESS_KEY_ID` and
`AWS_SECRET_ACCESS_KEY` variables.

The content of the configuration profile takes the form of a typical .env file, using
`#` for comments and `=` for variable assignment. Any variables read from AppConfig
will overwrite variables set in the execution environment.

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

## Included Modules

### ExpiryChecker simpleSAMLphp Module
A simpleSAMLphp module for warning users that their password will expire soon
or that it has already expired.

**NOTE:** This module does *not* prevent the user from logging in. It merely
shows a warning page (if their password is about to expire), with the option to
change their password now or later, or it tells the user that their password has
already expired, with the only option being to go change their password now.
Both of these pages will be bypassed (for varying lengths of time) if the user
has recently seen one of those two pages, in order to allow the user to get to
the change-password website (assuming it is also behind this IdP). If the user
should not be allowed to log in at all, the simpleSAMLphp Auth. Source should
consider the credentials provided by the user to be invalid.

The expirychecker module is implemented as an Authentication Processing Filter,
or AuthProc. That means it can be configured in the global config.php file or
the SP remote or IdP hosted metadata.

It is recommended to run the expirychecker module at the IdP, and configure the
filter to run before all the other filters you may have enabled.

#### How to use the module

Set filter parameters in your config. We recommend adding
them to the `'authproc'` array in your `metadata/saml20-idp-hosted.php` file,
but you are also able to put them in the `'authproc.idp'` array in your
`config/config.php` file.

Example (in `metadata/saml20-idp-hosted.php`):

    'authproc' => [
        10 => [
            // Required:
            'class' => 'expirychecker:ExpiryDate',
            'accountNameAttr' => 'cn',
            'expiryDateAttr' => 'schacExpiryDate',
            'passwordChangeUrl' => 'https://idm.example.com/pwdmgr/',

            // Optional:
            'warnDaysBefore' => 14,
            'originalUrlParam' => 'originalurl',
            'dateFormat' => 'm.d.Y', // Use PHP's date syntax.
            'loggerClass' => '\\Sil\\Psr3Adapters\\Psr3SamlLogger',
        ],
        
        // ...
    ],

The `accountNameAttr` parameter represents the SAML attribute name which has
the user's account name stored in it. In certain situations, this will be
displayed to the user, as well as being used in log messages.

The `expiryDateAttr` parameter represents the SAML attribute name which has
the user's expiry date, which must be formated as YYYYMMDDHHMMSSZ (e.g.
`20111011235959Z`). Those two attributes need to be part of the attribute set
returned when the user successfully authenticates.

The `warnDaysBefore` parameter should be an integer representing how many days
before the expiry date the "about to expire" warning will be shown to the user.

The `dateFormat` parameter specifies how you want the date to be formatted,
using PHP `date()` syntax. See <http://php.net/manual/en/function.date.php>.

The `loggerClass` parameter specifies the name of a PSR-3 compatible class that
can be autoloaded, to use as the logger within ExpiryDate.

#### Acknowledgements

This is adapted from the `ssp-iidp-expirycheck` and `expirycheck` modules.
Thanks to Alex Mihičinac, Steve Moitozo, and Steve Bagwell for the initial work
they did on those two modules.

### Multi-Factor Authentication (MFA) simpleSAMLphp Module
A simpleSAMLphp module for prompting the user for MFA credentials (such as a
TOTP code, etc.).

This mfa module is implemented as an Authentication Processing Filter,
or AuthProc. That means it can be configured in the global config.php file or
the SP remote or IdP hosted metadata.

It is recommended to run the mfa module at the IdP, and configure the
filter to run before all the other filters you may have enabled.

#### How to use the module

You will need to set filter parameters in your config. We recommend adding
them to the `'authproc'` array in your `metadata/saml20-idp-hosted.php` file.

Example (for `metadata/saml20-idp-hosted.php`):

    use Sil\PhpEnv\Env;
    use Sil\Psr3Adapters\Psr3SamlLogger;
    
    // ...
    
    'authproc' => [
        10 => [
            // Required:
            'class' => 'mfa:Mfa',
            'employeeIdAttr' => 'employeeNumber',
            'idBrokerAccessToken' => Env::get('ID_BROKER_ACCESS_TOKEN'),
            'idBrokerAssertValidIp' => Env::get('ID_BROKER_ASSERT_VALID_IP'),
            'idBrokerBaseUri' => Env::get('ID_BROKER_BASE_URI'),
            'idBrokerTrustedIpRanges' => Env::get('ID_BROKER_TRUSTED_IP_RANGES'),
            'idpDomainName' => Env::get('IDP_DOMAIN_NAME'),
            'mfaSetupUrl' => Env::get('MFA_SETUP_URL'),

            // Optional:
            'loggerClass' => Psr3SamlLogger::class,
        ],
        
        // ...
    ],

The `employeeIdAttr` parameter represents the SAML attribute name which has
the user's Employee ID stored in it. In certain situations, this may be
displayed to the user, as well as being used in log messages.

The `loggerClass` parameter specifies the name of a PSR-3 compatible class that
can be autoloaded, to use as the logger within ExpiryDate.

The `mfaSetupUrl` parameter is for the URL of where to send the user if they
want/need to set up MFA.

The `idpDomainName` parameter is used to assemble the Relying Party Origin
(RP Origin) for WebAuthn MFA options.

#### Why use an AuthProc for MFA?
Based on...

- the existence of multiple other simpleSAMLphp modules used for MFA and
  implemented as AuthProcs,
- implementing my solution as an AuthProc and having a number of tests that all
  confirm that it is working as desired, and
- a discussion in the SimpleSAMLphp mailing list about this:  
  https://groups.google.com/d/msg/simplesamlphp/ocQols0NCZ8/RL_WAcryBwAJ

... it seems sufficiently safe to implement MFA using a simpleSAMLphp AuthProc.

For more of the details, please see this Stack Overflow Q&A:  
https://stackoverflow.com/q/46566014/3813891

#### Acknowledgements
This is adapted from the `silinternational/simplesamlphp-module-mfa`
module, which itself is adapted from other modules. Thanks to all those who
contributed to that work.

### Profile Review SimpleSAMLphp Module

A simpleSAMLphp module for prompting the user review their profile (such as
2-step verification, email, etc.).

This module is implemented as an Authentication Processing Filter,
or AuthProc. That means it can be configured in the global config.php file or
the SP remote or IdP hosted metadata.

It is recommended to run the profilereview module at the IdP, after all
other authentication modules.

#### How to use the module

You will need to set filter parameters in your config. We recommend adding
them to the `'authproc'` array in your `metadata/saml20-idp-hosted.php` file.

Example (for `metadata/saml20-idp-hosted.php`):

    use Sil\PhpEnv\Env;
    use Sil\Psr3Adapters\Psr3SamlLogger;
    
    // ...
    
    'authproc' => [
        10 => [
            // Required:
            'class' => 'profilereview:ProfileReview',
            'employeeIdAttr' => 'employeeNumber',
            'profileUrl' => Env::get('PROFILE_URL'),
            'mfaLearnMoreUrl' => Env::get('MFA_LEARN_MORE_URL'),

            // Optional:
            'loggerClass' => Psr3SamlLogger::class,
        ],
        
        // ...
    ],

The `employeeIdAttr` parameter represents the SAML attribute name which has
the user's Employee ID stored in it. In certain situations, this may be
displayed to the user, as well as being used in log messages.

The `loggerClass` parameter specifies the name of a PSR-3 compatible class that
can be autoloaded, to use as the logger within ExpiryDate.

The `profileUrl` parameter is for the URL of where to send the user if they
want/need to update their profile.
