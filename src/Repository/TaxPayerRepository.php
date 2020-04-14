<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TaxPayer;
use App\InnNumber\InnNumberInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TaxPayer|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaxPayer|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaxPayer[]    findAll()
 * @method TaxPayer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaxPayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaxPayer::class);
    }

    public function getByInn(InnNumberInterface $value): ?TaxPayer
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.inn = :val')
            ->setParameter('val', $value->getInn())
            ->getQuery()
            ->getOneOrNullResult();
    }
}
