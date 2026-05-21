<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    protected array $admin;
    protected array $teachers;
    protected array $students;
    protected array $allUsers;

    public function run(
        $admin    = null,
        $teachers = [],
        $students = [],
        $allUsers = []
    ): void {
        $posts = [];

        // ── TEACHER POSTS ────────────────────────────────────────────────────
        $teacherPosts = [
            [
                'user'    => $teachers[0],
                'title'   => '🎓 Soutenance de HDR réussie !',
                'content' => "C'est avec une immense fierté que je vous annonce avoir obtenu mon Habilitation à Diriger des Recherches (HDR) hier après-midi devant un jury composé de 6 professeurs internationaux.\n\nMes travaux portaient sur l'apprentissage automatique appliqué aux systèmes de recommandation dans des contextes à faible connectivité, comme ceux que l'on rencontre dans les îles du Pacifique.\n\nUn grand merci à tous mes étudiants, collègues et à l'UPF pour leur soutien constant. C'est le début d'une nouvelle aventure scientifique ! 🙏\n\n#Recherche #IA #Pacifique #HDR",
                'image'   => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800',
                'visibility' => 'public',
            ],
            [
                'user'    => $teachers[1],
                'title'   => '📚 Publication acceptée dans Nature Mathematics',
                'content' => "Notre article sur la cryptographie post-quantique vient d'être accepté pour publication dans *Nature Mathematics* ! 🎉\n\nCe travail, réalisé en collaboration avec l'Université d'Auckland et le CNRS, propose un nouveau protocole d'échange de clés résistant aux attaques quantiques, adapté aux contraintes des réseaux insulaires.\n\nLe preprint sera disponible sur ArXiv dès la semaine prochaine. N'hésitez pas à partager avec vos collègues mathématiciens !\n\n#Cryptographie #Mathématiques #Publication #Recherche",
                'image'   => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?w=800',
                'visibility' => 'public',
            ],
            [
                'user'    => $teachers[2],
                'title'   => '🔭 Nouvelle subvention de recherche obtenue',
                'content' => "L'UPF et le département de Physique ont obtenu une subvention de 250 000 € de l'Agence Nationale de la Recherche (ANR) pour notre projet ASTROPOLY !\n\nCe projet sur 3 ans vise à installer un réseau de capteurs atmosphériques dans les îles Marquises pour étudier la pollution lumineuse et améliorer les conditions d'observation astronomique dans le Pacifique.\n\nNous recruterons prochainement 2 doctorants. Restez connectés ! 🌟\n\n#Physique #Astronomie #ANR #Doctorat",
                'image'   => 'https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?w=800',
                'visibility' => 'public',
            ],
            [
                'user'    => $teachers[3],
                'title'   => '⚖️ Intervention au Parlement de Polynésie française',
                'content' => "J'ai eu l'honneur d'être auditionné cette semaine par la commission environnement de l'Assemblée de la Polynésie française concernant le projet de loi sur la protection des zones marines.\n\nNous avons présenté nos recommandations juridiques basées sur les précédents internationaux (affaires de la CIJ, jurisprudences CJUE) pour renforcer la protection légale des atolls et récifs coralliens.\n\nC'est la preuve que la recherche universitaire peut directement influencer les politiques publiques. 💪\n\n#Droit #Environnement #Polynésie #DroitInternational",
                'image'   => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=800',
                'visibility' => 'public',
            ],
            [
                'user'    => $teachers[4],
                'title'   => '🐠 Découverte d\'une nouvelle espèce de corail à Fakarava',
                'content' => "Notre équipe de biologie marine vient de documenter une nouvelle espèce de corail dans le lagon de Fakarava, lors de notre campagne de terrain du mois dernier !\n\nL'espèce, provisoirement nommée *Acropora polynesiana*, présente une résistance inhabituelle aux épisodes de blanchiment thermique — une découverte qui pourrait avoir des implications majeures pour la conservation des récifs face au réchauffement climatique.\n\nLes analyses génétiques sont en cours. Article en préparation. 🪸\n\n#Biologie #CoralReef #Pacifique #Biodiversité",
                'image'   => 'https://images.unsplash.com/photo-1546026423-cc4642628d2b?w=800',
                'visibility' => 'public',
            ],
        ];

        foreach ($teacherPosts as $p) {
            $posts[] = Post::create([
                'user_id'    => $p['user']->id,
                'title'      => $p['title'],
                'content'    => $p['content'],
                'image'      => $p['image'],
                'visibility' => $p['visibility'],
                'likes_count'=> 0,
            ]);
        }

        // ── STUDENT POSTS ────────────────────────────────────────────────────
        $studentPosts = [
            [
                'user'    => $students[0],
                'title'   => '🏆 Finaliste du Hackathon Pacifique 2026 !',
                'content' => "Incroyable week-end ! Notre équipe \"ByteWave\" a atteint la finale du Hackathon Pacifique 2026 organisé à Papeete.\n\nEn 36h, nous avons développé une appli de suivi de la qualité de l'eau des lagons en temps réel, avec des capteurs IoT connectés à un dashboard Flutter.\n\nOn n'a pas gagné le grand prix, mais on a décroché le prix spécial Innovation Environnementale 🌊🥈. Merci à mes coéquipiers Roimata et Hinanui !\n\n#Hackathon #Innovation #IoT #Développement",
                'image'   => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=800',
                'visibility' => 'public',
            ],
            [
                'user'    => $students[5],
                'title'   => '💻 Mon stage de fin d\'études chez Mana — Retour d\'expérience',
                'content' => "Je reviens de 3 mois de stage au département IT de Mana (le principal FAI de Polynésie) et c'était une expérience incroyable !\n\nJ'ai travaillé sur la migration d'une partie de leur infrastructure vers le cloud AWS, la mise en place d'un système de monitoring avec Grafana & Prometheus, et j'ai participé à l'audit de sécurité de leur réseau cœur.\n\nMerci à toute l'équipe Mana pour la confiance ! Un grand merci aussi au Dr. Tefaafana dont les cours de réseaux m'ont été ultra-utiles. 🙌\n\n#Stage #Cloud #AWS #Réseaux #Informatique",
                'image'   => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
                'visibility' => 'public',
            ],
            [
                'user'    => $students[2],
                'title'   => '📊 Ma soutenance de master — J\'ai eu mention TB !',
                'content' => "C'est officiel : j'ai soutenu mon mémoire de master en Mathématiques Appliquées avec la mention Très Bien et les félicitations du jury ! 🎓\n\nMon sujet : \"Modèles prédictifs pour l'analyse des données satellitaires des atolls polynésiens\" — une collaboration entre le département maths de l'UPF et la Direction de l'Environnement de Polynésie.\n\nMerci au Professeur Hutihuti pour l'encadrement exceptionnel. Je commence une thèse CIFRE en octobre ! 🙏\n\n#Master #DataScience #Mathématiques #Soutenance",
                'image'   => 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=800',
                'visibility' => 'public',
            ],
            [
                'user'    => $students[7],
                'title'   => '🧠 Mon mémoire M2 sur les réseaux de neurones récurrents',
                'content' => "Je viens de déposer mon mémoire de M2 : \"Architectures Transformer pour la prévision climatique en milieu insulaire\". 460 pages, 18 mois de travail, des milliers de lignes de Python… et tellement de café ☕\n\nJ'ai implémenté un modèle basé sur les Vision Transformers pour analyser les images satellites de cyclones dans le Pacifique Sud, avec une précision de prévision de trajectoire de 94,3% sur 48h.\n\nSoutenance le 15 juin. Croisons les doigts ! 🤞\n\n#DeepLearning #Transformer #Climatologie #Mémoire",
                'image'   => 'https://images.unsplash.com/photo-1677442135703-1787eea5ce01?w=800',
                'visibility' => 'public',
            ],
            [
                'user'    => $students[3],
                'title'   => '🌊 Ma première plongée scientifique à Rangiroa !',
                'content' => "Dans le cadre de mes cours de biologie marine, j'ai participé à ma toute première plongée scientifique dans le lagon de Rangiroa la semaine dernière.\n\nNous avons cartographié des zones de récifs coralliens, prélevé des échantillons d'eau et observé l'état de santé des colonies de coraux. La présence du Dr. Narii nous a permis d'identifier directement sur site plusieurs espèces remarquables.\n\nC'est ça qui me confirme que j'ai choisi la bonne filière. 🐟🌺\n\n#BioMarine #Plongée #Rangiroa #Sciences",
                'image'   => 'https://images.unsplash.com/photo-1504870712357-65ea720d6078?w=800',
                'visibility' => 'public',
            ],
            [
                'user'    => $students[4],
                'title'   => '⚖️ Participation au concours de plaidoirie de Nouméa',
                'content' => "Retour de Nouvelle-Calédonie où j'ai représenté l'UPF au Concours de Plaidoirie du Pacifique 2026 !\n\nLe sujet portait sur la responsabilité des États insulaires face aux droits des peuples autochtones face au changement climatique. Un sujet qui me touche profondément.\n\nJ'ai obtenu la 2ème place dans la catégorie étudiants et le prix du meilleur argument juridique ! 🏅\n\nMerci au Professeur Pihaatae pour m'avoir préparé si efficacement. Je suis prêt pour le barreau ! ⚖️\n\n#Droit #Plaidoirie #Concours #Pacifique",
                'image'   => 'https://images.unsplash.com/photo-1589994965851-a8f479c573a9?w=800',
                'visibility' => 'public',
            ],
            [
                'user'    => $admin,
                'title'   => '🎉 Bienvenue à la nouvelle promotion 2026 !',
                'content' => "L'Université de la Polynésie Française est fière d'accueillir sa nouvelle promotion 2026 !\n\nCette année, ce sont plus de 1 200 étudiants qui rejoignent notre communauté académique, venus des 5 archipels de Polynésie et de l'ensemble du Pacifique.\n\nUPFConnect est votre espace pour partager, collaborer et vous connecter avec vos pairs et vos enseignants. N'hésitez pas à vous présenter ! 🌺🤙\n\nTe Fare Tāhito — La maison du savoir.\n\n#UPF #Rentrée2026 #Bienvenue #Polynésie",
                'image'   => 'https://images.unsplash.com/photo-1562774053-701939374585?w=800',
                'visibility' => 'public',
            ],
        ];

        foreach ($studentPosts as $p) {
            $posts[] = Post::create([
                'user_id'    => $p['user']->id,
                'title'      => $p['title'],
                'content'    => $p['content'],
                'image'      => $p['image'],
                'visibility' => $p['visibility'],
                'likes_count'=> 0,
            ]);
        }

        // ── LIKES ─────────────────────────────────────────────────────────────
        foreach ($posts as $post) {
            $likers = collect($allUsers)->random(rand(3, min(10, count($allUsers))))->all();
            foreach ($likers as $liker) {
                DB::table('post_likes')->insertOrIgnore([
                    'post_id'    => $post->id,
                    'user_id'    => $liker->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $post->update(['likes_count' => count($likers)]);
        }

        // ── COMMENTS ─────────────────────────────────────────────────────────
        $commentBank = [
            'Félicitations ! C\'est une super nouvelle 🎉',
            'Vraiment inspirant, merci pour le partage !',
            'Bravo, vous nous rendez fiers ! 🙌',
            'Très intéressant, j\'aurais aimé y assister.',
            'Super travail, continue comme ça ! 💪',
            'Magnifique découverte, hâte de lire l\'article.',
            'On est avec toi, bonne continuation !',
            'Tu nous représentes très bien, félicitations !',
            'Quel parcours inspirant ! Merci pour ces infos.',
            'Toutes mes félicitations, c\'est mérité 🏆',
            'Incroyable ! Tu me donnes envie de me dépasser.',
            'On en avait discuté en cours, super de voir l\'aboutissement !',
        ];

        foreach ($posts as $post) {
            $commenters = collect($allUsers)->random(rand(2, 5))->all();
            foreach ($commenters as $commenter) {
                $c = Comment::create([
                    'post_id'   => $post->id,
                    'user_id'   => $commenter->id,
                    'content'   => $commentBank[array_rand($commentBank)],
                    'parent_id' => null,
                ]);
                // Add 1-2 replies to each comment
                if (rand(0, 1)) {
                    $replier = collect($allUsers)->random();
                    Comment::create([
                        'post_id'   => $post->id,
                        'user_id'   => $replier->id,
                        'content'   => collect(['Tout à fait d\'accord ! 👍', 'Merci pour ce retour 🙏', 'Je partage cet avis !', 'Super commentaire ✨'])->random(),
                        'parent_id' => $c->id,
                    ]);
                }
            }
        }

        // ── NOTIFICATIONS ─────────────────────────────────────────────────────
        foreach ($posts as $post) {
            $author = collect($allUsers)->firstWhere('id', $post->user_id);
            if (!$author) continue;
            $notifier = collect($allUsers)->filter(fn($u) => $u->id !== $author->id)->random();
            \App\Models\Notification::create([
                'user_id'  => $author->id,
                'type'     => 'new_like',
                'data'     => [
                    'message'     => $notifier->name . ' a aimé votre publication.',
                    'sender_name' => $notifier->name,
                    'post_id'     => $post->id,
                ],
                'read_at'  => rand(0, 1) ? now() : null,
            ]);
        }
    }
}
