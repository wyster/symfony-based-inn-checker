<?php declare(strict_types=1);

namespace App\TaxPayer;

interface ApiInterface
{
    public function getByInn(int $inn): TaxPayerEntityInterface;
}
