<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class UserRepository extends EntityRepository
{
    public function getCountUsers(int $active = null)
    {
        $qb = $this->createQueryBuilder("u")
            ->select("count(u)");
        if (!is_null($active)) {
            $qb->andWhere("u.active = :active")
                ->setParameter("active", $active);
        }
        return $qb->getQuery()->getResult();
    }

    public function getUserById(int $id)
    {
        $qb = $this->createQueryBuilder("u")
            ->select("u")
            ->andWhere("u.id = :id")
                ->setParameter("id", $id);
        return $qb->getQuery()->getResult();
    }

    public function getUserByEmail(string $email, bool $active = null)
    {
        $qb = $this->createQueryBuilder("u")
            ->select("u")
            ->andWhere("u.email = :email")
                ->setParameter("email", $email);
        if (!is_null($active)) {
            $qb->andWhere("u.active = :active")
                ->setParameter("active", $active);
        }
        return $qb->getQuery()->getResult();
    }

    public function getUserByResetToken(string $token, bool $active = null)
    {
        $qb = $this->createQueryBuilder("u")
            ->select("u")
            ->andWhere("u.resetToken = :token")
                ->setParameter("token", $token);
        if (!is_null($active)) {
            $qb->andWhere("u.active = :active")
                ->setParameter("active", $active);
        }
        return $qb->getQuery()->getResult();
    }

    public function getUsersListPage(int $start = 0, int $limit = 20, array $filters = [])
    {
        $qb = $this->createQueryBuilder("u");

        if (isset($filters["search"])) {
            $qb->andWhere("u.email LIKE :search")
                ->setParameter("search", "%".$filters["search"]."%");
        }

        if (isset($filters["etat"])) {
            $qb->andWhere("u.active = :etat")
                ->setParameter("etat", $filters["etat"]);
        }

        $qb->addOrderBy("u.email", "ASC");

        $qb->setFirstResult($start)
            ->setMaxResults($limit);

        return count(new Paginator($qb));
    }

    public function getUsersListWithPosition(int $start = 0, int $limit = 20, array $filters = [])
    {
        $qb = $this->createQueryBuilder("u");

        if (isset($filters["search"])) {
            $qb->andWhere("u.email LIKE :search")
                ->setParameter("search", "%".$filters["search"]."%");
        }

        if (isset($filters["etat"])) {
            $qb->andWhere("u.active = :etat")
                ->setParameter("etat", $filters["etat"]);
        }

        $qb->addOrderBy("u.email", "ASC");

        return $qb->setFirstResult($start)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
