<?php

namespace App\DataFixtures;

use App\Entity\Equipment;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\Subject;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

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
            ['name' => 'Autre', 'abbreviation' => 'AUT'],
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
            [
                'name' => 'Communication, expression écrite et orale', 
                'PPN' => 'ECE', 
                'semester' => 1
            ],
            [
                'name' => 'Esthétique et expréssion artistique', 
                'PPN' => 'EEA', 
                'semester' => 1
            ],
            [
                'name' => 'Adaptation de parcours', 
                'PPN' => 'AP', 
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



        // Backup account
        $user = new User;
        $user->setFirstName('Compte');
        $user->setLastName('Backup');
        $user->setUsername('d132b33b0605cbd36120b8b3c523ff88');
        $user->setPassword('$argon2id$v=19$m=65536,t=4,p=1$OTdodGJhOVZWQ1gzSDVqMA$qR7aIO64e8oPqKxDvL/mQOSkBL3o4TAEVyjC56nR1TI');
        $user->setRoles(["ROLE_","ROLE_PRO","ROLE_DIR","ROLE_MAN"]);
        
        $om->persist($user);


        $equipments = [
            ['name' => 'Suite Adobe', 'category' => 'Logiciel'],
            ['name' => 'Blender', 'category' => 'Logiciel'],
            ['name' => 'Imprimante 3D', 'category' => 'Matériel physique'],
            ['name' => 'Linux - Ubuntu', 'category' => 'Système d\'exploitation'],
            ['name' => 'Caméra(s)', 'category' => 'Matériel physique'],
            ['name' => 'Stabilisateur(s)', 'category' => 'Matériel physique'],
            ['name' => 'Appareil(s) photo', 'category' => 'Matériel physique'],
            ['name' => 'Microphone(s)', 'category' => 'Matériel physique'],
            ['name' => 'Ordinateurs Mac - MacOS', 'category' => 'Système d\'exploitation'],
            // etc
        ];
        foreach ($equipments as $element) {
            $equipment = new Equipment;
            $equipment->setName($element['name']);
            $equipment->setCategory($element['category']);
            
            $om->persist($equipment);
        }




        $om->flush();
    }
}
