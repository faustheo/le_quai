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
        // Récupérez l'entité MaxGuests avec le nombre de sièges disponibles
        $maxGuests = $this->findOneByAvailableSeats();

        // Si l'entité MaxGuests n'existe pas, retournez 0
        if ($maxGuests === null) {
            return 0;
        }

        // Sinon, retournez le nombre de sièges disponibles
        return $maxGuests->getAvailableSeats();
    }





    //    /**
    //     * @return MaxGuests[] Returns an array of MaxGuests objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MaxGuests
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
