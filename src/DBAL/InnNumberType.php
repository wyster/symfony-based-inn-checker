<?php declare(strict_types=1);

namespace App\DBAL;

use App\InnNumber\InnNumber;
use App\InnNumber\InnNumberInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class InnNumberType extends Type
{
    private const TYPE = Types::BIGINT;

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getBinaryTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): InnNumberInterface
    {
        var_dump($value);
        die;
        return new InnNumber((int)$value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        if ($value instanceof InnNumberInterface) {
            return $value->getInn();
        }

        return null;
    }

    public function getName(): string
    {
        return self::TYPE;
    }
}
