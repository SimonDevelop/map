<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

class MarkerRepository extends EntityRepository
{
    public function queryGetMarkers()
    {
        return $this->createQueryBuilder("m")
            ->getQuery()
            ->getResult();
    }

    public function getMarkersByUser($id)
    {
        return $this->createQueryBuilder("m")
            ->where('m.user = :user')
            ->setParameter("user", $id)
            ->getQuery()
            ->getResult();
    }
}
