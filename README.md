# ssp-base

Base image for SimpleSAMLphp IdP and Hub with custom modules

Docker image: [silintl/ssp-base](https://hub.docker.com/r/silintl/ssp-base/)

## Prerequisite software
[Docker](https://www.docker.com/products/overview) and [docker compose](https://docs.docker.com/compose/install)
must be installed.

[Make](https://www.gnu.org/software/make) is optional but simplifies the build process.

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
2. `cp local.broker.env.dist local.broker.env` within project root and make adjustments as needed.
3. Add your GitHub [personal access token](https://github.com/settings/tokens?type=beta) to the `COMPOSER_AUTH` variable in the `local.env` file.
4. Create `localhost` aliases for `ssp-hub.local`, `ssp-idp1.local`, `ssp-idp2.local`, `ssp-idp3.local`, `ssp-sp1.local`, `ssp-sp2.local`, and `ssp-sp3.local`. This is typically done in `/etc/hosts`.  _Example line:  `127.0.0.1  ssp-hub.local ssp-idp1.local ssp-idp2.local ssp-idp3.local ssp-sp1.local ssp-sp2.local ssp-sp3.local`_
5. Run `make test` within the project root.
6. Visit http://ssp-hub.local to see SimpleSAMLphp

_Note:_ there is an unresolved problem that requires a change to BASE_URL_PATH for ssp-idp1.local in docker-compose.yml due to a requirement in silauth that it be a full URL. For automated testing, it must not have a port number, but for manual testing it needs the port number.

### Configure a container for debugging with Xdebug

1. Add a volume map for run-debug.sh on the container you wish to debug.

```yml
    - ./development/run-debug.sh:/data/run-debug.sh
```

2. Add or change the `command` for the container.

```yml
    command: /data/run-debug.sh
```

3. Restart the container.

```shell
docker composer up -d ssp-hub.local
```

### Setup PhpStorm for remote debugging with Docker

1. Make sure you're running PhpStorm 2016.3 or later
2. Setup Docker server by going to `Preferences` (or `Settings` on Windows) -> `Build, Execution, Deployment`
   -> Click `+` to add a new server. Use settings:
 - Name it `Docker`
 - API URL should be `tcp://localhost:2375`
 - Certificates folder should be empty
 - Docker Compose executable should be full path to docker compose script

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

### Metadata Tests Check:
- Metadata files can be linted via php (`php -l file`)
- Metadata files return arrays
- IdP Metadata files have an IdP namespace that exists, is a string, and only contains letters, numbers, hyphens, and underscores
- IdP Metadata files don't have duplicate IdP codes
- SP Metadata files don't have duplicate entity ids
- IdP Metadatas contains `name` entry with an `en` entry
- IdP Metadatas contains `logoURL` entry
- if SP Metadata contains `IDPList`, check that it is allowed for that IdP as well

#### Hub mode tests [SKIPPED if HUB_MODE = false]
- IdP Metadata files SP List is an array
- IdP Metadata files LogoCaption isset
- IdP Metadata files SP List has existing SPs
- All SPs have an IdP it can use
- All SPs have a non-empty IDPList entry
- All SPs have a non-empty name entry

#### SP tests [SKIPPED if `'SkipTests' => true,`]
- Contains a `CertData` entry
- Contains a `saml20.sign.response` entry AND it is set to true
- Contains a `saml20.sign.assertion` entry AND it is set to true
- Contains a `assertion.encryption` entry AND it is set to true

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

The `passwordChangeUrl` parameter contains the URL of the password manager. A
link to that URL may be presented to the user as a convenience for updating
their password.

The `warnDaysBefore` parameter should be an integer representing how many days
before the expiry date the "about to expire" warning will be shown to the user.

The `loggerClass` parameter specifies the name of a PSR-3 compatible class that
can be autoloaded, to use as the logger within ExpiryDate.

#### Acknowledgements

This is adapted from the `ssp-iidp-expirycheck` and `expirycheck` modules.
Thanks to Alex Mihičinac, Steve Moitozo, and Steve Bagwell for the initial work
they did on those two modules.

### Material Module

Material Design theme for use with SimpleSAMLphp

#### Configuration

No configuration is necessary. The `theme.use` config option is pre-configured to `material:material`.
Optional configuration is described below.

##### Google reCAPTCHA

If a site key has been provided in the `RECAPTCHA_SITE_KEY` environment variable, the
username/password page may require the user prove his/her humanity.

##### Branding

Set the `THEME_COLOR_SCHEME` environment variable using one of the following values:

```
'indigo-purple', 'blue_grey-teal', 'red-teal', 'orange-light_blue', 'brown-orange', 'teal-blue'
```

The default is `indigo-purple`.

The login page looks for `/simplesamlphp/public/logo.png` which is **NOT** provided by default.

##### Analytics

Set the `ANALYTICS_ID` environment variable to contain your Google Analytics 4 tag ID.

##### Announcements

Update `/simplesamlphp/announcement/announcement.php`:

```
 return 'Some <strong>important</strong> announcement';
```

By default, the announcement is whatever is returned by `/simplesamlphp/announcement/announcement.php`._

If provided, an alert will be shown to the user filled with the content of that announcement. HTML is supported.

#### Testing the Material theme

[Manual tests](./docs/material_tests.md)

#### i18n support

Translations are in files located in the `modules/material/locales` directory.

Localization is affected by the configuration setting `language.available`. Only language codes found in this property
will be utilized. For example, if a translation is provided in Afrikaans for this module, the configuration must be
adjusted to make 'af' an available language. If that's not done, the translation function will not utilize the
translations even if provided.

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
can be autoloaded, to use as the logger within the module.

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

### SilAuth SimpleSAMLphp module

SimpleSAMLphp auth module implementing custom business logic:

- authentication
- rate limiting
- status endpoint

#### Database Migrations
To create another database migration file, run the following (replacing
`YourMigrationName` with whatever you want the migration to be named, using
CamelCase):

    make migration NAME=YourMigrationName

#### Rate Limiting
SilAuth will rate limit failed logins by username and by every untrusted IP
address from a login attempt.

##### tl;dr ("the short version")
If there have been more than 10 failed logins for a given username (or IP
address) within the past hour, a captcha will be included in the webpage. The
user may or may not have to directly interact with the captcha, though.

If there have been more than 50 failed logins for that username (or IP address)
within the past hour, logins for that username (or IP address) will be blocked
for up to an hour.

##### Details
For each login attempt, if it has too many failed logins within the last hour
(aka. recent failed logins) for the given username OR for any single untrusted
IP address associated with the current request, it will do one of the following:

- If there are fewer than `Authenticator::REQUIRE_CAPTCHA_AFTER_NTH_FAILED_LOGIN`
  recent failures: process the request normally.
- If there are at least that many, but fewer than
  `Authenticator::BLOCK_AFTER_NTH_FAILED_LOGIN`: require the user to pass a
  captcha.
- If there are more than that: block that login attempt for `(recent failures
  above the limit)^2` seconds after the most recent failed login, with a
  minimum of 3 (so blocking for 9 seconds).
- Note: the blocking time is capped at an hour, so if no more failures occur,
  then the user will be unblocked in no more than an hour.

See `features/login.feature` for descriptions of how various situations are
handled. That file not only contains human-readable scenarios, but those are
also actual tests that are run to ensure those descriptions are correct.

##### Example 1

- If `BLOCK_AFTER_NTH_FAILED_LOGIN` is 50, and
- if `REQUIRE_CAPTCHA_AFTER_NTH_FAILED_LOGIN` is 10, and
- if there have been 4 failed login attempts for `john_smith`, and
- there have been 10 failed login attempts from `11.22.33.44`, and
- there have been 3 failed login attempts from `192.168.1.2`, and
- someone tries to login as `john_smith` from `192.168.1.2` and their request
  goes through a proxy at `11.22.33.44`, then
- they will have to pass a captcha, but they will not yet be blocked.

##### Example 2

- However, if all of the above is true, but
- there have now been 55 failed login attempts from `11.22.33.44`, then
- any request involving that IP address will be blocked for 25 seconds after
  the most recent of those failed logins.

#### Excluding trusted IP addresses from IP address based rate limiting
Since this application enforces rate limits based on the number of recent
failed login attempts by both username and IP address, and since it looks at
both the REMOTE_ADDR and the X-Forwarded-For header for IP addresses, you will
want to list any IP addresses that should NOT be rate limited (such as your
load balancer) in the TRUSTED_IP_ADDRESSES environment variable (see
`local.env.dist`).

#### Status Check
To check the status of the website, you can access this URL:
`https://(your domain name)/module.php/silauth/status.php`

### SilDisco module for SAML Discovery

#### Configuration

Ensure the DYNAMO_* environment variables are set as shown in the local.env.dist file.

#### Overview

[Module Overview](./docs/overview.md)

#### The Hub

[The Hub](./docs/the_hub.md)

#### Authprocs

[Editing Authprocs](./docs/editing_authprocs.md)

#### Development

[Development](./docs/development.md)

#### Functional Testing

[Functional Testing](./docs/functional_testing.md)
