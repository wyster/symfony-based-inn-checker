<?php declare(strict_types=1);

namespace App\TaxPayer;

interface TaxPayerEntityInterface
{
    public function isPayTaxes(): bool;
}
