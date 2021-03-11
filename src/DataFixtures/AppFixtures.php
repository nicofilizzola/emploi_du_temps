<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Event;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $om)
    {
        $events = [
            ['name' => 'Cours', 'abbreviation' => 'COU'],
            ['name' => 'Vacances', 'abbreviation' => 'VAC'],
            ['name' => 'Vacances Zone C', 'abbreviation' => 'VZC'],
            ['name' => 'Projet tuteuré', 'abbreviation' => 'PTUT'],
            ['name' => 'Stage 1A', 'abbreviation' => 'S1A'],
            ['name' => 'Stage 2A', 'abbreviation' => 'S2A'],
            ['name' => 'Stage 3A', 'abbreviation' => 'S3A'],
            ['name' => 'Conseil de l\'IUT', 'abbreviation' => 'CIUT'],
            ['name' => 'Conseil de direction de l\'IUT', 'abbreviation' => 'CDI'],
            ['name' => 'Conseil de département', 'abbreviation' => 'DEP'],
            ['name' => 'Jurys semestre', 'abbreviation' => 'JUR'],
            //['name' => 'Semaines intersemestre', 'abbreviation' => 'SIS'],
            //['name' => 'Immersions Digitales', 'abbreviation' => 'JPO'],
        ];
        
        foreach ($events as $element) {
            $event = new Event;
            $event->setName($element['name']);
            $event->setAbbreviation($element['abbreviation']);
            $om->persist($event);
        }

        $om->flush();
    }
}
