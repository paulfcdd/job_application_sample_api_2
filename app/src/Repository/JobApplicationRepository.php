<?php

namespace App\Repository;

use App\Entity\JobApplication;
use App\Entity\Position;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobApplication>
 *
 * @method JobApplication|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobApplication|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobApplication[]    findAll()
 * @method JobApplication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobApplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobApplication::class);
    }

    public function getList(bool $isRead, int $limit, string $sort, string $order, int $page = 1): array
    {
        $queryBuilder = $this->createQueryBuilder('ja');
        $query = $queryBuilder
            ->select("ja.id, ja.firstName, ja.lastName, ja.email, ja.level, ja.expectedSalary, p.name, ja.createdAt")
            ->leftJoin(Position::class, 'p', Join::WITH, 'ja.position = p.id')
            ->where('ja.isRead = :isRead')
            ->setParameter('isRead', $isRead)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->orderBy('ja.' . $sort, $order)
            ->getQuery();

        return $query->getResult();
    }

    public function getTotalByStatus(bool $isRead): int
    {
        $queryBuilder = $this->createQueryBuilder('ja');
        $query = $queryBuilder
            ->select('COUNT(ja.id)')
            ->where('ja.isRead = :isRead')
            ->setParameter('isRead', $isRead)
            ->getQuery();

        return $query->getSingleScalarResult();
    }
}
