<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Profile;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Event;
use App\Models\Connection;
use App\Models\Message;
use App\Models\Notification;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $pw = 'password';
        $meta = fn() => [
            'email_verified_at' => now(),
            'is_active'         => true,
            'remember_token'    => Str::random(60),
        ];

        // ─── ADMIN ───────────────────────────────────────────────────────────
        $admin = User::forceCreate(array_merge([
            'name'       => 'UPF University',
            'email'      => 'admin@upf.pf',
            'password'   => $pw,
            'role'       => 'admin',
            'bio'        => 'Compte officiel de l\'Université de la Polynésie Française. Bienvenue sur UPFConnect !',
            'department' => 'Direction',
        ], $meta()));
        Profile::create(['user_id' => $admin->id, 'skills' => ['Administration', 'Communication'], 'interests' => ['Innovation', 'Éducation']]);

        // ─── TEACHERS ────────────────────────────────────────────────────────
        $teacherData = [
            ['Dr. Moana Tefaafana',  'moana.tefaafana@upf.pf',  'Informatique',   'Maître de conférences en IA et systèmes distribués. Passionné par la recherche appliquée.'],
            ['Prof. Raiarii Hutihuti','raiarii.hutihuti@upf.pf', 'Mathématiques',  'Professeur agrégé en mathématiques. Spécialiste en algèbre et cryptographie.'],
            ['Dr. Teiva Maraetaata', 'teiva.maraetaata@upf.pf',  'Physique',       'Docteur en physique quantique. Chercheur associé au CNRS.'],
            ['Prof. Heimana Pihaatae','heimana.pihaatae@upf.pf', 'Droit',          'Professeur en droit international. Consultant juridique pour les institutions du Pacifique.'],
            ['Dr. Vaite Narii',      'vaite.narii@upf.pf',       'Biologie',       'Biologiste marine, spécialiste des écosystèmes coralliens de Polynésie.'],
        ];

        $teachers = [];
        foreach ($teacherData as [$name, $email, $dept, $bio]) {
            $t = User::forceCreate(array_merge([
                'name' => $name, 'email' => $email, 'password' => $pw,
                'role' => 'teacher', 'bio' => $bio, 'department' => $dept,
            ], $meta()));
            Profile::create([
                'user_id'      => $t->id,
                'linkedin_url' => 'https://linkedin.com/in/' . Str::slug($name),
                'skills'       => ['Recherche', 'Enseignement', 'Publication'],
                'interests'    => ['Science', 'Innovation'],
            ]);
            $teachers[] = $t;
        }

        // ─── STUDENTS ────────────────────────────────────────────────────────
        $studentData = [
            ['Teariki Manutahi',   'teariki.manutahi@etud.upf.pf',   'Informatique',  3, 'Passionné de développement web et d\'intelligence artificielle.'],
            ['Heiura Tamatoa',     'heiura.tamatoa@etud.upf.pf',     'Informatique',  2, 'Étudiante en L2 info, amoureuse du open-source et du design.'],
            ['Manea Teriitehau',   'manea.teriitehau@etud.upf.pf',   'Mathématiques', 4, 'En master de maths appliquées, avec une spécialité en data science.'],
            ['Poerava Tefaafana',  'poerava.tefaafana@etud.upf.pf',  'Biologie',      1, 'Première année en bio marine. Plongée et sciences, ma vie.'],
            ['Teva Mairau',        'teva.mairau@etud.upf.pf',        'Droit',         3, 'Futur avocat en droit de l\'environnement polynésien.'],
            ['Roimata Vahine',     'roimata.vahine@etud.upf.pf',     'Informatique',  4, 'Développeuse full-stack, finaliste du hackathon Pacifique 2025.'],
            ['Natea Bourgeois',    'natea.bourgeois@etud.upf.pf',    'Physique',      2, 'Curieux de tout ce qui touche à l\'astrophysique et à la cosmologie.'],
            ['Hinanui Salmon',     'hinanui.salmon@etud.upf.pf',     'Informatique',  5, 'En master 2, je prépare ma thèse sur les réseaux de neurones.'],
            ['Tuarii Paeore',      'tuarii.paeore@etud.upf.pf',      'Lettres',       2, 'Passionné de littérature francophone et de traduction.'],
            ['Maeva Tehei',        'maeva.tehei@etud.upf.pf',        'Mathématiques', 1, 'L1 maths. J\'aime les problèmes complexes et les jeux logiques.'],
        ];

        $students = [];
        foreach ($studentData as [$name, $email, $dept, $year, $bio]) {
            $s = User::forceCreate(array_merge([
                'name' => $name, 'email' => $email, 'password' => $pw,
                'role' => 'student', 'bio' => $bio, 'department' => $dept,
            ], $meta()));
            Profile::create([
                'user_id'      => $s->id,
                'github_url'   => 'https://github.com/' . Str::slug($name),
                'skills'       => ['Travail en équipe', 'Recherche', $dept],
                'interests'    => ['Tech', 'Culture polynésienne'],
                'year_of_study'=> $year,
            ]);
            $students[] = $s;
        }

        $allUsers = array_merge([$admin], $teachers, $students);

        // ─── POSTS ───────────────────────────────────────────────────────────
        $this->call(PostSeeder::class, false, [
            'admin'    => $admin,
            'teachers' => $teachers,
            'students' => $students,
            'allUsers' => $allUsers,
        ]);

        // ─── EVENTS ──────────────────────────────────────────────────────────
        $events = [
            [$teachers[0], 'Conférence IA & Pacifique 2026', 'Une journée dédiée aux avancées de l\'intelligence artificielle dans le Pacifique. Intervenants locaux et internationaux.', 'Amphithéâtre A', '+15 days'],
            [$teachers[1], 'Séminaire Cryptographie Appliquée', 'Introduction à la cryptographie post-quantique et ses applications dans la sécurité des données.', 'Salle 201', '+20 days'],
            [$teachers[2], 'Nuit des étoiles — Observation au télescope', 'Soirée observation astronomique organisée par le département de physique. Ouverte à tous.', 'Toit du bâtiment B', '+7 days'],
            [$teachers[3], 'Forum Droit & Environnement', 'Table ronde sur la protection juridique des lagons polynésiens. Avec des experts du PNUE.', 'Amphithéâtre B', '+30 days'],
            [$teachers[4], 'Atelier Récifs Coralliens', 'Sortie terrain sur le lagon de Tahiti : observation, collecte de données et analyse en groupe.', 'Lagon de Papeete', '+10 days'],
            [$admin,        'Journée Portes Ouvertes UPF 2026', 'Découvrez tous les cursus, rencontrez les enseignants et visitez le campus. Inscription gratuite.', 'Campus de Outumaoro', '+45 days'],
        ];

        foreach ($events as [$user, $title, $desc, $loc, $offset]) {
            Event::create([
                'user_id'     => $user->id,
                'title'       => $title,
                'description' => $desc,
                'location'    => $loc,
                'event_date'  => now()->modify($offset),
                'image'       => 'https://picsum.photos/800/400?random=' . rand(100, 999),
            ]);
        }

        // ─── CONNECTIONS ─────────────────────────────────────────────────────
        $pairs = [];
        // Teacher-Student connections
        foreach ($teachers as $t) {
            foreach (array_slice($students, 0, 6) as $s) {
                $key = min($t->id, $s->id) . '-' . max($t->id, $s->id);
                if (!isset($pairs[$key])) {
                    Connection::create(['sender_id' => $t->id, 'receiver_id' => $s->id, 'status' => 'accepted']);
                    $pairs[$key] = true;
                }
            }
        }
        // Student-Student connections
        foreach ($students as $i => $s1) {
            foreach ($students as $j => $s2) {
                if ($i >= $j) continue;
                $key = min($s1->id, $s2->id) . '-' . max($s1->id, $s2->id);
                if (!isset($pairs[$key]) && rand(0, 1)) {
                    Connection::create(['sender_id' => $s1->id, 'receiver_id' => $s2->id, 'status' => rand(0,3) ? 'accepted' : 'pending']);
                    $pairs[$key] = true;
                }
            }
        }

        // ─── MESSAGES ────────────────────────────────────────────────────────
        $msgData = [
            [$students[0], $teachers[0], 'Bonjour Dr. Tefaafana, est-ce que votre cours de demain est maintenu ?'],
            [$teachers[0], $students[0], 'Oui bien sûr, rendez-vous en salle 104 à 8h30.'],
            [$students[1], $students[5], 'Tu as fini le projet de base de données ?'],
            [$students[5], $students[1], 'Presque ! Je bute sur la partie requêtes imbriquées. On se voit demain ?'],
            [$students[2], $teachers[1], 'Professeur, pouvez-vous relire mon chapitre 3 de mémoire ?'],
            [$teachers[1], $students[2], 'Envoyez-le moi par mail, je vous retourne mes remarques sous 48h.'],
        ];
        foreach ($msgData as [$sender, $receiver, $body]) {
            Message::create(['sender_id' => $sender->id, 'receiver_id' => $receiver->id, 'body' => $body, 'read_at' => now()]);
        }

        // ─── GROUPS ──────────────────────────────────────────────────────────
        $this->call(GroupSeeder::class);
    }
}
