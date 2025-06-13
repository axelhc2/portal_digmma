@extends('layouts.app')

@section('title', 'Espace Admin')

@section('content')
<div class="p-4 sm:ml-64 mt-16">
    <!-- Cartes statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total ce mois-ci -->
        <div class="bg-white rounded-lg p-4 border border-gray-100 hover:shadow-sm transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total ce mois-ci</p>
                    <p class="text-2xl font-bold text-gray-900">€{{ number_format($totalThisMonth ?? 5067.45, 2) }}</p>
                </div>
                <div class="w-10 h-10 flex items-center justify-center bg-blue-50 rounded-lg">
                    <i class="fas fa-euro-sign text-blue-500 text-lg"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs">
                <span class="text-green-500 mr-1">
                    <i class="fas fa-arrow-up"></i> 303.9%
                </span>
                <span class="text-gray-400">vs mois dernier</span>
            </div>
        </div>

        <!-- Payé à l'URSSAF -->
        <div class="bg-white rounded-lg p-4 border border-gray-100 hover:shadow-sm transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">A payé l'URSSAF</p>
                    <p class="text-2xl font-bold text-gray-900">€{{ number_format($urssafThisMonth ?? 1317.537, 2) }}</p>
                </div>
                <div class="w-10 h-10 flex items-center justify-center bg-green-50 rounded-lg">
                    <i class="fas fa-file-invoice text-green-500 text-lg"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs">
                <span class="text-green-500 mr-1">
                    <i class="fas fa-check"></i> À jour
                </span>
            </div>
        </div>

        <!-- Total depuis le début -->
        <div class="bg-white rounded-lg p-4 border border-gray-100 hover:shadow-sm transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total depuis le début</p>
                    <p class="text-2xl font-bold text-gray-900">€{{ number_format($totalAllTime ?? 6381.68, 2) }}</p>
                </div>
                <div class="w-10 h-10 flex items-center justify-center bg-purple-50 rounded-lg">
                    <i class="fas fa-chart-line text-purple-500 text-lg"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs">
                <span class="text-gray-400">Cumul total</span>
            </div>
        </div>

        <!-- Projets en cours -->
        <div class="bg-white rounded-lg p-4 border border-gray-100 hover:shadow-sm transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Projets en cours</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeProjects ?? 3 }}</p>
                </div>
                <div class="w-10 h-10 flex items-center justify-center bg-yellow-50 rounded-lg">
                    <i class="fas fa-tasks text-yellow-500 text-lg"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs">
                <span class="text-blue-500 mr-1">
                    <i class="fas fa-clock"></i> En cours
                </span>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Graphique des revenus -->
        <div class="bg-white rounded-lg p-4 border border-gray-100 hover:shadow-sm transition-all duration-300">
            <h3 class="text-sm font-medium text-gray-500 mb-4">Revenus sur 12 mois</h3>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Graphique des clients -->
        <div class="bg-white rounded-lg p-4 border border-gray-100 hover:shadow-sm transition-all duration-300">
            <h3 class="text-sm font-medium text-gray-500 mb-4">Évolution des clients</h3>
            <div class="h-64">
                <canvas id="clientsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Statistiques Serveur -->
    <div class="bg-white rounded-lg p-4 border border-gray-100 hover:shadow-sm transition-all duration-300">
        <h3 class="text-sm font-medium text-gray-500 mb-4">Statistiques Serveur</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- CPU -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-500">CPU</span>
                    <div class="w-8 h-8 flex items-center justify-center bg-blue-50 rounded-lg">
                        <i class="fas fa-microchip text-blue-500"></i>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <span class="text-2xl font-bold text-gray-900" id="cpu-usage">0%</span>
                    <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 rounded-full" id="cpu-bar" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- RAM -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-500">RAM</span>
                    <div class="w-8 h-8 flex items-center justify-center bg-purple-50 rounded-lg">
                        <i class="fas fa-memory text-purple-500"></i>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <span class="text-2xl font-bold text-gray-900" id="ram-usage">0%</span>
                    <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-purple-500 rounded-full" id="ram-bar" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Disque -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-500">Disque</span>
                    <div class="w-8 h-8 flex items-center justify-center bg-green-50 rounded-lg">
                        <i class="fas fa-hdd text-green-500"></i>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <span class="text-2xl font-bold text-gray-900" id="disk-usage">0%</span>
                    <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-green-500 rounded-full" id="disk-bar" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Ping -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-500">Ping (1.1.1.1)</span>
                    <div class="w-8 h-8 flex items-center justify-center bg-yellow-50 rounded-lg">
                        <i class="fas fa-network-wired text-yellow-500"></i>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <span class="text-2xl font-bold text-gray-900" id="ping-value">0ms</span>
                    <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-yellow-500 rounded-full" id="ping-bar" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique des revenus
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Revenus mensuels',
                data: [0, 0, 0, 60, 1254.23, 5067.45, 0, 0, 0, 0, 0, 0],
                borderColor: '#000000',
                backgroundColor: 'rgba(0, 0, 0, 0.05)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '€' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Graphique des clients
    const clientsCtx = document.getElementById('clientsChart').getContext('2d');
    new Chart(clientsCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Nouveaux clients',
                data: [0, 0, 0, 1, 3, 3, 0, 0, 0, 0, 0, 0],
                backgroundColor: '#33001c',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 5
                    }
                }
            }
        }
    });
});

// Fonction pour mettre à jour les statistiques du serveur
function updateServerStats() {
    fetch('/api/server-stats', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur réseau');
        }
        return response.json();
    })
    .then(data => {
        // Mise à jour CPU
        document.getElementById('cpu-usage').textContent = data.cpu + '%';
        document.getElementById('cpu-bar').style.width = data.cpu + '%';
        
        // Mise à jour RAM
        document.getElementById('ram-usage').textContent = data.ram + '%';
        document.getElementById('ram-bar').style.width = data.ram + '%';
        
        // Mise à jour Disque
        document.getElementById('disk-usage').textContent = data.disk + '%';
        document.getElementById('disk-bar').style.width = data.disk + '%';
        
        // Mise à jour Ping
        document.getElementById('ping-value').textContent = data.ping + 'ms';
        // Normaliser le ping pour la barre (0-100ms = 0-100%)
        const pingPercentage = Math.min(100, (data.ping / 100) * 100);
        document.getElementById('ping-bar').style.width = pingPercentage + '%';
    })
    .catch(error => {
        console.error('Erreur:', error);
        // Mettre à jour l'interface pour montrer l'erreur
        document.getElementById('cpu-usage').textContent = 'Erreur';
        document.getElementById('ram-usage').textContent = 'Erreur';
        document.getElementById('disk-usage').textContent = 'Erreur';
        document.getElementById('ping-value').textContent = 'Erreur';
    });
}

// Mettre à jour les stats toutes les 5 secondes
setInterval(updateServerStats, 5000);
updateServerStats(); // Première mise à jour immédiate
</script>
@endpush
@endsection
