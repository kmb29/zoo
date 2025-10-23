<?php
namespace App\Repository;

use App\Entity\Cell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cell>
 */
class CellRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cell::class);
    }

    public function findAllWithAnimals(): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.animals', 'a')
            ->addSelect('a')
            ->getQuery()
            ->getResult();
    }
}

