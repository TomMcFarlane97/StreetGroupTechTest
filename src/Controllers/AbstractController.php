<?php

namespace App\Controllers;

use App\Exceptions\RequestException;
use Psr\Http\Message\RequestInterface;

abstract class AbstractController
{
    protected const JSON = 'application/json';
    protected const CSV = 'text/csv';
    protected const BAD_REQUEST = 400;
    protected const CREATED = 201;
    protected const INTERNAL_SERVER_ERROR = 500;

    /**
     * Validates is JSON request
     * @param RequestInterface $request
     * @throws RequestException
     */
    protected function validateRequest(RequestInterface $request): void
    {
        $contentType = $request->getHeader('Content-type');
        if (!empty($contentType[0]) && false === strpos($contentType[0], self::CSV)
            && !$request->getBody()->eof()
        ) {
            throw new RequestException('Must be type CSV');
        }

        $acceptHeader = $request->getHeader('Accept');
        if (!empty($acceptHeader[0]) && false === strpos($acceptHeader[0], self::JSON)) {
            throw new RequestException('Must be type JSON');
        }
    }

    /**
     * @param RequestInterface $request
     * @return string[]
     * @throws RequestException
     */
    protected function getCsv(RequestInterface $request): array
    {
        $i = 0;
        $data = '';
        if ($request->getBody()->isReadable() === false) {
            throw new RequestException('Unable to read CSV file');
        }
        return str_getcsv($request->getBody()->__toString());
    }
}
