<?php

namespace SimpleSAML\Module\mfa;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiClient
{
    private string $apiKey;
    private Client $httpClient;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->httpClient = new Client();
    }

    /**
     * @return mixed -- The JSON-decoded response to the API call.
     * @throws GuzzleException
     */
    public function call(string $apiUrl, array $queryParameters): mixed
    {
        $requestOptions = [
            'connect_timeout' => 10,
            'headers' => [
                'Accept' => 'application/json',
                'Accept-Encoding' => 'gzip',
                'Authorization' => 'Bearer ' . $this->apiKey,
            ],
            'http_errors' => false,
        ];

        if (!empty($queryParameters)) {
            $requestOptions['query'] = $queryParameters;
        }

        $response = $this->httpClient->get(
            $apiUrl,
            $requestOptions
        );
        $responseContents = $response->getBody()->getContents();
        return json_decode($responseContents, true);
    }
}
