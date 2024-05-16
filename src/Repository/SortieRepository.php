<?php

namespace App\Repository;

use App\Entity\Etat;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\Clock\now;

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

    public function findByFilters(?\DateTimeInterface $dateDebut, ?\DateTimeInterface $dateFin, ?bool $isEtatPassee, ?bool $isOrganisateur, ?int $organisateurId,?bool $isInscrit  ): array
    {
        $qb = $this->createQueryBuilder('s');

        if ($dateDebut && $dateFin) {
            $qb->andWhere('s.dateHeureDebut BETWEEN :dateDebut AND :dateFin')
                ->setParameter('dateDebut', $dateDebut)
                ->setParameter('dateFin', $dateFin);
        }

        if ($isEtatPassee) {
            $qb->join('s.etat', 'e')
                ->andWhere('e.libelle = :libelle')
                ->setParameter('libelle', 'Passée'); // Utilisez la valeur de chaîne 'Passée'
        }

        if ($isOrganisateur) {

            $qb->andWhere('s.organisateur = :organisateurId')
                ->setParameter('organisateurId', $organisateurId);
        }

        if ($isInscrit) {
            $qb->join('s.inscriptions', 'i')
                ->andWhere('i.idParticipant = :participantId')
                ->setParameter('participantId', $organisateurId);
        }



        return $qb->getQuery()->getResult();
    }

    public function findAllNonArchivee()
    {
        $entityManager = $this->getEntityManager();
        $etatArchive = $entityManager->getRepository(Etat::class)->find(7);

        $now = new \DateTime();
        $oneMonthAgo = clone $now;
        $oneMonthAgo->modify('-1 month');

        $sorties = $this->findAll();

        foreach ($sorties as $sortie) {
            if ($sortie->getDateHeureDebut() <= $oneMonthAgo) {
                $sortie->setEtat($etatArchive);
                $entityManager->persist($sortie);
            }
        }
        $entityManager->flush();
        return $sorties;
    }
}
