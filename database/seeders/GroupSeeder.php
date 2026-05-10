<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\GroupMembership;
use App\Models\User;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::where('role', 'teacher')->first();
        $students = User::where('role', 'student')->take(5)->get();

        // 1. Club Informatique
        $infoClub = Group::create([
            'created_by' => $teacher->id,
            'name' => 'Club Informatique',
            'description' => 'Un espace pour les passionnés de programmation, développement web, mobile et IA. Partagez vos projets et apprenez ensemble !',
            'category' => 'club',
            'visibility' => 'public',
            'members_count' => 4,
        ]);

        // Admin (créateur)
        GroupMembership::create([
            'user_id' => $teacher->id,
            'group_id' => $infoClub->id,
            'role' => 'admin',
            'status' => 'approved',
            'joined_at' => now(),
        ]);

        // Membres
        foreach ($students->take(3) as $student) {
            GroupMembership::create([
                'user_id' => $student->id,
                'group_id' => $infoClub->id,
                'role' => 'member',
                'status' => 'approved',
                'joined_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // 2. Groupe de Projet - Application Mobile
        $projectGroup = Group::create([
            'created_by' => $students[0]->id,
            'name' => 'Projet App Mobile 2026',
            'description' => 'Groupe de travail pour le projet d\'application mobile. Stack: React Native, Laravel API. Deadline: Juin 2026',
            'category' => 'project',
            'visibility' => 'private',
            'members_count' => 4,
        ]);

        GroupMembership::create([
            'user_id' => $students[0]->id,
            'group_id' => $projectGroup->id,
            'role' => 'admin',
            'status' => 'approved',
            'joined_at' => now(),
        ]);

        foreach ($students->slice(1, 3) as $student) {
            GroupMembership::create([
                'user_id' => $student->id,
                'group_id' => $projectGroup->id,
                'role' => 'member',
                'status' => 'approved',
                'joined_at' => now()->subDays(rand(1, 10)),
            ]);
        }

        // 3. Académique - Mathématiques Avancées
        $mathGroup = Group::create([
            'created_by' => $teacher->id,
            'name' => 'Mathématiques Avancées',
            'description' => 'Espace d\'entraide pour les cours d\'analyse, algèbre et probabilités. Posez vos questions et partagez des ressources !',
            'category' => 'academic',
            'visibility' => 'public',
            'members_count' => 5,
        ]);

        GroupMembership::create([
            'user_id' => $teacher->id,
            'group_id' => $mathGroup->id,
            'role' => 'admin',
            'status' => 'approved',
            'joined_at' => now(),
        ]);

        foreach ($students as $student) {
            GroupMembership::create([
                'user_id' => $student->id,
                'group_id' => $mathGroup->id,
                'role' => 'member',
                'status' => 'approved',
                'joined_at' => now()->subDays(rand(1, 20)),
            ]);
        }

        // 4. Carrière - Stages & Emplois
        $careerGroup = Group::create([
            'created_by' => $teacher->id,
            'name' => 'Stages & Opportunités',
            'description' => 'Partagez et découvrez des offres de stage, d\'alternance et d\'emploi. Conseils CV et entretiens bienvenus !',
            'category' => 'career',
            'visibility' => 'public',
            'members_count' => 6,
        ]);

        GroupMembership::create([
            'user_id' => $teacher->id,
            'group_id' => $careerGroup->id,
            'role' => 'admin',
            'status' => 'approved',
            'joined_at' => now(),
        ]);

        // Plus d'étudiants pour ce groupe
        $moreStudents = User::where('role', 'student')->take(10)->get();
        foreach ($moreStudents as $student) {
            $existing = GroupMembership::where('user_id', $student->id)
                ->where('group_id', $careerGroup->id)
                ->exists();
            if (!$existing) {
                GroupMembership::create([
                    'user_id' => $student->id,
                    'group_id' => $careerGroup->id,
                    'role' => 'member',
                    'status' => 'approved',
                    'joined_at' => now()->subDays(rand(1, 45)),
                ]);
            }
        }

        // 5. Club Robotique
        $roboticsGroup = Group::create([
            'created_by' => $students[2]->id,
            'name' => 'Club Robotique',
            'description' => 'Conception et programmation de robots. Ateliers pratiques tous les samedis. Rejoignez-nous !',
            'category' => 'club',
            'visibility' => 'public',
            'members_count' => 3,
        ]);

        GroupMembership::create([
            'user_id' => $students[2]->id,
            'group_id' => $roboticsGroup->id,
            'role' => 'admin',
            'status' => 'approved',
            'joined_at' => now(),
        ]);

        GroupMembership::create([
            'user_id' => $students[3]->id,
            'group_id' => $roboticsGroup->id,
            'role' => 'moderator',
            'status' => 'approved',
            'joined_at' => now()->subDays(5),
        ]);

        GroupMembership::create([
            'user_id' => $students[4]->id,
            'group_id' => $roboticsGroup->id,
            'role' => 'member',
            'status' => 'approved',
            'joined_at' => now()->subDays(2),
        ]);

        $this->command->info('5 groupes créés avec succès !');
    }
}
