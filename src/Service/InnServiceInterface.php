<?php declare(strict_types=1);

namespace App\Service;

use App\InnNumber\InnNumberInterface;

interface InnServiceInterface
{
    public function isTaxPayer(InnNumberInterface $inn): bool;
}
