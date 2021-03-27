<?php

namespace DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use App\Entity\User;

class UserFixture extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";

        $user = new User();
        $email = "contact@simon-micheneau.fr";
        $password = substr(str_shuffle(str_repeat($alphabet, 12)), 0, 12);
        echo $email." : ".$password."\n\r";
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setAdmin(true);
        $manager->persist($user);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
