<?php

namespace App\Controllers;

use App\Exceptions\RequestException;
use App\Processes\UploadHomeOwnerProcess;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HomeOwnerController extends AbstractController
{
    /** @var UploadHomeOwnerProcess */
    private $uploadHomeOwnerProcess;

    /**
     * HomeOwnerController constructor.
     * @param UploadHomeOwnerProcess $uploadHomeOwnerProcess
     */
    public function __construct(UploadHomeOwnerProcess $uploadHomeOwnerProcess)
    {
        $this->uploadHomeOwnerProcess = $uploadHomeOwnerProcess;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws \JsonException
     */
    public function upload(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $this->validateRequest($request);
            $homeOwnerData = $this->getCsv($request);
            $homeOwners = $this->uploadHomeOwnerProcess->process($homeOwnerData);
        } catch (RequestException $exception) {
            $response
                ->getBody()->write(json_encode(['message' => $exception->getMessage()], JSON_THROW_ON_ERROR));
            return $response
                ->withHeader('Content-Type', self::JSON)
                ->withStatus(self::BAD_REQUEST);
        } catch (\Throwable $exception) {
            // Log the error as it is usually a 500
            $response
                ->getBody()->write(json_encode(['message' => $exception->getMessage()], JSON_THROW_ON_ERROR));
            return $response
                ->withHeader('Content-Type', self::JSON)
                ->withStatus(self::INTERNAL_SERVER_ERROR);
        }

        $response->getBody()->write(json_encode(['message' => 'success', json_encode($homeOwners)], JSON_THROW_ON_ERROR));
        return $response
            ->withHeader('Content-Type', self::JSON)
            ->withStatus(self::CREATED);
    }
}
