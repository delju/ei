<?php

namespace App\Repository;

use App\Entity\Animals;
use App\Search\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Animals>
 *
 * @method Animals|null find($id, $lockMode = null, $lockVersion = null)
 * @method Animals|null findOneBy(array $criteria, array $orderBy = null)
 * @method Animals[]    findAll()
 * @method Animals[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animals::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Animals $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Animals $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findWithAll($slug): Animals
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a')
            ->leftJoin('a.gallery', 'g')
            ->addSelect('g')
            ->leftJoin('a.getOn', 'go')
            ->addSelect('go')
            ->leftJoin('a.arrivalReason', 'ar')
            ->addSelect('ar')
            ->leftJoin('a.treatments', 't', 'WITH', 't.animals = a.id ')
            ->where('a.slug = :slug')
            ->setParameter('slug', $slug);

        return $qb->getQuery()->getOneorNullResult();
    }

    public function findLastChance($limit = null)
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.lastChance = true')
            ->orderBy('a.dateArrival', 'desc');

        if (false === is_null($limit))
            $qb->setMaxResults($limit);

        return $qb->getQuery()->getResult();

    }

    public function findDeceased()
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.death IS NOT NULL')
            ->leftJoin('a.death', 'd')
            ->orderBy('d.date', 'desc');

        return $qb->getQuery()->getResult();
    }

    public function findRecovered()
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.comeBack IS NOT NULL')
            ->leftJoin('a.comeBack', 'c')
            ->orderBy('c.date', 'desc');

        return $qb->getQuery()->getResult();
    }

    public function findAdopted()
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.Adoption IS NOT NULL')
            ->leftJoin('a.Adoption', 'ad')
            ->orderBy('ad.date', 'desc');

        return $qb->getQuery()->getResult();
    }

    public function findBySearch(Search $search)
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.name LIKE :keyword')
            ->setParameter('keyword', '%' . $search->getKeyword() . '%');


        if (count($search->getSexe())){
        $qb->andWhere('a.Sexe in (:sexe)')
            ->setParameter('sexe', $search->getSexe());
    }
        if ($search->getSpecies()) {
            $qb->andWhere('a.species in (:species)')
                ->setParameter('species', $search->getSpecies());
        }

        if (count($search->getGetOns())) {
            $qb->andWhere('a.getOn in (:geton)')
                ->setParameter('geton', $search->getGetOns());
       }

        if ($search->getLastChance() == 1) {
            $qb->andWhere('a.lastChance in (:lastchances)')
                ->setParameter('lastchances', $search->getLastChance());
        }

        return $qb->getQuery()->getResult();
    }
//    /**
//     * @return Animals[] Returns an array of Animals objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Animals
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
