<?php

namespace App\Repository;

use App\Entity\File;
use App\Entity\JobApplication;
use App\Entity\Position;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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

    public function getList(bool $isRead, int $limit, string $sort, string $order, int $page = 1, ?int $position = null): array
    {
        $queryBuilder = $this->createQueryBuilder('ja');
        $query = $queryBuilder
            ->select("ja.id, ja.firstName, ja.lastName, ja.email, ja.level, ja.expectedSalary, 
                  p.id as positionId, p.name as positionName, ja.createdAt, f.name as cv")
            ->leftJoin(Position::class, 'p', Join::WITH, 'ja.position = p.id')
            ->leftJoin(File::class, 'f', Join::WITH, 'ja.id = f.jobApplication')

            ->where('ja.isRead = :isRead');

        if ($position) {
            $query = $query->andWhere('p.id = :position')
                ->setParameter('position', $position);
        }

        $query = $query
            ->setParameter('isRead', $isRead)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->orderBy('ja.' . $sort, $order)
            ->getQuery();

        $result = $query->getResult();

        return $this->transformResult($result);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getTotal(bool $isRead, ?int $position = null): int
    {
        $queryBuilder = $this->createQueryBuilder('ja');
        $query = $queryBuilder
            ->select('COUNT(ja.id)')
            ->leftJoin(Position::class, 'p', Join::WITH, 'ja.position = p.id')
            ->where('ja.isRead = :isRead');

        if ($position) {
            $query = $query->andWhere('p.id = :position')
                ->setParameter('position', $position);
        }

        $query = $query
            ->setParameter('isRead', $isRead)
            ->getQuery();

        return $query->getSingleScalarResult();
    }

    private function transformResult(array $result): array
    {
        return array_map(function ($item) {
            $item['position'] = [
                'id' => $item['positionId'],
                'name' => $item['positionName']
            ];
            unset($item['positionId'], $item['positionName']);
            return $item;
        }, $result);
    }
}
