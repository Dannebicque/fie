<?php

namespace App\Repository;

use App\Entity\Creneaux;
use App\Entity\Entreprise;
use App\Entity\Etudiant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Creneaux|null find($id, $lockMode = null, $lockVersion = null)
 * @method Creneaux|null findOneBy(array $criteria, array $orderBy = null)
 * @method Creneaux[]    findAll()
 * @method Creneaux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreneauxRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Creneaux::class);
    }

    /**
     * @param Entreprise $entreprise
     *
     * @return array
     */
    public function findByEntreprise(Entreprise $entreprise): array
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.entreprise = :entreprise')
            ->setParameter('entreprise', $entreprise->getId())
            ->orderBy('c.heure')
            ->getQuery()
            ->getResult();

        $t = array();
        /** @var Creneaux $item */
        foreach ($query as $item) {
            $t[$item->getHeure()] = $item;
        }

        return $t;
    }

    /**
     * @param Etudiant $etudiant
     *
     * @return array
     */
    public function findByEtudiant(Etudiant $etudiant): array
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.etudiant = :etudiant')
            ->setParameter('etudiant', $etudiant->getId())
            ->orderBy('c.heure')
            ->getQuery()
            ->getResult();

        $t = array();
        /** @var Creneaux $item */
        foreach ($query as $item) {
            $t[$item->getHeure()] = $item;
        }

        return $t;
    }

    // /**
    //  * @return Creneaux[] Returns an array of Creneaux objects
    //  */
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
    public function findOneBySomeField($value): ?Creneaux
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
