<?php

use App\Controllers\HomeOwnerController;
use App\Processes\UploadHomeOwnerProcess;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Services\HomeOwnerService;

// Repositories
$container->set(UserRepositoryInterface::class, static function(): UserRepositoryInterface {
    return new UserRepository();
});

// Services
$container->set(HomeOwnerService::class, static function() use ($container): HomeOwnerService {
    return new HomeOwnerService(
        $container->get(UserRepositoryInterface::class)
    );
});

// Processes
$container->set(UploadHomeOwnerProcess::class, static function() use ($container): UploadHomeOwnerProcess {
    return new UploadHomeOwnerProcess(
        $container->get(HomeOwnerService::class)
    );
});

// Processes
$container->set(HomeOwnerController::class, static function() use ($container): HomeOwnerController {
    return new HomeOwnerController(
        $container->get(UploadHomeOwnerProcess::class)
    );
});
