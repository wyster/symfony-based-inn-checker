<?php declare(strict_types=1);

namespace App\Validator;

use devsergeev\validators\InnValidator as InnChecker;
use InvalidArgumentException;

final class InnValidator
{
    private const INN_LENGTH = 12;

    private array $messages = [];

    public function isValid(int $value): bool
    {
        $this->messages = [];

        if (mb_strlen((string)$value) !== self::INN_LENGTH) {
            $this->messages[] = sprintf('ИНН физического лица должен состоять из %s цифр', self::INN_LENGTH);
            return false;
        }

        try {
            InnChecker::check((string)$value);
        } catch (InvalidArgumentException $e) {
            $this->messages[] = $e->getMessage();
            return false;
        }

        return true;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
