<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTime;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $microPost = (new MicroPost())
                ->setText('Some random text ' . rand(1, 500))
                ->setTime(DateTime::createFromFormat("d-m-Y", '01-01-2018'))
            ;
            $manager->persist($microPost);
        }

        $manager->flush();
    }
}
