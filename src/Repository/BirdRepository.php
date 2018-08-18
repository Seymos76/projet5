<?php

namespace App\Repository;

use App\Entity\Bird;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Bird|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bird|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bird[]    findAll()
 * @method Bird[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BirdRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Bird::class);
    }

//    /**
//     * @return Bird[] Returns an array of Bird objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bird
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getBirdsByFirstLetter($letter, $elementsPerPage, $firstEntrance)
    {
        return $this->createQueryBuilder('b')
            ->where('b.vernacularname LIKE \''.$letter.'%\'')
            ->orderBy('b.vernacularname', 'ASC')
            ->getQuery()
            ->setMaxResults($elementsPerPage)
            ->setFirstResult($firstEntrance)
            ->getResult()
        ;
    }

    public function getBirdsPerPage($elementsPerPage, $firstEntrance)
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.vernacularname', 'ASC')
            ->addOrderBy('b.validname', 'ASC')
            ->getQuery()
            ->setMaxResults($elementsPerPage)
            ->setFirstResult($firstEntrance)
            ->getResult()
        ;
    }

    public function getBirdsByOrderAsc()
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.vernacularname', 'ASC')
            ->addOrderBy('b.validname', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function countBirds()
    {
        $qb = $this->createQueryBuilder('b');
        $qb->select('count(b.id)');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countBirdsByLetter($letter)
    {
        $qb = $this->createQueryBuilder('b');
        $qb->select('count(b.id)');
        $qb->where('b.vernacularname LIKE \''.$letter.'%\'');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countSearchBirdsByRegion($region)
    {
        $qb = $this->createQueryBuilder('b');
        $qb->select('count(b.id)');
        $qb->join('b.captures', 'c');
        $qb->andWhere('c.region = :region');
        $qb->setParameter('region', $region);
        $qb->andWhere('c.status != :status1');
        $qb->setParameter('status1', 'draft');
        $qb->andWhere('c.status != :status2');
        $qb->setParameter('status2', 'waiting_for_validation');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function searchBirdsByRegionPerPage($region, $numberOfElementsPerPage, $firstEntrance)
    {
        return $this->createQueryBuilder('b')
            ->join('b.captures', 'c')
            ->where('c.region = :region')
            ->setParameter('region', $region)
            ->andWhere('c.status != :status1')
            ->setParameter('status1', 'draft')
            ->andWhere('c.status != :status2')
            ->setParameter('status2', 'waiting_for_validation')
            ->getQuery()
            ->setMaxResults($numberOfElementsPerPage)
            ->setFirstResult($firstEntrance)
            ->getResult()
        ;
    }
}
