<?php

namespace App\Repository;

use App\Entity\MaxGuests;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MaxGuests>
 *
 * @method MaxGuests|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaxGuests|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaxGuests[]    findAll()
 * @method MaxGuests[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaxGuestsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaxGuests::class);
    }

    public function save(MaxGuests $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MaxGuests $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findOneByAvailableSeats(): ?MaxGuests
    {
        return $this->createQueryBuilder('m')
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function getAvailableSeats(): int
    {
        $maxGuests = $this->findOneByAvailableSeats();

        if ($maxGuests === null) {
            return 0;
        }

        return $maxGuests->getAvailableSeats();
    }
}
