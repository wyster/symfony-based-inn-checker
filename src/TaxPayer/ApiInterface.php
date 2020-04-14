<?php declare(strict_types=1);

namespace App\TaxPayer;

use App\InnNumber\InnNumberInterface;

interface ApiInterface
{
    public function getByInn(InnNumberInterface $innNumber): TaxPayerEntityInterface;
}
