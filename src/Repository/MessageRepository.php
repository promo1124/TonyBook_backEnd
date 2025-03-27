<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    //    /**
    //     * @return Message[] Returns an array of Message objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    /********RÉCUPÉRATION DES MESSAGES PAR RÉSERVATION********/
    /*********************************************************/

    /*public function findByReservation(Reservation $reservation): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.reservation = :reservation')
            ->setParameter('reservation', $reservation)
            ->orderBy('m.sendAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
        
    /*RÉCUPÉRATION DES MESSAGES RECUS PAR UN UTILISATEUR*/
    /****************************************************/
    /*public function findMessagesReceivedByUser(User $user): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.reception = :user')
            ->setParameter('user', $user)
            ->orderBy('m.sendAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }*/
   
    //    public function findOneBySomeField($value): ?Message
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /********RÉCUPÉRATION DES MESSAGES PAR RÉSERVATION********/
    /*********************************************************/

    /*public function findMessagesReceivedByUser(User $user): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.reception = :user')
            ->setParameter('user', $user)
            ->orderBy('m.sendAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }*/

}
