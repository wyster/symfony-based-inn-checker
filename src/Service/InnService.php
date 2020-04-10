<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\TaxPayer as TaxPayerEntity;
use App\TaxPayer;
use App\Repository\TaxPayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

final class InnService implements InnServiceInterface
{
    private const ROW_ACTUALITY_INTERVAL = 60 * 60 * 24;

    private ObjectManager $entityManager;
    private TaxPayer\ApiInterface $api;

    public function __construct(EntityManagerInterface $entityManager, TaxPayer\ApiInterface $api)
    {
        $this->entityManager = $entityManager;
        $this->api = $api;
    }

    public function isTaxPayer(int $inn): bool
    {
        /**
         * @var TaxPayerRepository $taxPayerRepository
         */
        $taxPayerRepository = $this->entityManager->getRepository(TaxPayerEntity::class);

        $row = $taxPayerRepository->getByInn($inn);
        if ($row !== null && $this->isActualRow($row)) {
            return $row->isPays();
        }

        $response = $this->api->getTaxpayerStatus($inn);

        if ($row === null) {
            $row = new TaxPayerEntity();
            $row->setInn($inn);
        }

        // @todo где гарантии что тут bool?
        $row->setPays($response['status']);

        $this->entityManager->persist($row);
        $this->entityManager->flush();

        return $row->isPays();
    }

    private function isActualRow(TaxPayerEntity $row): bool
    {
        return $row->getUpdatedAt()->getTimestamp() >= time() - self::ROW_ACTUALITY_INTERVAL;
    }
}
