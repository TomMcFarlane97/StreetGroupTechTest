<?php

namespace App\Processes;

use App\Entities\User;
use App\Exceptions\HomeOwnerProcessException;
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
     * @return User[]
     * @throws \Exception
     */
    public function process(array $homeOwnerData): array
    {
        $users = [];
        try {
            $this->homeOwnerService->beginTransaction();
            foreach ($homeOwnerData as $homeOwner) {
                if ($this->homeOwnerService->canStoreHomeOwner($homeOwner) === false) {
                    try {
                        $users[] = $this->homeOwnerService->storeHomeOwner($homeOwner);
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
        return $users;
    }
}
