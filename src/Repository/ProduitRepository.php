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
        $query = $this->createQueryBuilder('p');

        foreach ($criteria as $field => $value) {
            {
                $query->andWhere("p.$field = :$field")
                    ->setParameter($field, $value);
            }
        }

        return $query->getQuery()->getResult();
    }
}
