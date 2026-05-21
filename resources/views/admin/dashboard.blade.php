<x-app-layout>
    <div class="min-h-screen p-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">📊 Tableau de Bord Administrateur</h1>
            <p class="text-gray-600">Vue d'ensemble de l'activité de la plateforme</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-8">
            <div class="upf-card p-4 text-center hover:shadow-md transition">
                <div class="text-blue-600 text-2xl font-bold mb-1">{{ $stats['users_count'] }}</div>
                <div class="text-xs text-gray-500 uppercase font-medium">Utilisateurs</div>
            </div>
            <div class="upf-card p-4 text-center hover:shadow-md transition">
                <div class="text-green-600 text-2xl font-bold mb-1">{{ $stats['active_students'] }}</div>
                <div class="text-xs text-gray-500 uppercase font-medium">Étudiants</div>
            </div>
            <div class="upf-card p-4 text-center hover:shadow-md transition">
                <div class="text-purple-600 text-2xl font-bold mb-1">{{ $stats['posts_count'] }}</div>
                <div class="text-xs text-gray-500 uppercase font-medium">Posts</div>
            </div>
            <div class="upf-card p-4 text-center hover:shadow-md transition">
                <div class="text-orange-600 text-2xl font-bold mb-1">{{ $stats['events_count'] }}</div>
                <div class="text-xs text-gray-500 uppercase font-medium">Événements</div>
            </div>
            <div class="upf-card p-4 text-center hover:shadow-md transition">
                <div class="text-pink-600 text-2xl font-bold mb-1">{{ $stats['messages_count'] }}</div>
                <div class="text-xs text-gray-500 uppercase font-medium">Messages</div>
            </div>
            <div class="upf-card p-4 text-center hover:shadow-md transition">
                <div class="text-indigo-600 text-2xl font-bold mb-1">{{ $stats['connections_count'] }}</div>
                <div class="text-xs text-gray-500 uppercase font-medium">Connexions</div>
            </div>
            <div class="upf-card p-4 text-center hover:shadow-md transition">
                <div class="text-teal-600 text-2xl font-bold mb-1">{{ $stats['comments_count'] }}</div>
                <div class="text-xs text-gray-500 uppercase font-medium">Commentaires</div>
            </div>
        </div>

        <!-- Charts Row 1 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- User Growth Chart -->
            <div class="bg-white p-6 shadow-md border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📈 Croissance des Inscriptions</h3>
                <div class="h-64"><canvas id="userGrowthChart"></canvas></div>
            </div>
            <!-- Post Activity Chart -->
            <div class="bg-white p-6 shadow-md border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📝 Activité des Publications</h3>
                <div class="h-64"><canvas id="postActivityChart"></canvas></div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Role Distribution -->
            <div class="bg-white p-6 shadow-md border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">🎯 Répartition par Rôle</h3>
                <div class="h-48 flex items-center justify-center"><canvas id="roleChart"></canvas></div>
            </div>
            <!-- Daily Activity -->
            <div class="bg-white p-6 shadow-md lg:col-span-2 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Activité des 7 Derniers Jours</h3>
                <div class="h-48"><canvas id="dailyActivityChart"></canvas></div>
            </div>
        </div>

        <!-- Bottom Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Contributors -->
            <div class="bg-white p-6 shadow-md border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">🏆 Top Contributeurs</h3>
                <div class="space-y-3">
                    @forelse($topContributors as $index => $contributor)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white font-bold text-sm">{{ $index + 1 }}</div>
                            <img src="{{ $contributor->avatar ? asset('storage/' . $contributor->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($contributor->name).'&background=3b82f6&color=fff' }}"
                                 class="w-10 h-10 rounded-full object-cover" alt="">
                            <div>
                                <div class="font-medium text-gray-900">{{ $contributor->name }}</div>
                                <div class="text-xs text-gray-500">{{ $contributor->posts_count }} publications</div>
                            </div>
                        </div>
                        <div class="text-blue-600 font-semibold">{{ $contributor->posts_count }} posts</div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">Aucun contributeur pour le moment</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white p-6 shadow-md border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">🔔 Activité Récente</h3>
                <div class="space-y-3 max-h-80 overflow-y-auto">
                    @forelse($recentActivity as $activity)
                    <div class="flex items-start space-x-3 p-3 {{ $activity['type'] === 'post' ? 'bg-blue-50' : 'bg-green-50' }} rounded-lg">
                        <div class="w-2 h-2 rounded-full {{ $activity['type'] === 'post' ? 'bg-blue-500' : 'bg-green-500' }} mt-2"></div>
                        <div class="flex-1">
                            <div class="text-sm">
                                <span class="font-semibold text-gray-900">{{ $activity['user'] }}</span>
                                <span class="text-gray-600">{{ $activity['action'] }}</span>
                            </div>
                            <div class="text-xs text-gray-400 mt-1">{{ $activity['time']->diffForHumans() }}</div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">Aucune activité récente</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // User Growth Chart
        const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
        new Chart(userGrowthCtx, {
            type: 'line',
            data: {
                labels: @json($userGrowthLabels),
                datasets: [{
                    label: 'Nouveaux utilisateurs',
                    data: @json($userGrowthData),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });

        // Post Activity Chart
        const postActivityCtx = document.getElementById('postActivityChart').getContext('2d');
        new Chart(postActivityCtx, {
            type: 'bar',
            data: {
                labels: @json($postActivityLabels),
                datasets: [{
                    label: 'Publications',
                    data: @json($postActivityData),
                    backgroundColor: '#8b5cf6',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Role Distribution Chart
        const roleCtx = document.getElementById('roleChart').getContext('2d');
        new Chart(roleCtx, {
            type: 'doughnut',
            data: {
                labels: @json($roleLabels),
                datasets: [{
                    data: @json($roleData),
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // Daily Activity Chart
        const dailyCtx = document.getElementById('dailyActivityChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: @json($weeklyActivity),
                datasets: [
                    {
                        label: 'Posts',
                        data: @json($dailyPosts),
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Messages',
                        data: @json($dailyMessages),
                        borderColor: '#ec4899',
                        backgroundColor: 'rgba(236, 72, 153, 0.1)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</x-app-layout>
