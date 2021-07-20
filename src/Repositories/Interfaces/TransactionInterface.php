<?php

namespace App\Repositories\Interfaces;

interface TransactionInterface
{
    /**
     * @return void
     */
    public function beginTransaction(): void;

    /**
     * @return void
     */
    public function commitTransaction(): void;

    /**
     * @return void
     */
    public function rollbackTransaction(): void;
}
