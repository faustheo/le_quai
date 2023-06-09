<?php

namespace App\Repository;

use App\Entity\Hours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

/**
 * @extends ServiceEntityRepository<Hours>
 *
 * @method Hours|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hours|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hours[]    findAll()
 * @method Hours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hours::class);
    }

    public function save(Hours $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Hours $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Récupère toutes les dates disponibles
     */
    public function findAllDatesAndHoursAvailable(): array
    {
        $qb = $this->createQueryBuilder('h');
        $qb->select('DISTINCT h.date, CONCAT(h.LunchOpening, \' - \', h.LunchClosing) AS lunch_hours, CONCAT(h.DinnerOpening, \' - \', h.DinnerClosing) AS dinner_hours')
            ->andWhere('h.LunchOpening IS NOT NULL')
            ->andWhere('h.LunchClosing IS NOT NULL')
            ->andWhere('h.DinnerOpening IS NOT NULL')
            ->andWhere('h.DinnerClosing IS NOT NULL')
            ->orderBy('h.date', 'ASC');

        $results = $qb->getQuery()->getResult();

        $datesAndHoursAvailable = [];
        foreach ($results as $result) {
            $date = $result['date']->format('Y-m-d');
            $hours = [$result['lunch_hours'], $result['dinner_hours']];
            if (!array_key_exists($date, $datesAndHoursAvailable)) {
                $datesAndHoursAvailable[$date] = ['hours' => $hours];
            } else {
                $datesAndHoursAvailable[$date]['hours'] = array_merge($datesAndHoursAvailable[$date]['hours'], $hours);
            }
        }

        return $datesAndHoursAvailable;
    }

    /**
     * Récupère les heures d'ouverture et de fermeture pour une date spécifique
     */
    public function findOpeningAndClosingHoursForDate(\DateTimeInterface $date): ?array
    {
        $qb = $this->createQueryBuilder('h');
        $qb->select('h.LunchOpening', 'h.LunchClosing', 'h.DinnerOpening', 'h.DinnerClosing')
            ->andWhere('h.date = :date')
            ->setParameter('date', $date, \Doctrine\DBAL\Types\Types::DATE_IMMUTABLE)
            ->setMaxResults(1);

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result;
    }
    public function findHoursForDate(\DateTimeInterface $date): array
    {
        $hours = [];

        return $hours;
    }

    public function findLastDatesAndHours(int $nbDays): array
    {
        $startDate = new \DateTimeImmutable();
        $endDate = $startDate->modify(sprintf('+%d days', $nbDays));

        $qb = $this->createQueryBuilder('h');
        $qb->select('h.date', 'h.LunchOpening', 'h.LunchClosing', 'h.DinnerOpening', 'h.DinnerClosing')
            ->andWhere('h.date BETWEEN :start_date AND :end_date')
            ->andWhere('h.LunchOpening IS NOT NULL')
            ->andWhere('h.LunchClosing IS NOT NULL')
            ->andWhere('h.DinnerOpening IS NOT NULL')
            ->andWhere('h.DinnerClosing IS NOT NULL')
            ->setParameter('start_date', $startDate)
            ->setParameter('end_date', $endDate)
            ->orderBy('h.date', 'ASC');

        $results = $qb->getQuery()->getResult();

        return $results;
    }
}
