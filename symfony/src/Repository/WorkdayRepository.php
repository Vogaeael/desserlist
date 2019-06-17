<?php

namespace App\Repository;

use App\Entity\Entry;
use App\Entity\Workday;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class WorkdayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workday::class);
    }

    public function findUnregisteredWorkdays($userId)
    {
        $registeredWorkdayIds = $this
            ->findRegisteredWorkdayIds($userId);

        $qb = $this->createQueryBuilder('w');

        $notInExpression = $qb->expr()
            ->notIn('w.id', $registeredWorkdayIds);

        return $qb->select('w')
            ->where($notInExpression)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find the Workdays on which the user is registered
     *
     * @param $userId
     *
     * @return array
     */
    private function findRegisteredWorkdayIds($userId)
    {
        $workdayIds = [];
        $workdayIdsArray = $this->getEntityManager()
            ->getRepository(Entry::class)
            ->createQueryBuilder('e')
            ->select('IDENTITY(e.workday)')
            ->where('e.user = :user')
            ->setParameter('user', $userId)
            ->getQuery()
            ->getResult();
        foreach ($workdayIdsArray as $workdayId) {
            $workdayIds =
                array_merge(
                    $workdayIds,
                    array_values($workdayId)
                );
        }

        return $workdayIds;
    }
}
