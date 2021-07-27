<?php
/**
 * SAML 2.0 remote SP metadata for SimpleSAMLphp.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-sp-remote
 */

return [
    /*
     * Example SimpleSAMLphp SAML 2.0 SP
     */
    'http://ssp-hub-sp.local:8081' => [
        'IDPList' => [
            'http://ssp-hub-idp.local:8085',
            'http://ssp-hub-idp2.local:8086',
        ],
        'name' => "SP Local",
        'AssertionConsumerService' => 'http://ssp-hub-sp.local:8081/module.php/saml/sp/saml2-acs.php/ssp-hub',
        'SingleLogoutService' => 'http://ssp-hub-sp.local:8081/module.php/saml/sp/saml2-logout.php/ssp-hub',
        'certData' => 'MIIDzzCCAregAwIBAgIJAPnOHgSgAeNrMA0GCSqGSIb3DQEBCwUAMH4xCzAJBgNVBAYTAlVTMQswCQYDVQQIDAJOQzEPMA0GA1UEBwwGV2F4aGF3MQwwCgYDVQQKDANTSUwxDTALBgNVBAsMBEdUSVMxDjAMBgNVBAMMBVN0ZXZlMSQwIgYJKoZIhvcNAQkBFhVzdGV2ZV9iYWd3ZWxsQHNpbC5vcmcwHhcNMTYxMDE3MTIyNzU2WhcNMjYxMDE3MTIyNzU2WjB+MQswCQYDVQQGEwJVUzELMAkGA1UECAwCTkMxDzANBgNVBAcMBldheGhhdzEMMAoGA1UECgwDU0lMMQ0wCwYDVQQLDARHVElTMQ4wDAYDVQQDDAVTdGV2ZTEkMCIGCSqGSIb3DQEJARYVc3RldmVfYmFnd2VsbEBzaWwub3JnMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0u+mXWS8vUkKjtJcK1hd0iGW2vbTvYosgyDdqClcSzwpbWJg1A1ChuiQIf7S+5bWL2AN4zMoem/JTn7cE9octqU34ZJAyP/cesppA9G53F9gH4XdoPgnWsb8vdWooDDUk+asc7ah/XwKixQNcELPDZkOba5+pqoKGjMxfL7JQ6+P6LB+xItzvLBXU4+onbGPIF6pmZ8S74mt0J62Y6ne40BHx8FdrtBgdk5TFcDedW09rRJrTFpi3hGSUkcjqj84B+oLAb08Z0SHoELMp5Yh7Tg5QZ2c+S8I47tQjV72rNhUYhIyFuImzSg27R7aRJ6Jj6sK4zEg0Ai4VhO4RmgyzwIDAQABo1AwTjAdBgNVHQ4EFgQUgkYcMbT0o8kmxAz2O3+p1lDVj1MwHwYDVR0jBBgwFoAUgkYcMbT0o8kmxAz2O3+p1lDVj1MwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEANgyTgMVRghgL8klqvZvQpfh80XDPTZotJCc8mZJZ98YkNC8jnR2RIUJpah+XrgotlKNDOK3HMNuyKGgYcqcno4PdDXKbqp4yXmywdNbbEHwPWDGqZXULw2az+UVwPUZJcJyJuwJjy3diCJT53N9G0LqXfeEsV0OPQPaB2PWgYNraBd59fckmBTc298HuvsHtxUcoXM53ms2Ck6GygGwH1vCg7qyIRRQFL4DiSlnoS8jxt3IIpZZs9FAl1ejtFBepSne9kEo7lLhAWY1TQqRrRXNHngG/L70ZkZonE9TNK/9xIHuaawqWkV6WLnkhT0DHCOw67GP97MWzceyFw+n9Vg==',
        'assertion.encryption' => true,
    ],
    'http://ssp-hub-sp.local' => [
        'IDPList' => [
            'http://ssp-hub-idp.local',
            'http://ssp-hub-idp2.local',
        ],
        'name' => "SP Local",
        'AssertionConsumerService' => 'http://ssp-hub-sp.local/module.php/saml/sp/saml2-acs.php/ssp-hub',
        'SingleLogoutService' => 'http://ssp-hub-sp.local/module.php/saml/sp/saml2-logout.php/ssp-hub',
        'certData' => 'MIIDzzCCAregAwIBAgIJAPnOHgSgAeNrMA0GCSqGSIb3DQEBCwUAMH4xCzAJBgNVBAYTAlVTMQswCQYDVQQIDAJOQzEPMA0GA1UEBwwGV2F4aGF3MQwwCgYDVQQKDANTSUwxDTALBgNVBAsMBEdUSVMxDjAMBgNVBAMMBVN0ZXZlMSQwIgYJKoZIhvcNAQkBFhVzdGV2ZV9iYWd3ZWxsQHNpbC5vcmcwHhcNMTYxMDE3MTIyNzU2WhcNMjYxMDE3MTIyNzU2WjB+MQswCQYDVQQGEwJVUzELMAkGA1UECAwCTkMxDzANBgNVBAcMBldheGhhdzEMMAoGA1UECgwDU0lMMQ0wCwYDVQQLDARHVElTMQ4wDAYDVQQDDAVTdGV2ZTEkMCIGCSqGSIb3DQEJARYVc3RldmVfYmFnd2VsbEBzaWwub3JnMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0u+mXWS8vUkKjtJcK1hd0iGW2vbTvYosgyDdqClcSzwpbWJg1A1ChuiQIf7S+5bWL2AN4zMoem/JTn7cE9octqU34ZJAyP/cesppA9G53F9gH4XdoPgnWsb8vdWooDDUk+asc7ah/XwKixQNcELPDZkOba5+pqoKGjMxfL7JQ6+P6LB+xItzvLBXU4+onbGPIF6pmZ8S74mt0J62Y6ne40BHx8FdrtBgdk5TFcDedW09rRJrTFpi3hGSUkcjqj84B+oLAb08Z0SHoELMp5Yh7Tg5QZ2c+S8I47tQjV72rNhUYhIyFuImzSg27R7aRJ6Jj6sK4zEg0Ai4VhO4RmgyzwIDAQABo1AwTjAdBgNVHQ4EFgQUgkYcMbT0o8kmxAz2O3+p1lDVj1MwHwYDVR0jBBgwFoAUgkYcMbT0o8kmxAz2O3+p1lDVj1MwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEANgyTgMVRghgL8klqvZvQpfh80XDPTZotJCc8mZJZ98YkNC8jnR2RIUJpah+XrgotlKNDOK3HMNuyKGgYcqcno4PdDXKbqp4yXmywdNbbEHwPWDGqZXULw2az+UVwPUZJcJyJuwJjy3diCJT53N9G0LqXfeEsV0OPQPaB2PWgYNraBd59fckmBTc298HuvsHtxUcoXM53ms2Ck6GygGwH1vCg7qyIRRQFL4DiSlnoS8jxt3IIpZZs9FAl1ejtFBepSne9kEo7lLhAWY1TQqRrRXNHngG/L70ZkZonE9TNK/9xIHuaawqWkV6WLnkhT0DHCOw67GP97MWzceyFw+n9Vg==',
        'assertion.encryption' => true,
    ],

    'http://ssp-hub-sp2.local:8082' => [
        'AssertionConsumerService' => 'http://ssp-hub-sp2.local:8082/module.php/saml/sp/saml2-acs.php/ssp-hub',
        'SingleLogoutService' => 'http://ssp-hub-sp2.local:8082/module.php/saml/sp/saml2-logout.php/ssp-hub',
        'IDPList' => [
            'http://ssp-hub-idp2.local:8086',
        ],
        'name' => 'SP 2 (custom port)',
        'certData' => 'MIIDzzCCAregAwIBAgIJAPnOHgSgAeNrMA0GCSqGSIb3DQEBCwUAMH4xCzAJBgNVBAYTAlVTMQswCQYDVQQIDAJOQzEPMA0GA1UEBwwGV2F4aGF3MQwwCgYDVQQKDANTSUwxDTALBgNVBAsMBEdUSVMxDjAMBgNVBAMMBVN0ZXZlMSQwIgYJKoZIhvcNAQkBFhVzdGV2ZV9iYWd3ZWxsQHNpbC5vcmcwHhcNMTYxMDE3MTIyNzU2WhcNMjYxMDE3MTIyNzU2WjB+MQswCQYDVQQGEwJVUzELMAkGA1UECAwCTkMxDzANBgNVBAcMBldheGhhdzEMMAoGA1UECgwDU0lMMQ0wCwYDVQQLDARHVElTMQ4wDAYDVQQDDAVTdGV2ZTEkMCIGCSqGSIb3DQEJARYVc3RldmVfYmFnd2VsbEBzaWwub3JnMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0u+mXWS8vUkKjtJcK1hd0iGW2vbTvYosgyDdqClcSzwpbWJg1A1ChuiQIf7S+5bWL2AN4zMoem/JTn7cE9octqU34ZJAyP/cesppA9G53F9gH4XdoPgnWsb8vdWooDDUk+asc7ah/XwKixQNcELPDZkOba5+pqoKGjMxfL7JQ6+P6LB+xItzvLBXU4+onbGPIF6pmZ8S74mt0J62Y6ne40BHx8FdrtBgdk5TFcDedW09rRJrTFpi3hGSUkcjqj84B+oLAb08Z0SHoELMp5Yh7Tg5QZ2c+S8I47tQjV72rNhUYhIyFuImzSg27R7aRJ6Jj6sK4zEg0Ai4VhO4RmgyzwIDAQABo1AwTjAdBgNVHQ4EFgQUgkYcMbT0o8kmxAz2O3+p1lDVj1MwHwYDVR0jBBgwFoAUgkYcMbT0o8kmxAz2O3+p1lDVj1MwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEANgyTgMVRghgL8klqvZvQpfh80XDPTZotJCc8mZJZ98YkNC8jnR2RIUJpah+XrgotlKNDOK3HMNuyKGgYcqcno4PdDXKbqp4yXmywdNbbEHwPWDGqZXULw2az+UVwPUZJcJyJuwJjy3diCJT53N9G0LqXfeEsV0OPQPaB2PWgYNraBd59fckmBTc298HuvsHtxUcoXM53ms2Ck6GygGwH1vCg7qyIRRQFL4DiSlnoS8jxt3IIpZZs9FAl1ejtFBepSne9kEo7lLhAWY1TQqRrRXNHngG/L70ZkZonE9TNK/9xIHuaawqWkV6WLnkhT0DHCOw67GP97MWzceyFw+n9Vg==',
        'assertion.encryption' => true,
    ],
    'http://ssp-hub-sp2.local' => [
        'AssertionConsumerService' => 'http://ssp-hub-sp2.local/module.php/saml/sp/saml2-acs.php/ssp-hub',
        'SingleLogoutService' => 'http://ssp-hub-sp2.local/module.php/saml/sp/saml2-logout.php/ssp-hub',
        'IDPList' => [
            'http://ssp-hub-idp2.local',
        ],
        'name' => 'SP 2',
        'certData' => 'MIIDzzCCAregAwIBAgIJAPnOHgSgAeNrMA0GCSqGSIb3DQEBCwUAMH4xCzAJBgNVBAYTAlVTMQswCQYDVQQIDAJOQzEPMA0GA1UEBwwGV2F4aGF3MQwwCgYDVQQKDANTSUwxDTALBgNVBAsMBEdUSVMxDjAMBgNVBAMMBVN0ZXZlMSQwIgYJKoZIhvcNAQkBFhVzdGV2ZV9iYWd3ZWxsQHNpbC5vcmcwHhcNMTYxMDE3MTIyNzU2WhcNMjYxMDE3MTIyNzU2WjB+MQswCQYDVQQGEwJVUzELMAkGA1UECAwCTkMxDzANBgNVBAcMBldheGhhdzEMMAoGA1UECgwDU0lMMQ0wCwYDVQQLDARHVElTMQ4wDAYDVQQDDAVTdGV2ZTEkMCIGCSqGSIb3DQEJARYVc3RldmVfYmFnd2VsbEBzaWwub3JnMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0u+mXWS8vUkKjtJcK1hd0iGW2vbTvYosgyDdqClcSzwpbWJg1A1ChuiQIf7S+5bWL2AN4zMoem/JTn7cE9octqU34ZJAyP/cesppA9G53F9gH4XdoPgnWsb8vdWooDDUk+asc7ah/XwKixQNcELPDZkOba5+pqoKGjMxfL7JQ6+P6LB+xItzvLBXU4+onbGPIF6pmZ8S74mt0J62Y6ne40BHx8FdrtBgdk5TFcDedW09rRJrTFpi3hGSUkcjqj84B+oLAb08Z0SHoELMp5Yh7Tg5QZ2c+S8I47tQjV72rNhUYhIyFuImzSg27R7aRJ6Jj6sK4zEg0Ai4VhO4RmgyzwIDAQABo1AwTjAdBgNVHQ4EFgQUgkYcMbT0o8kmxAz2O3+p1lDVj1MwHwYDVR0jBBgwFoAUgkYcMbT0o8kmxAz2O3+p1lDVj1MwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEANgyTgMVRghgL8klqvZvQpfh80XDPTZotJCc8mZJZ98YkNC8jnR2RIUJpah+XrgotlKNDOK3HMNuyKGgYcqcno4PdDXKbqp4yXmywdNbbEHwPWDGqZXULw2az+UVwPUZJcJyJuwJjy3diCJT53N9G0LqXfeEsV0OPQPaB2PWgYNraBd59fckmBTc298HuvsHtxUcoXM53ms2Ck6GygGwH1vCg7qyIRRQFL4DiSlnoS8jxt3IIpZZs9FAl1ejtFBepSne9kEo7lLhAWY1TQqRrRXNHngG/L70ZkZonE9TNK/9xIHuaawqWkV6WLnkhT0DHCOw67GP97MWzceyFw+n9Vg==',
        'assertion.encryption' => true,
    ],
];
