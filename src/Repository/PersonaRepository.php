<?php

namespace App\Repository;

use App\Entity\Persona;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Persona|null find($id, $lockMode = null, $lockVersion = null)
 * @method Persona|null findOneBy(array $criteria, array $orderBy = null)
 * @method Persona[]    findAll()
 * @method Persona[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Persona::class);
    }

    // /**
    //  * @return Persona[] Returns an array of Persona objects
    //  */
    
    public function findPersonaByAgeInterval($ageMin, $ageMax)
    {
        $qb = $this->createQueryBuilder('p');

        $this->addIntervaleAge($qb, $ageMin, $ageMax);
       
        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Persona[] Returns an array of Persona objects
    //  */
    
    public function statsPersonaByAgeInterval($ageMin, $ageMax)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('avg(p.age) as ageMedia, count(p.id) as numeroPersonas');

        $this->addIntervaleAge($qb, $ageMin, $ageMax);
           
        return $qb ->getQuery()->getScalarResult();
    }


    public function addIntervaleAge(QueryBuilder $qb, $ageMin, $ageMax){
        $qb->andWhere('p.age >= :ageMin and p.age <= :ageMax')    
        //->setParameter('ageMin', $ageMin)
        //->setParameter('ageMax', $ageMax) 
        ->setParameters(['ageMin' => $ageMin, 'ageMax' => $ageMax]);
    }

   

    /*
    public function findOneBySomeField($value): ?Persona
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
