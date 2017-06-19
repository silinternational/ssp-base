<?php
/**
 * SAML 2.0 remote IdP metadata for SimpleSAMLphp.
 *
 * Remember to remove the IdPs you don't use from this file.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-remote 
 */
return [
    /*
     * Guest IdP. Sign in with an "a" (lower case) in both the username and password
     */
    'http://ssp-hub-idp.local:8085' => [
        'metadata-set' => 'saml20-idp-remote',
        'entityid' => 'http://ssp-hub-idp.local:8085',
        'name' => [
          'en' => 'IDP 1',
        ],
        'IDPNamespace' => 'IDP-1',
        'enabled' => true,

        'description'          => 'Local IDP for testing SSP Hub',

        'SingleSignOnService'  => 'http://ssp-hub-idp.local:8085/saml2/idp/SSOService.php',
        'SingleLogoutService'  => 'http://ssp-hub-idp.local:8085/saml2/idp/SingleLogoutService.php',
      //  'certFingerprint'      => 'c9ed4dfb07caf13fc21e0fec1572047eb8a7a4cb'
        'certData' => 'MIIDzzCCAregAwIBAgIJAPlZYTAQSIbHMA0GCSqGSIb3DQEBCwUAMH4xCzAJBgNVBAYTAlVTMQswCQYDVQQIDAJOQzEPMA0GA1UEBwwGV2F4aGF3MQwwCgYDVQQKDANTSUwxDTALBgNVBAsMBEdUSVMxDjAMBgNVBAMMBVN0ZXZlMSQwIgYJKoZIhvcNAQkBFhVzdGV2ZV9iYWd3ZWxsQHNpbC5vcmcwHhcNMTYxMDE3MTIzMTQ1WhcNMjYxMDE3MTIzMTQ1WjB+MQswCQYDVQQGEwJVUzELMAkGA1UECAwCTkMxDzANBgNVBAcMBldheGhhdzEMMAoGA1UECgwDU0lMMQ0wCwYDVQQLDARHVElTMQ4wDAYDVQQDDAVTdGV2ZTEkMCIGCSqGSIb3DQEJARYVc3RldmVfYmFnd2VsbEBzaWwub3JnMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArssOaeKbdOQFpN6bBolwSJ/6QFBXA73Sotg60anx9v6aYdUTmi+b7SVtvOmHDgsD5X8pN/6Z11QCZfTYg2nW3ZevGZsj8W/R6C8lRLHzWUr7e7DXKfj8GKZptHlUs68kn0ndNVt9r/+irJe9KBdZ+4kAihykomNdeZg06bvkklxVcvpkOfLTQzEqJAmISPPIeOXes6hXORdqLuRNTuIKarcZ9rstLnpgAs2TE4XDOrSuUg3XFnM05eDpFQpUb0RXWcD16mLCPWw+CPrGoCfoftD5ZGfll+W2wZ7d0kQ4TbCpNyxQH35q65RPVyVNPgSNSsFFkmdcqP9DsFqjJ8YC6wIDAQABo1AwTjAdBgNVHQ4EFgQUD6oyJKOPPhvLQpDCC3027QcuQwUwHwYDVR0jBBgwFoAUD6oyJKOPPhvLQpDCC3027QcuQwUwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEAA6tCLHJQGfXGdFerQ3J0wUu8YDSLb0WJqPtGdIuyeiywR5ooJf8G/jjYMPgZArepLQSSi6t8/cjEdkYWejGnjMG323drQ9M1sKMUhOJF4po9R3t7IyvGAL3fSqjXA8JXH5MuGuGtChWxaqhduA0dBJhFAtAXQ61IuIQF7vSFxhTwCvJnaWdWD49sG5OqjCfgIQdY/mw70e45rLnR/bpfoigL67sTJxy+Kx2ogbvMR6lITByOEQFMt7BYpMtXrwvKUM7k9NOo1jREmJacC8PTx//jRhCWwzUj1RsfIri24BuITrawwqMsYl8DZiiwMpjUf9m4NPaf4E7+QRpzo+MCcg==',
    ],


/*
 * IdP2. Sign in with a "b" (lower case) in both the username and password
 */
    'http://ssp-hub-idp2.local:8086' => [
        'metadata-set' => 'saml20-idp-remote',
        'entityid' => 'http://ssp-hub-idp2.local:8086',
        'name' => [
          'en' => 'IDP 2',
        ],
        'IDPNamespace' => 'IDP-2',
        'enabled' => false,
        'betaEnabled' => true,

        'description'          => 'Local IDP2 for testing SSP Hub',

        'SingleSignOnService'  => 'http://ssp-hub-idp2.local:8086/saml2/idp/SSOService.php',
        'SingleLogoutService'  => 'http://ssp-hub-idp2.local:8086/saml2/idp/SingleLogoutService.php',
      //  'certFingerprint'      => 'c9ed4dfb07caf13fc21e0fec1572047eb8a7a4cb'
        'certData' => 'MIIDzzCCAregAwIBAgIJALBaUrvz1X5DMA0GCSqGSIb3DQEBCwUAMH4xCzAJBgNVBAYTAlVTMQswCQYDVQQIDAJOQzEPMA0GA1UEBwwGV2F4aGF3MQwwCgYDVQQKDANTSUwxDTALBgNVBAsMBEdUSVMxDjAMBgNVBAMMBVN0ZXZlMSQwIgYJKoZIhvcNAQkBFhVzdGV2ZV9iYWd3ZWxsQHNpbC5vcmcwHhcNMTYxMDE4MTQwMDUxWhcNMjYxMDE4MTQwMDUxWjB+MQswCQYDVQQGEwJVUzELMAkGA1UECAwCTkMxDzANBgNVBAcMBldheGhhdzEMMAoGA1UECgwDU0lMMQ0wCwYDVQQLDARHVElTMQ4wDAYDVQQDDAVTdGV2ZTEkMCIGCSqGSIb3DQEJARYVc3RldmVfYmFnd2VsbEBzaWwub3JnMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAx5mZNwjEnakJho+5etuFyx+2g9rs96iLX/LDC24aBAsdNxTNuIc1jJ7pxBxGrepEND4LkietLNBlOr1q50nq2+ddTrCfmoJB+9BqBOxcm9qWeqWbp8/arUjaxPzK3DfZrxJxIVFjzqFF7gI91y9yvEW/fqLRMhvnH1ns+N1ne59zr1y6h9mmHfBffGr1YXAfyEAuV1ich4AfTfjqhdwFwxhFLLCVnxA0bDbNw/0eGCSiA13N7a013xTurLeJu0AQaZYssMqvc/17UphH4gWDMEZAwy0EfRSBOsDOYCxeNxVajnWX1834VDpBDfpnZj996Gh8tzRQxQgT9/plHKhGiwIDAQABo1AwTjAdBgNVHQ4EFgQUApxlUQg26GrG3eH8lEG3SkqbH/swHwYDVR0jBBgwFoAUApxlUQg26GrG3eH8lEG3SkqbH/swDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEANhbm8WgIqBDlF7DIRVUbq04TEA9nOJG8wdjJYdoKrPX9f/E9slkFuD2StcK99RTcowa8Z2OmW7tksa+onyH611Lq21QXh4aHzQUAm2HbsmPQRZnkByeYoCJ/1tuEho+x+VGanaUICSBVWYiebAQVKHR6miFypRElibNBizm2nqp6Q9B87V8COzyDVngR1DlWDduxYaNOBgvht3Rk9Y2pVHqym42dIfN+pprcsB1PGBkY/BngIuS/aqTENbmoC737vcb06e8uzBsbCpHtqUBjPpL2psQZVJ2Y84JmHafC3B7nFQrjdZBbc9eMHfPo240Rh+pDLwxdxPqRAZdeLaUkCQ==',
    ],
    

    'dummy' => [
        'enabled' => false,
        'IDPCode' => 'dummy', // being replaced by IDPNamespace
        'IDPNamespace' => 'dummy',
        'logoURL' => '//static.gtis.guru/idp-logo/insite-logo.png',
        'metadata-set' => 'saml20-idp-remote',
        'entityid' => 'sil.qa.iidp.net',
        'SingleSignOnService' => [
            0 => [
                'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
                'Location' => 'https://sil.qa.iidp.net/saml2/idp/SSOService.php',
            ],
        ],
        'SingleLogoutService' => 'https://sil.qa.iidp.net/saml2/idp/SingleLogoutService.php',
        'certData' => 'MIID3jCCAsYCCQCN4bf8dThNHjANBgkqhkiG9w0BAQUFADCBsDELMAkGA1UEBhMCVVMxDjAMBgNVBAgTBVRleGFzMQ8wDQYDVQQHEwZEYWxsYXMxHzAdBgNVBAoTFlNJTCBJbnRlcm5hdGlvbmFsLCBJbmMxJDAiBgNVBAsTG0ZlZGVyYXRlZCBJZGVudGl0eSBTZXJ2aWNlczEVMBMGA1UEAxMMc2lsLmlpZHAubmV0MSIwIAYJKoZIhvcNAQkBFhNmc3RlaW5Ac2lsLmlpZHAubmV0MB4XDTExMDgxOTE5NDIxNFoXDTIxMDgxODE5NDIxNFowgbAxCzAJBgNVBAYTAlVTMQ4wDAYDVQQIEwVUZXhhczEPMA0GA1UEBxMGRGFsbGFzMR8wHQYDVQQKExZTSUwgSW50ZXJuYXRpb25hbCwgSW5jMSQwIgYDVQQLExtGZWRlcmF0ZWQgSWRlbnRpdHkgU2VydmljZXMxFTATBgNVBAMTDHNpbC5paWRwLm5ldDEiMCAGCSqGSIb3DQEJARYTZnN0ZWluQHNpbC5paWRwLm5ldDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAL+hBSOyfziuCE8rzVzPRQAkoxlWVsTzNok1vWH7PGvm5v1rPJwiNGrAioxh351f1LW1zLTk7WzxDfcP0z7kPE/mqfp5JjYN9WHGcBe6xnrBnapAloLgjvZy2/UAtthxU8C/vEKZznQK4C/q+XyjlSEz7mEw05TF1WmmjYmbZHcZBpbMiqgvOHgEP1u0Of/d6H5lO5F6YB1glEA0vTvXNOzpT/wIp/lh2R41UxnBLOaE7OGOSDSzMaeM5808vlPwl22Kj/d5sMOokhV7BAkKuMPRqS3QFghugnWj/7YKlC55hWkBsdyBhMT6014gLYI1YX46lOzlB+O5RyPplyOR7N8CAwEAATANBgkqhkiG9w0BAQUFAAOCAQEAP6Uz1YBG0N4hW8zP4UO10PbRmsKNYfMHc1v859HjFC5N8AajuY9J199CECF7Ae0jRNqjNJvFtkvhAxpk6D4r4Tb4E4SjNuIfOM/xGlycWf0ELhqx/qEJiPGZ4rfoWNLsEWQwF2sEiBYQz1/ZeeJEBR01VbmyHHTYeaT6P7xvA3fRzkM+D+bS0mEpesZblQ4TNyfcPlZMGePamjf2k76hSY1Gv7gMeaFtu1TOixSLbuiEJsdsFVMLpcqmwmvkTj2eghSUkG4kr1JLzj3/HkMmkszmMWkbKAclqzVjWuHHfRbCstBeYIh9yLHlgZZQ4kxDQ+L9cuWYmk/coVqDahsdnw==',
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:transient',
        'name' => [
            'en' => 'Dummy',
        ],
        'OrganizationName' => [
            'en' => 'SIL International',
        ],
        'OrganizationDisplayName' => [
            'en' => 'SIL International',
        ],
        'OrganizationURL' => [
            'en' => 'http://www.sil.org',
        ],
    ],
        
];

