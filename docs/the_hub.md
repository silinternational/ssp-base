The hub will need its certs, `config.php` and `authsources.php` files as a normal simplesamlphp installation. Examples of these can be found in the `./development/hub` folder. (Note the `discoURL` entry in the `authsources.php` file.)

Other files it will need are as follows ...
* The files in the `./lib` folder will need to go into `/data/vendor/simplesamlphp/simplesamlphp/modules/sildisco/lib`
* The files in the `./www` folder will need to go into `/data/vendor/simplesamlphp/simplesamlphp/modules/sildisco/www`
* The `./sspoverrides/www_saml2_idp/SSOService.php` file will need overwrite the same out-of-the-box file in `/data/vendor/simplesamlphp/simplesamlphp/www/saml2/idp/`

### Metadata files
The hub should use the `saml20-*-remote.php`  files from [ssp-base](https://github.com/silinternational/ssp-base) in `/data/vendor/simplesamlphp/simplesamlphp/metadata/`.  These pull in metadata from all the files named `idp-*.php` and `sp-*.php` respectively, including those in sub-folders.

In order for forced re-authentication to be limited only to situations which warrant it, the `saml20-idp-hosted.php` file should include an authproc as such ...
>  [
>     'class' =>'sildisco:TrackIdps',
>  ]

#### IDP Remote metadata

##### IDPNamespace
Each metadata stanza should include an `IDPNamespace` entry that includes no special characters.  This is intended for namespacing the `NameId` value in the Auth Proc `AddIdp2NameId.php`.
It is also used by the `TagGroup.php` Auth Proc to convert group names into the form ...

`idp|<IDPNamespace>|<group name>`.

##### betaEnabled
An optional metadata entry is `betaEnabled`.  
This will allow the IdP to be marked as `'enable' => true` when the user has a certain cookie ('beta_tester') that they would get from visiting `hub_domain/module.php/sildisco/betatest.php`.  
The user would need to manually remove that cookie to be free of this effect.

Sildisco does not otherwise deal with looking at the `'enable'` value.  However, a theme for idp discovery may (e.g. simplesamlphp-module-material).

##### SPList
In order to limit access to an IdP to only certain SP's, add an `'SPList'` array entry to the metadata for the IdP.  The values of this array should match the `entity_id` values from the `sp-remote.php` metadata.

##### excludeByDefault
If you want to require SP's to list a certain IdP in their IDPList entry in order to be able to access it, add `excludeByDefault => true` to that IdP's metadata.

### Forced IdP discovery
The `.../lib/IdP/SAML2.php` file ensures that if an SP is allowed to access more than one IdP, then the user will be forced back to the IdP discovery page, even if they are already authenticated through one of those IdP's.

The reason for this is to ensure that the user has a chance to decide which of their identities is used for that SP.
