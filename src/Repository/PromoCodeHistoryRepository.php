<?php

namespace App\Repository;

use App\Entity\PromoCodeHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PromoCodeHistory>
 *
 * @method PromoCodeHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method PromoCodeHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method PromoCodeHistory[]    findAll()
 * @method PromoCodeHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromoCodeHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PromoCodeHistory::class);
    }
}
