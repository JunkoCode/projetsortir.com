<?php

namespace App\Repository;

use App\data\FiltreData;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use dateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function add(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


    public function findByFiltre(int $idUser, FiltreData $data)

    {
        $query = $this ->createQueryBuilder('s')
            ->orderBy('s.dateHeureDebut', 'ASC')
            ->andWhere('s.dateHeureDebut > :archiveDate')
            ->setParameter('archiveDate', new dateTimeImmutable('-30 day'))
            ->distinct();

        if(!empty($data->filtreSortieMotCle)){
            $query
                ->andWhere('s.nom LIKE :filtreSortieMotCle')
                ->setParameter('filtreSortieMotCle', '%'.$data->filtreSortieMotCle.'%');
        }

        if (!empty($data->filtreSortieDateMin)){
            $query
                ->andWhere('s.dateHeureDebut >= :filtreSortieDateMin')
                ->setParameter('filtreSortieDateMin', $data->filtreSortieDateMin);
        }

        if (!empty($data->filtreSortieDateMax)){
            $query
                ->andWhere('s.dateHeureDebut <= :filtreSortieDateMax')
                ->setParameter('filtreSortieDateMax', $data->filtreSortieDateMax);
        }

        if (!empty($data->filtreSortieOrganisateur)){
            $query
                ->andWhere('s.organisateur = :idUser')
                ->setParameter('idUser', $idUser);
        }

        if (!empty($data->filtreSortieInscrit)){
            $query
                ->innerJoin('s.participants', 'pa')
                ->andWhere('pa.id = :id')
                ->setParameter('id', $idUser);
        }

        if (!empty($data->filtreSortiePasInscrit)){
            $query
                ->leftJoin('s.participants', 'p')
                ->andWhere($query->expr()->neq('p.id',$idUser))
                ->orWhere($query->expr()->isNull('p.id'));
        }

        if (!empty($data->filtreSortiePassees)){
            $query
                ->andWhere('s.dateHeureDebut < :dateNow')
                ->setParameter('dateNow', new \dateTimeImmutable);
        }

        if (!empty($data->filtreSortieCampus)){
            $query
                ->leftJoin('s.participants', 'par')
                ->andWhere('par.campus IN (:campus)')
                ->setParameter('campus', $data->filtreSortieCampus);
        }

        return $query->getQuery()->execute();
    }

}
