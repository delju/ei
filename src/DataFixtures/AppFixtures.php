<?php

namespace App\DataFixtures;

use App\Entity\ArrivalReason;
use App\Entity\GetOn;
use App\Entity\Races;
use App\Entity\Species;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setPassword($this->encoder->encodePassword($admin, "azerty"));
        $admin->setEmail("admin@fixture");
        $admin->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);

        $species1 = new Species();
        $species1->setName("Chien");
        $manager->persist($species1);

        $species2 = new Species();
        $species2->setName("Chat");
        $manager->persist($species2);

        $species3 = new Species();
        $species3->setName("NAC");
        $manager->persist($species3);

        $species4 = new Species();
        $species4->setName("Autres");
        $manager->persist($species4);

        $arrivalReason1 = new ArrivalReason();
        $arrivalReason1->setName("Abandon");
        $manager->persist($arrivalReason1);

        $arrivalReason2 = new ArrivalReason();
        $arrivalReason2->setName("Perdu");
        $manager->persist($arrivalReason2);

        $arrivalReason3 = new ArrivalReason();
        $arrivalReason3->setName("Saisie");
        $manager->persist($arrivalReason3);

        $arrivalReason4 = new ArrivalReason();
        $arrivalReason4->setName("Né au centre");
        $manager->persist($arrivalReason4);

        $getOn1 = new GetOn();
        $getOn1->setName("Chien");
        $manager->persist($getOn1);

        $getOn2 = new GetOn();
        $getOn2->setName("Chat");
        $manager->persist($getOn2);

        $getOn3 = new GetOn();
        $getOn3->setName("Enfants");
        $manager->persist($getOn3);

        $getOn4 = new GetOn();
        $getOn4->setName("Aucun");
        $manager->persist($getOn4);


        $manager->flush();
    }
}
