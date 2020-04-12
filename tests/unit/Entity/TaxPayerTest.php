<?php declare(strict_types=1);

namespace Test\Entity;

use App\Entity\TaxPayer;
use Codeception\Test\Unit;
use DateTime;
use ReflectionProperty;
use Test\UnitTester;

final class TaxPayerTest extends Unit
{
    protected UnitTester $tester;

    public function testSetGetId(): void
    {
        $value = 1;
        $entity = new TaxPayer();
        $this->assertNull($entity->getId());
        $prop = new ReflectionProperty($entity, 'id');
        $prop->setAccessible(true);
        $prop->setValue($entity, $value);
        $this->assertSame($value, $entity->getId());
    }

    public function testSetGetUpdatedAt(): void
    {
        $value = new DateTime();
        $entity = new TaxPayer();
        $prop = new ReflectionProperty($entity, 'updatedAt');
        $prop->setAccessible(true);
        $prop->setValue($entity, $value);
        $this->assertSame($value, $entity->getUpdatedAt());
    }

    public function testSetGetInn(): void
    {
        $value = 366221019350;
        $entity = new TaxPayer();
        $prop = new ReflectionProperty($entity, 'inn');
        $prop->setAccessible(true);
        $prop->setValue($entity, $value);
        $this->assertSame($value, $entity->getInn());
    }
}
