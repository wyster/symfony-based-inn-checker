<?php declare(strict_types=1);

namespace App\TaxPayer;

final class TaxPayerEntity implements TaxPayerEntityInterface
{
    private bool $payTaxes = false;

    public function setPayTaxes(bool $payTaxes): void
    {
        $this->payTaxes = $payTaxes;
    }

    public function isPayTaxes(): bool
    {
        return $this->payTaxes;
    }
}
