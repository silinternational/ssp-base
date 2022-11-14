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
     * Guest IdP. Sign in with an "a" (lower case) as the password
     */
    'http://ssp-hub-idp.local:8085' => [
        'metadata-set' => 'saml20-idp-remote',
        'entityid' => 'http://ssp-hub-idp.local:8085',
        'name' => [
          'en' => 'IDP 1',
        ],
        'IDPNamespace' => 'IDP-1-custom-port',
        'logoCaption' => 'IDP-1:8085 staff',
        'enabled' => true,
        'logoURL' => 'https://dummyimage.com/125x125/0f4fbd/ffffff.png&text=IDP+1+8085',

        'description'          => 'Local IDP for testing SSP Hub (custom port)',

        'SingleSignOnService'  => 'http://ssp-hub-idp.local:8085/saml2/idp/SSOService.php',
        'SingleLogoutService'  => 'http://ssp-hub-idp.local:8085/saml2/idp/SingleLogoutService.php',
        'certData' => 'MIIDzzCCAregAwIBAgIJAPlZYTAQSIbHMA0GCSqGSIb3DQEBCwUAMH4xCzAJBgNVBAYTAlVTMQswCQYDVQQIDAJOQzEPMA0GA1UEBwwGV2F4aGF3MQwwCgYDVQQKDANTSUwxDTALBgNVBAsMBEdUSVMxDjAMBgNVBAMMBVN0ZXZlMSQwIgYJKoZIhvcNAQkBFhVzdGV2ZV9iYWd3ZWxsQHNpbC5vcmcwHhcNMTYxMDE3MTIzMTQ1WhcNMjYxMDE3MTIzMTQ1WjB+MQswCQYDVQQGEwJVUzELMAkGA1UECAwCTkMxDzANBgNVBAcMBldheGhhdzEMMAoGA1UECgwDU0lMMQ0wCwYDVQQLDARHVElTMQ4wDAYDVQQDDAVTdGV2ZTEkMCIGCSqGSIb3DQEJARYVc3RldmVfYmFnd2VsbEBzaWwub3JnMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArssOaeKbdOQFpN6bBolwSJ/6QFBXA73Sotg60anx9v6aYdUTmi+b7SVtvOmHDgsD5X8pN/6Z11QCZfTYg2nW3ZevGZsj8W/R6C8lRLHzWUr7e7DXKfj8GKZptHlUs68kn0ndNVt9r/+irJe9KBdZ+4kAihykomNdeZg06bvkklxVcvpkOfLTQzEqJAmISPPIeOXes6hXORdqLuRNTuIKarcZ9rstLnpgAs2TE4XDOrSuUg3XFnM05eDpFQpUb0RXWcD16mLCPWw+CPrGoCfoftD5ZGfll+W2wZ7d0kQ4TbCpNyxQH35q65RPVyVNPgSNSsFFkmdcqP9DsFqjJ8YC6wIDAQABo1AwTjAdBgNVHQ4EFgQUD6oyJKOPPhvLQpDCC3027QcuQwUwHwYDVR0jBBgwFoAUD6oyJKOPPhvLQpDCC3027QcuQwUwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEAA6tCLHJQGfXGdFerQ3J0wUu8YDSLb0WJqPtGdIuyeiywR5ooJf8G/jjYMPgZArepLQSSi6t8/cjEdkYWejGnjMG323drQ9M1sKMUhOJF4po9R3t7IyvGAL3fSqjXA8JXH5MuGuGtChWxaqhduA0dBJhFAtAXQ61IuIQF7vSFxhTwCvJnaWdWD49sG5OqjCfgIQdY/mw70e45rLnR/bpfoigL67sTJxy+Kx2ogbvMR6lITByOEQFMt7BYpMtXrwvKUM7k9NOo1jREmJacC8PTx//jRhCWwzUj1RsfIri24BuITrawwqMsYl8DZiiwMpjUf9m4NPaf4E7+QRpzo+MCcg==',
    ],
    'http://ssp-hub-idp.local' => [
        'metadata-set' => 'saml20-idp-remote',
        'entityid' => 'http://ssp-hub-idp.local',
        'name' => [
          'en' => 'IDP 1',
        ],
        'IDPNamespace' => 'IDP-1',
        'logoCaption' => 'IDP-1 staff',
        'enabled' => true,
        'logoURL' => 'https://dummyimage.com/125x125/0f4fbd/ffffff.png&text=IDP+1',

        'description'          => 'Local IDP for testing SSP Hub (default port)',

        'SingleSignOnService'  => 'http://ssp-hub-idp.local/saml2/idp/SSOService.php',
        'SingleLogoutService'  => 'http://ssp-hub-idp.local/saml2/idp/SingleLogoutService.php',
      //  'certFingerprint'      => 'c9ed4dfb07caf13fc21e0fec1572047eb8a7a4cb'
        'certData' => 'MIIDzzCCAregAwIBAgIJAPlZYTAQSIbHMA0GCSqGSIb3DQEBCwUAMH4xCzAJBgNVBAYTAlVTMQswCQYDVQQIDAJOQzEPMA0GA1UEBwwGV2F4aGF3MQwwCgYDVQQKDANTSUwxDTALBgNVBAsMBEdUSVMxDjAMBgNVBAMMBVN0ZXZlMSQwIgYJKoZIhvcNAQkBFhVzdGV2ZV9iYWd3ZWxsQHNpbC5vcmcwHhcNMTYxMDE3MTIzMTQ1WhcNMjYxMDE3MTIzMTQ1WjB+MQswCQYDVQQGEwJVUzELMAkGA1UECAwCTkMxDzANBgNVBAcMBldheGhhdzEMMAoGA1UECgwDU0lMMQ0wCwYDVQQLDARHVElTMQ4wDAYDVQQDDAVTdGV2ZTEkMCIGCSqGSIb3DQEJARYVc3RldmVfYmFnd2VsbEBzaWwub3JnMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArssOaeKbdOQFpN6bBolwSJ/6QFBXA73Sotg60anx9v6aYdUTmi+b7SVtvOmHDgsD5X8pN/6Z11QCZfTYg2nW3ZevGZsj8W/R6C8lRLHzWUr7e7DXKfj8GKZptHlUs68kn0ndNVt9r/+irJe9KBdZ+4kAihykomNdeZg06bvkklxVcvpkOfLTQzEqJAmISPPIeOXes6hXORdqLuRNTuIKarcZ9rstLnpgAs2TE4XDOrSuUg3XFnM05eDpFQpUb0RXWcD16mLCPWw+CPrGoCfoftD5ZGfll+W2wZ7d0kQ4TbCpNyxQH35q65RPVyVNPgSNSsFFkmdcqP9DsFqjJ8YC6wIDAQABo1AwTjAdBgNVHQ4EFgQUD6oyJKOPPhvLQpDCC3027QcuQwUwHwYDVR0jBBgwFoAUD6oyJKOPPhvLQpDCC3027QcuQwUwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEAA6tCLHJQGfXGdFerQ3J0wUu8YDSLb0WJqPtGdIuyeiywR5ooJf8G/jjYMPgZArepLQSSi6t8/cjEdkYWejGnjMG323drQ9M1sKMUhOJF4po9R3t7IyvGAL3fSqjXA8JXH5MuGuGtChWxaqhduA0dBJhFAtAXQ61IuIQF7vSFxhTwCvJnaWdWD49sG5OqjCfgIQdY/mw70e45rLnR/bpfoigL67sTJxy+Kx2ogbvMR6lITByOEQFMt7BYpMtXrwvKUM7k9NOo1jREmJacC8PTx//jRhCWwzUj1RsfIri24BuITrawwqMsYl8DZiiwMpjUf9m4NPaf4E7+QRpzo+MCcg==',
    ],

    /*
     * IdP2. Sign in with a "b" (lower case) as the password
     */
    'http://ssp-hub-idp2.local:8086' => [
        'metadata-set' => 'saml20-idp-remote',
        'entityid' => 'http://ssp-hub-idp2.local:8086',
        'name' => [
          'en' => 'IDP 2',
        ],
        'IDPNamespace' => 'IDP-2-custom-port',
        'logoCaption' => 'IDP-2:8086 staff',
        'enabled' => false,
        'betaEnabled' => true,
        'logoURL' => 'https://dummyimage.com/125x125/0f4fbd/ffffff.png&text=IDP+2+8086',

        'description'          => 'Local IDP2 for testing SSP Hub (custom port)',

        'SingleSignOnService'  => 'http://ssp-hub-idp2.local:8086/saml2/idp/SSOService.php',
        'SingleLogoutService'  => 'http://ssp-hub-idp2.local:8086/saml2/idp/SingleLogoutService.php',
        'certData' => 'MIIDzzCCAregAwIBAgIJALBaUrvz1X5DMA0GCSqGSIb3DQEBCwUAMH4xCzAJBgNVBAYTAlVTMQswCQYDVQQIDAJOQzEPMA0GA1UEBwwGV2F4aGF3MQwwCgYDVQQKDANTSUwxDTALBgNVBAsMBEdUSVMxDjAMBgNVBAMMBVN0ZXZlMSQwIgYJKoZIhvcNAQkBFhVzdGV2ZV9iYWd3ZWxsQHNpbC5vcmcwHhcNMTYxMDE4MTQwMDUxWhcNMjYxMDE4MTQwMDUxWjB+MQswCQYDVQQGEwJVUzELMAkGA1UECAwCTkMxDzANBgNVBAcMBldheGhhdzEMMAoGA1UECgwDU0lMMQ0wCwYDVQQLDARHVElTMQ4wDAYDVQQDDAVTdGV2ZTEkMCIGCSqGSIb3DQEJARYVc3RldmVfYmFnd2VsbEBzaWwub3JnMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAx5mZNwjEnakJho+5etuFyx+2g9rs96iLX/LDC24aBAsdNxTNuIc1jJ7pxBxGrepEND4LkietLNBlOr1q50nq2+ddTrCfmoJB+9BqBOxcm9qWeqWbp8/arUjaxPzK3DfZrxJxIVFjzqFF7gI91y9yvEW/fqLRMhvnH1ns+N1ne59zr1y6h9mmHfBffGr1YXAfyEAuV1ich4AfTfjqhdwFwxhFLLCVnxA0bDbNw/0eGCSiA13N7a013xTurLeJu0AQaZYssMqvc/17UphH4gWDMEZAwy0EfRSBOsDOYCxeNxVajnWX1834VDpBDfpnZj996Gh8tzRQxQgT9/plHKhGiwIDAQABo1AwTjAdBgNVHQ4EFgQUApxlUQg26GrG3eH8lEG3SkqbH/swHwYDVR0jBBgwFoAUApxlUQg26GrG3eH8lEG3SkqbH/swDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEANhbm8WgIqBDlF7DIRVUbq04TEA9nOJG8wdjJYdoKrPX9f/E9slkFuD2StcK99RTcowa8Z2OmW7tksa+onyH611Lq21QXh4aHzQUAm2HbsmPQRZnkByeYoCJ/1tuEho+x+VGanaUICSBVWYiebAQVKHR6miFypRElibNBizm2nqp6Q9B87V8COzyDVngR1DlWDduxYaNOBgvht3Rk9Y2pVHqym42dIfN+pprcsB1PGBkY/BngIuS/aqTENbmoC737vcb06e8uzBsbCpHtqUBjPpL2psQZVJ2Y84JmHafC3B7nFQrjdZBbc9eMHfPo240Rh+pDLwxdxPqRAZdeLaUkCQ==',
    ],
    'http://ssp-hub-idp2.local' => [
        'metadata-set' => 'saml20-idp-remote',
        'entityid' => 'http://ssp-hub-idp2.local',
        'name' => [
          'en' => 'IDP 2',
        ],
        'IDPNamespace' => 'IDP-2',
        'logoCaption' => 'IDP-2 staff',
        'enabled' => false,
        'betaEnabled' => true,
        'logoURL' => 'https://dummyimage.com/125x125/0f4fbd/ffffff.png&text=IDP+2',

        'description'          => 'Local IDP2 for testing SSP Hub (normal port)',

        'SingleSignOnService'  => 'http://ssp-hub-idp2.local/saml2/idp/SSOService.php',
        'SingleLogoutService'  => 'http://ssp-hub-idp2.local/saml2/idp/SingleLogoutService.php',
        'certData' => 'MIIDzzCCAregAwIBAgIJALBaUrvz1X5DMA0GCSqGSIb3DQEBCwUAMH4xCzAJBgNVBAYTAlVTMQswCQYDVQQIDAJOQzEPMA0GA1UEBwwGV2F4aGF3MQwwCgYDVQQKDANTSUwxDTALBgNVBAsMBEdUSVMxDjAMBgNVBAMMBVN0ZXZlMSQwIgYJKoZIhvcNAQkBFhVzdGV2ZV9iYWd3ZWxsQHNpbC5vcmcwHhcNMTYxMDE4MTQwMDUxWhcNMjYxMDE4MTQwMDUxWjB+MQswCQYDVQQGEwJVUzELMAkGA1UECAwCTkMxDzANBgNVBAcMBldheGhhdzEMMAoGA1UECgwDU0lMMQ0wCwYDVQQLDARHVElTMQ4wDAYDVQQDDAVTdGV2ZTEkMCIGCSqGSIb3DQEJARYVc3RldmVfYmFnd2VsbEBzaWwub3JnMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAx5mZNwjEnakJho+5etuFyx+2g9rs96iLX/LDC24aBAsdNxTNuIc1jJ7pxBxGrepEND4LkietLNBlOr1q50nq2+ddTrCfmoJB+9BqBOxcm9qWeqWbp8/arUjaxPzK3DfZrxJxIVFjzqFF7gI91y9yvEW/fqLRMhvnH1ns+N1ne59zr1y6h9mmHfBffGr1YXAfyEAuV1ich4AfTfjqhdwFwxhFLLCVnxA0bDbNw/0eGCSiA13N7a013xTurLeJu0AQaZYssMqvc/17UphH4gWDMEZAwy0EfRSBOsDOYCxeNxVajnWX1834VDpBDfpnZj996Gh8tzRQxQgT9/plHKhGiwIDAQABo1AwTjAdBgNVHQ4EFgQUApxlUQg26GrG3eH8lEG3SkqbH/swHwYDVR0jBBgwFoAUApxlUQg26GrG3eH8lEG3SkqbH/swDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEANhbm8WgIqBDlF7DIRVUbq04TEA9nOJG8wdjJYdoKrPX9f/E9slkFuD2StcK99RTcowa8Z2OmW7tksa+onyH611Lq21QXh4aHzQUAm2HbsmPQRZnkByeYoCJ/1tuEho+x+VGanaUICSBVWYiebAQVKHR6miFypRElibNBizm2nqp6Q9B87V8COzyDVngR1DlWDduxYaNOBgvht3Rk9Y2pVHqym42dIfN+pprcsB1PGBkY/BngIuS/aqTENbmoC737vcb06e8uzBsbCpHtqUBjPpL2psQZVJ2Y84JmHafC3B7nFQrjdZBbc9eMHfPo240Rh+pDLwxdxPqRAZdeLaUkCQ==',
    ],
];
