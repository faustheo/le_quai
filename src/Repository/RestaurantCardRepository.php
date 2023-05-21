<?php

namespace App\Repository;

use App\Entity\RestaurantCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RestaurantCard>
 *
 * @method RestaurantCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantCard[]    findAll()
 * @method RestaurantCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantCardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantCard::class);
    }

    public function save(RestaurantCard $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RestaurantCard $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllRestaurantCards(): array
    {
        return $this->findAll();
    }
}
