<?php

namespace App\Tests;

use App\Exceptions\HomeOwnerProcessException;
use App\Processes\Results\UploadHomeOwnerResult;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\HomeOwnerService;
use PHPUnit\Framework\TestCase;

class HomeOwnerServiceTest extends TestCase
{
    /** @var UserRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject */
    private $userRepository;

    /** @var HomeOwnerService */
    private $homeOwnerService;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->userRepository = $this
            ->getMockBuilder(UserRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->homeOwnerService = new HomeOwnerService(
            $this->userRepository
        );
    }

    /**
     * @covers \App\Services\HomeOwnerService::canStoreHomeOwner
     */
    public function test_user_has_valid_name(): void
    {
        $this->userRepository
            ->expects($this->never())
            ->method('save');
        $this->assertTrue($this->homeOwnerService->canStoreHomeOwner('Mr Tom Staff and Mr John Doe'));
    }

    /**
     * @covers \App\Services\HomeOwnerService::canStoreHomeOwner
     */
    public function test_user_has_invalid_name(): void
    {
        $this->userRepository
            ->expects($this->never())
            ->method('save');
        $this->assertFalse($this->homeOwnerService->canStoreHomeOwner('this_test_should_fail'));
    }

    /**
     * @covers \App\Services\HomeOwnerService::storeHomeOwner
     */
    public function test_store_successfully(): void
    {
        $uploadHomeOwnerResult = new UploadHomeOwnerResult();
        $this->userRepository
            ->expects($this->exactly(2))
            ->method('save');
        try {
            $this->homeOwnerService->storeHomeOwner('Mr Tom Staff and Mr John Doe', $uploadHomeOwnerResult);
        } catch (\Throwable $exception) {
            $this->fail($exception->getMessage());
        }
        $this->assertNotEmpty($uploadHomeOwnerResult->serialise());
    }

    /**
     * @covers \App\Services\HomeOwnerService::storeHomeOwner
     */
    public function test_store_throws_error(): void
    {
        $uploadHomeOwnerResult = new UploadHomeOwnerResult();
        $this->userRepository
            ->expects($this->never())
            ->method('save');

        $this->expectException(HomeOwnerProcessException::class);
        $this->homeOwnerService->storeHomeOwner('this_test_should_fail', $uploadHomeOwnerResult);
    }
}
