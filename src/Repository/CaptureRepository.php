<?php

namespace App\Repository;

use App\Entity\Capture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Capture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Capture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Capture[]    findAll()
 * @method Capture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CaptureRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Capture::class);
    }

//    /**
//     * @return Capture[] Returns an array of Capture objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Capture
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getPublishedCaptures()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.status = :status1')
            ->setParameter('status1', 'published')
            ->orWhere('c.status = :status2')
            ->setParameter('status2', 'validated')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getPublishedCapturesPerPage($elementsPerPage, $firstEntrance)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.status = :status1')
            ->setParameter('status1', 'published')
            ->orWhere('c.status = :status2')
            ->setParameter('status2', 'validated')
            ->getQuery()
            ->setMaxResults($elementsPerPage)
            ->setFirstResult($firstEntrance)
            ->getResult()
        ;
    }

    public function getLastPublishedCaptures($numberElements)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.status = :status1')
            ->setParameter('status1', 'published')
            ->orWhere('c.status = :status2')
            ->setParameter('status2', 'validated')
            ->orderBy('c.created_date', 'desc')
            ->getQuery()
            ->setMaxResults($numberElements)
            ->getResult()
        ;
    }

    public function getPublishedCapture($id)
    {
        return $this->createQueryBuilder('c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->andWhere('c.status != :status1')
            ->setParameter('status1', 'draft')
            ->andWhere('c.status != :status2')
            ->setParameter('status2', 'waiting for validation')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getCaptureByStatus($status)
    {
        return $this->createQueryBuilder('c')
            ->where('c.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getCapturesByStatusPerPage($status, $elementsPerPage, $firstEntrance)
    {
        return $this->createQueryBuilder('c')
            ->where('c.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->setMaxResults($elementsPerPage)
            ->setFirstResult($firstEntrance)
            ->getResult()
        ;
    }

    public function getBirdPublishedCaptures($bird)
    {
        return $this->createQueryBuilder('c')
            ->where('c.bird = :bird')
            ->setParameter('bird', $bird)
            ->andWhere('c.status != :status1')
            ->setParameter('status1', 'draft')
            ->andWhere('c.status != :status2')
            ->setParameter('status2', 'waiting for validation')
            ->getQuery()
            ->getResult()
        ;
    }

    public function countByStatus($status)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('count(t.id)');
        $qb->where('t.status = :status');
        $qb->setParameter('status', $status);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countPublishedCaptures()
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('count(t.id)');
        $qb->andWhere('t.status = :status1');
        $qb->setParameter('status1', 'published');
        $qb->where('t.status = :status1');
        $qb->setParameter('status1', 'published');
        $qb->orWhere('t.status = :status2');
        $qb->setParameter('status2', 'validated');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countByStatusAndAuthor($status, $author)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('count(t.id)');
        $qb->where('t.status = :status');
        $qb->setParameter('status', $status);
        $qb->andWhere('t.user = :user');
        $qb->setParameter('user', $author);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function searchCaptureByBirdAndRegion($bird, $region)
    {
        return $this->createQueryBuilder('c')
            ->where('c.bird.vernacularname = :bird')
            ->setParameter('bird', $bird)
            ->andWhere('c.region = :region')
            ->setParameter('region', $region)
            ->getQuery()
            ->getResult()
        ;
    }
}
