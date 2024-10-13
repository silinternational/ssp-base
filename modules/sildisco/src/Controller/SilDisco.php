<?php

declare(strict_types=1);

namespace SimpleSAML\Module\sildisco\Controller;

use Exception;
use SimpleSAML\Error;
use SimpleSAML\HTTP\RunnableResponse;
use SimpleSAML\Module\sildisco\IdPDisco;
use Symfony\Component\HttpFoundation\Request;

class SilDisco
{
    /**
     * @param Request $request The current request.
     * @return RunnableResponse
     * @throws Error\Error
     */
    public function main(Request $request): RunnableResponse
    {
        try {
            $discoHandler = new IdPDisco(['saml20-idp-remote'], 'saml');
        } catch (Exception $exception) {
            // An error here should be caused by invalid query parameters
            throw new Error\Error('DISCOPARAMS', $exception);
        }

        try {
            return new RunnableResponse([$discoHandler, 'handleRequest'], []);
        } catch (Exception $exception) {
            // An error here should be caused by metadata
            throw new Error\Error('METADATA', $exception);
        }
    }
}
