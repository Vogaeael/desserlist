<?php

namespace App\Repository;

use App\Entity\Entry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class EntryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Entry::class);
    }

    public function findUserEntriesOrderedByDate($userId)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('e')
            ->where('e.user = :userId')
            ->join('e.workday', 'w', 'WITH')
            ->orderBy('w.date', 'ASC')
            ->setParameter('userId', $userId);
        return $qb->getQuery()->getResult();
    }
}
