<?php

namespace App\Repository;

use App\Entity\JobApplication;
use App\Entity\Position;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

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
    public function __construct(ManagerRegistry $registry, private readonly ParameterBagInterface $parameterBag)
    {
        parent::__construct($registry, JobApplication::class);
    }

    public function getList(bool $isRead, int $page = 1): array
    {
        $limit = $this->parameterBag->get('records_per_page');
        $queryBuilder = $this->createQueryBuilder('ja');
        $query = $queryBuilder
            ->select("CONCAT(ja.firstName, ' ', ja.lastName) AS fullName, ja.email, ja.level, ja.expectedSalary, p.name, ja.createdAt")
            ->leftJoin(Position::class, 'p', Join::WITH, 'ja.position = p.id')
            ->where('ja.isRead = :isRead')
            ->setParameter('isRead', $isRead)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();
        $total = $this->getTotalCountByIsRead($isRead);
        $pages = ceil($total / $limit);

        return [
            'pages' => (int)$pages,
            'totalRecords' => $total,
            'pageRecords' => $query->getResult(),
        ];
    }

    private function getTotalCountByIsRead(bool $isRead): int
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
