<?php

namespace App\Processes;

use App\Entities\User;
use App\Exceptions\HomeOwnerProcessException;
use App\Processes\Results\UploadHomeOwnerResult;
use App\Services\HomeOwnerService;

class UploadHomeOwnerProcess
{
    /** @var HomeOwnerService  */
    private $homeOwnerService;

    /**
     * UploadHomeOwnerProcess constructor.
     * @param HomeOwnerService $homeOwnerService
     */
    public function __construct(HomeOwnerService $homeOwnerService)
    {
        $this->homeOwnerService = $homeOwnerService;
    }

    /**
     * @param string[] $homeOwnerData
     * @return UploadHomeOwnerResult
     * @throws \Exception
     */
    public function process(array $homeOwnerData): UploadHomeOwnerResult
    {
        $uploadHomeOwnerResult = new UploadHomeOwnerResult();
        try {
            $this->homeOwnerService->beginTransaction();
            foreach ($homeOwnerData as $homeOwner) {
                if ($this->homeOwnerService->canStoreHomeOwner($homeOwner) === true) {
                    try {
                        $this->homeOwnerService->storeHomeOwner($homeOwner, $uploadHomeOwnerResult);
                    } catch (HomeOwnerProcessException $exception) {
                        // log exception
                    }
                }
            }
            $this->homeOwnerService->commitTransaction();
        } catch (\Exception $exception) {
            // log exception, rollback transaction and throw the exception up
            $this->homeOwnerService->rollbackTransaction();
            throw $exception;
        }

        return $uploadHomeOwnerResult;
    }
}
