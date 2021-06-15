<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }
    public function findAllByCategorie( $categorie )
    { 
        
        if($categorie){
            $entityManager = $this->getEntityManager();
           $query= $entityManager->createQuery(
                'SELECT l, c
                FROM App\Entity\Product l
                INNER JOIN l.category c
                WHERE c.nom = :nom'
            )->setParameter('nom', $categorie);
        //     return $this->createQueryBuilder('a')
        //     ->innerJoin(Logger::class, 'l')
        // ->andWhere('l.nom = :val')
        // ->setParameter('val', $value)
        // ->orderBy('a.id', 'DESC')
        // ->setMaxResults(50)
        return $query->getResult();
    ;}else{return $this->findAll();}
        
    }
    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
