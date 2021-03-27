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
        $secret = "11kA7J7tuF9J0mQPsEfFg4Zy9fvuu79WAOhTvVtclVNUkyqpTjgwFcQ8AhiSa4w2ru5fw52i"
        ."YgzVOlo80lYXpK85TdNA1U7czcF59HOYL8qBivMlcFTyuuDgpdPxOQcHs2qzTvdCaNtUGLUt5xMI7UWQ3npCgwMkgQ"
        ."s31juyDoHAJWlIAIIsDXACSLxWCPh0CquxvHWRol8vtBsJUycW6ZAsJu4xIW88ZjShSW7ab0JOrl2oQ6JgbmXitC";

        $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";

        $user = new User();
        $email = "contact@simon-micheneau.fr";
        $password = substr(str_shuffle(str_repeat($alphabet, 12)), 0, 12);
        echo $email." : ".$password."\n\r";
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setSecret($secret);
        $user->setAdmin(true);
        $manager->persist($user);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
