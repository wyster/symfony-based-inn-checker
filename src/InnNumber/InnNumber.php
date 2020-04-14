<?php declare(strict_types=1);

namespace App\InnNumber;

use App\Validator\InnValidator;
use InvalidArgumentException;

final class InnNumber implements InnNumberInterface
{
    private int $inn;

    public function __construct(int $inn)
    {
        if (!(new InnValidator())->isValid($inn)) {
            throw new InvalidArgumentException('Invalid inn');
        }

        $this->inn = $inn;
    }

    public function getInn(): int
    {
        return $this->inn;
    }
}
