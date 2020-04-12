<?php declare(strict_types=1);

namespace Test;

use App\Service\InnService;
use App\Service\InnServiceInterface;
use App\TaxPayer\ApiInterface;
use App\TaxPayer\TaxPayerEntity;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;

final class InnServiceTest extends Unit
{
    private const VALID_INN = 366221019350;
    private const INVALID_INN = 366221019361;

    protected UnitTester $tester;

    public function testIsNotTaxPayer(): void
    {
        $taxPayer = new TaxPayerEntity();
        $taxPayer->setPayTaxes(false);
        $apiMock = $this->createMock(ApiInterface::class);
        $apiMock->method('getByInn')->willReturn($taxPayer);

        $innService = $this->createInnService($apiMock);

        $this->assertFalse($innService->isTaxPayer(self::INVALID_INN));
    }

    public function testIsTaxPayer(): void
    {
        $taxPayer = new TaxPayerEntity();
        $taxPayer->setPayTaxes(true);
        $apiMock = $this->createMock(ApiInterface::class);
        $apiMock->expects($this->once())->method('getByInn')->willReturn($taxPayer);

        $innService = $this->createInnService($apiMock);

        // Получение из апи
        $this->assertTrue($innService->isTaxPayer(self::VALID_INN));

        // Получение из базы
        $this->assertTrue($innService->isTaxPayer(self::VALID_INN));
    }

    private function createInnService(ApiInterface $apiMock): InnServiceInterface
    {
        return new InnService(
            $this->tester->grabService(EntityManagerInterface::class),
            $apiMock
        );
    }
}
