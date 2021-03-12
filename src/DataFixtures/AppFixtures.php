<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Event;
use App\Entity\Subject;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $om)
    {
        /*
        EVENTS
        */
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


        /*
        SUBJECTS
        */
        $subjects = [
            [
                'name' => 'Intégration Web',
                'PPN' => 'ITW', 
                'semester' => 1
            ],
            [
                'name' => 'Algorithmique et programmation',
                'PPN' => 'DEV', 
                'semester' => 1
            ],
            [
                'name' => 'Programmation Orientée Objet',
                'PPN' => 'PRG', 
                'semester' => 1
            ],
            [
                'name' => 'Anglais',
                'PPN' => 'ANG', 
                'semester' => 1
            ],
            [
                'name' => 'Environnement Juridique, Économique et Mercatique',
                'PPN' => 'JEM', 
                'semester' => 1
            ],
            [
                'name' => 'Projet Personnel Professionnel',
                'PPN' => 'PPP', 
                'semester' => 1
            ],
            [
                'name' => 'Infographie',
                'PPN' => 'IFG', 
                'semester' => 1
            ],
            [
                'name' => 'Production Audiovisuelle',
                'PPN' => 'PAV', 
                'semester' => 1
            ],
            [
                'name' => 'Gestion de projet',
                'PPN' => 'GPR', 
                'semester' => 1
            ],
            [
                'name' => 'Théories de l\'information et de la communication',
                'PPN' => 'TIC', 
                'semester' => 1
            ],
            [
                'name' => 'Écriture pour les médias numériques', 
                'PPN' => 'EMN', 
                'semester' => 1
            ],
        ];
        foreach ($subjects as $element) {
            $subject = new Subject;
            $subject->setName($element['name']);
            $subject->setPPN($element['PPN']);
            $subject->setSemester($element['semester']);
            if (isset($element['CA'])){
                $subject->setCA($element['CA']);
            }
            
            $om->persist($subject);
        }



        $om->flush();
    }
}
