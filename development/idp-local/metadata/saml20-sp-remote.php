<?php
/**
 * SAML 2.0 remote SP metadata for SimpleSAMLphp.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-sp-remote
 */

/*
 * Example SimpleSAMLphp SAML 2.0 SP
 */
$metadata['ssp-hub.local'] = [
    'entityid' => 'ssp-hub.local',
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
    'AssertionConsumerService' => 'http://ssp-hub.local/module.php/saml/sp/saml2-acs.php/hub-discovery',
    'SingleLogoutService' => 'http://ssp-hub.local/module.php/saml/sp/saml2-logout.php/hub-discovery',
    'certData' => 'MIIDzzCCAregAwIBAgIJANuvVcQPANecMA0GCSqGSIb3DQEBCwUAMH4xCzAJBgNVBAYTAlVTMQswCQYDVQQIDAJOQzEPMA0GA1UEBwwGV2F4aGF3MQwwCgYDVQQKDANTSUwxDTALBgNVBAsMBEdUSVMxDjAMBgNVBAMMBVN0ZXZlMSQwIgYJKoZIhvcNAQkBFhVzdGV2ZV9iYWd3ZWxsQHNpbC5vcmcwHhcNMTYxMDE3MTIzMTEyWhcNMjYxMDE3MTIzMTEyWjB+MQswCQYDVQQGEwJVUzELMAkGA1UECAwCTkMxDzANBgNVBAcMBldheGhhdzEMMAoGA1UECgwDU0lMMQ0wCwYDVQQLDARHVElTMQ4wDAYDVQQDDAVTdGV2ZTEkMCIGCSqGSIb3DQEJARYVc3RldmVfYmFnd2VsbEBzaWwub3JnMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxAimEkw4Teyf/gZelL7OuQYg/JbDIKHPXJhLPBm/HK6pM5ZZKydVXTdMgMqkl4xK+xZ2CnkozsUiMLhAuWBsX9Dcz1M4SkPRwk4puFhXzsp7fKIVP43zUhF7p2TmbernrrIQHjg6PuegKmCGyiKUpukcYvf2RXNwHwJx+Uq0zLP4PgBSrQ2t1eKZ1jQ+noBb1NqOuy969WRYmN4EmjXDuJB9d+b3GwtbZToWgiFxFjd/NN9BFJXZEaLzRj5LAq5bu2vPPDZDarHFMRUzVJ91eafoaz6zpR1iUGj9zR+y2sUPxD/fJMZ+4AHWA2LOrTBBIuuWbp96yvcJ4WjmlfhcFQIDAQABo1AwTjAdBgNVHQ4EFgQUkJFAMJdr2lXsuezS6pDXHnmJspMwHwYDVR0jBBgwFoAUkJFAMJdr2lXsuezS6pDXHnmJspMwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEAOEPbchaUr45L5i+ueookevsABYnltwJZ4rYJbF9VURPcEhB6JxTMZqb4s113ftHvVYfoAfLYZ9swETaHL+esx41yAebf0kWpQ3f63S5F2FcrTj+HP0XsvW/EDrvaTKM9jnKPNmbXrpq06eaUZfkVL0TAUsxYTKkttTSTiESEzp5wzYyhp7l3kpHhEvGOlh5suYjnZ2HN0uxscCR6PS47H6TMMEZuG032DWDC016/JniWvERtpf4Yw26V+I9xevp2E2MPcZne31Pe3sCh4Wpe4cV/SCFqZHlpnH96ncz4F+KvmmhbEx5VPhQSJNFIWEvI86k+lTNQOqj6YVvGvq95LQ==',
    'assertion.encryption' => true,
];
