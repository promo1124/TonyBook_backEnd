<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function findByCriteria(array $criteria): array {
        $qb = $this->createQueryBuilder('p');

        foreach ($criteria as $field => $value) {
            $qb->andWhere("p.$field LIKE :$field")
                ->setParameter($field, "%$value%");
        }

        return $qb->getQuery()->getResult();
    }
}
