<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    // /**
    //  * @return Film[] Returns an array of Film objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Film
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    // Recupere tout les films et le ordonne par date de sortie en décroissant (du plus récent au plus vieux qui est la date du jour)
    public function getFilmsOrdreDate()
    {
        $dateJour = date('Y-m-d');
        $dateAnnee = date('2020-01-01');
        return $this->createQueryBuilder('f')
            ->where('f.dateSortie < :val')
            ->andWhere('f.dateSortie > :val2')
            ->orderBy('f.dateSortie', 'DESC')
            ->setParameter('val', $dateJour)
            ->setParameter('val2', $dateAnnee)
            ->getQuery()
            ->getResult()
        ;
    }

    // Recupere tout les films et le ordonne par date de sortie en croissant (du plus proche au plus éloigner)
    public function getFilmsProchainDate()
    {
        $dateJour = date('Y-m-d');
        return $this->createQueryBuilder('f')
            ->where('f.dateSortie > :val')
            ->orderBy('f.dateSortie', 'ASC')
            ->setParameter('val', $dateJour)
            ->getQuery()
            ->getResult()
        ;
    }

    // Compte tout les films ayant une catégory donnée
    /* public function getTotalFilmParCategory($idCategory)
    {
        return $this->createQueryBuilder('f')
            ->select('count(f.id)')
            ->where('f.category = :val')
            ->setParameter('val', $idCategory)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    } */

    // Recupere tout les films ayant une categorie donnée
     public function getFilmParCategory($idCategory)
    {
        return $this->createQueryBuilder('f')
            ->where('f.category = :val')
            ->setParameter('val', $idCategory)
            ->getQuery()
            ->getResult()
        ;
    } 
    
}
