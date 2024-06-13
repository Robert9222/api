<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }
    public function save(Item $item)
    {
        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();
    }
    public function update(Item $item)
    {
        $this->getEntityManager()->flush();
    }
    public function findById(int $id): ?Item
    {
        return $this->find($id);
    }
}