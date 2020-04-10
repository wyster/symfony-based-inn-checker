<?php declare(strict_types=1);

namespace App\Service;

interface InnServiceInterface
{
    public function isTaxPayer(int $inn): bool;
}
