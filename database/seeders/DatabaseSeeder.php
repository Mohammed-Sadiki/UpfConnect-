<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Event;
use App\Models\Connection;
use App\Models\Message;
use App\Models\Notification;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $password = Hash::make('password');

        // 1 Admin
        $admin = User::create([
            'name' => 'Admin System',
            'email' => 'admin@upfconnect.edu',
            'password' => $password,
            'role' => 'admin',
            'bio' => 'Administrateur système de la plateforme UPFConnect.',
            'department' => 'IT',
            'email_verified_at' => now(),
        ]);
        
        Profile::create([
            'user_id' => $admin->id,
            'skills' => ['Administration', 'Laravel', 'VueJS'],
            'interests' => ['Tech', 'Networking']
        ]);

        // 5 Teachers
        $teachers = [];
        for ($i = 1; $i <= 5; $i++) {
            $teacher = User::create([
                'name' => "Prof. " . $faker->lastName,
                'email' => "teacher$i@upfconnect.edu",
                'password' => $password,
                'role' => 'teacher',
                'bio' => $faker->realText(100),
                'department' => $faker->randomElement(['Informatique', 'Mathématiques', 'Physique', 'Biologie', 'Lettres']),
                'email_verified_at' => now(),
            ]);
            Profile::create([
                'user_id' => $teacher->id,
                'linkedin_url' => 'https://linkedin.com/in/' . $faker->slug,
                'skills' => ['Enseignement', 'Recherche', 'Pédagogie'],
            ]);
            $teachers[] = $teacher;
        }

        // 30 Students
        $students = [];
        for ($i = 1; $i <= 30; $i++) {
            $student = User::create([
                'name' => $faker->firstName . ' ' . $faker->lastName,
                'email' => "student$i@upfconnect.edu",
                'password' => $password,
                'role' => 'student',
                'bio' => $faker->realText(80),
                'department' => $faker->randomElement(['Informatique', 'Mathématiques', 'Physique', 'Biologie', 'Lettres']),
                'email_verified_at' => now(),
            ]);
            Profile::create([
                'user_id' => $student->id,
                'github_url' => 'https://github.com/' . $faker->userName,
                'skills' => ['PHP', 'HTML', 'CSS', 'JavaScript'],
                'interests' => ['Web Dev', 'IA', 'Sports'],
                'year_of_study' => $faker->numberBetween(1, 5)
            ]);
            $students[] = $student;
        }

        $allUsers = array_merge([$admin], $teachers, $students);

        // 50 Posts
        $posts = [];
        for ($i = 0; $i < 50; $i++) {
            $user = $faker->randomElement($allUsers);
            $posts[] = Post::create([
                'user_id' => $user->id,
                'title' => $faker->sentence,
                'content' => $faker->paragraphs(3, true),
                'image' => $faker->boolean(40) ? 'https://picsum.photos/800/400?random=' . $i : null,
                'visibility' => $faker->randomElement(['public', 'university', 'private']),
                'likes_count' => $faker->numberBetween(0, 50),
            ]);
        }

        // 100+ Comments
        for ($i = 0; $i < 120; $i++) {
            $post = $faker->randomElement($posts);
            $user = $faker->randomElement($allUsers);
            Comment::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
                'content' => $faker->sentence,
            ]);
        }

        // 20 Events
        for ($i = 0; $i < 20; $i++) {
            $teacher = $faker->randomElement($teachers);
            Event::create([
                'user_id' => $teacher->id,
                'title' => 'Événement: ' . $faker->catchPhrase,
                'description' => $faker->realText(200),
                'location' => $faker->randomElement(['Amphi A', 'Amphi B', 'Salle 101', 'Bibliothèque', 'En ligne']),
                'event_date' => $faker->dateTimeBetween('now', '+2 months'),
                'image' => $faker->boolean(50) ? 'https://picsum.photos/600/300?random=' . $i : null,
            ]);
        }

        // Connections & Messages
        for ($i = 0; $i < 50; $i++) {
            $sender = $faker->randomElement($students);
            $receiver = $faker->randomElement($students);
            if ($sender->id !== $receiver->id) {
                Connection::firstOrCreate(
                    [
                        'sender_id' => min($sender->id, $receiver->id),
                        'receiver_id' => max($sender->id, $receiver->id)
                    ],
                    ['status' => $faker->randomElement(['pending', 'accepted'])]
                );
            }
        }
        
        for ($i = 0; $i < 40; $i++) {
            $sender = $faker->randomElement($allUsers);
            $receiver = $faker->randomElement($allUsers);
            if ($sender->id !== $receiver->id) {
                Message::create([
                    'sender_id' => $sender->id,
                    'receiver_id' => $receiver->id,
                    'body' => $faker->sentence,
                    'read_at' => $faker->boolean(70) ? now() : null,
                ]);
            }
        }

        // Notifications
        for ($i = 0; $i < 30; $i++) {
            $user = $faker->randomElement($allUsers);
            Notification::create([
                'user_id' => $user->id,
                'type' => 'new_like',
                'data' => ['message' => 'Quelqu\'un a aimé votre post.'],
                'read_at' => $faker->boolean(50) ? now() : null,
            ]);
        }
    }
}
