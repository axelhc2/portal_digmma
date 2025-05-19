@extends('layouts.app')

@section('title', 'Modifier une Licence')

@section('content')
<div class="p-4 sm:ml-64 mt-16">
    <div class="bg-white rounded-xl shadow-lg p-8 w-full">
        <div class="mb-8 flex flex-wrap gap-4 items-center justify-between border-b pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Modifier une Licence</h2>
                <p class="text-gray-600 mt-1">Mettez à jour les paramètres de la licence existante</p>
            </div>
            <a href="{{ route('licenses.index') }}" 
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour à la liste
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2 text-red-500"></i>
                    <h3 class="font-medium">Veuillez corriger les erreurs suivantes :</h3>
                </div>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('licenses.update', $license) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Licence -->
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-key mr-2 text-gray-600"></i>
                    Clé de Licence
                </h3>
                <div class="flex gap-4">
                    <input type="text" name="license" id="license" value="{{ old('license', $license->license) }}" 
                           class="flex-1 h-10 text-base rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500 bg-white px-4"
                           readonly>
                    <button type="button" id="generateLicense" 
                            class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors duration-200 flex items-center">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Régénérer
                    </button>
                </div>
                <p class="mt-2 text-sm text-gray-600">La clé de licence peut être régénérée si nécessaire</p>
            </div>

            <!-- Domaines -->
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-globe mr-2 text-gray-600"></i>
                    Domaines Autorisés
                </h3>
                <div id="domains-container" class="space-y-3">
                    @foreach ($license->domain as $domain)
                        <div class="flex gap-3">
                            <input type="text" name="domains[]" 
                                   class="flex-1 h-10 text-base rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500 bg-white px-4"
                                   placeholder="exemple.com"
                                   value="{{ old('domains[]', $domain) }}">
                            <button type="button" class="remove-domain px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors duration-200">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="addDomain" 
                        class="mt-3 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>Ajouter un domaine
                </button>
                <p class="mt-2 text-sm text-gray-600">Modifiez la liste des domaines autorisés à utiliser cette licence</p>
            </div>

            <!-- IPs -->
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-network-wired mr-2 text-gray-600"></i>
                    Adresses IP Autorisées
                </h3>
                <div id="ips-container" class="space-y-3">
                    @foreach ($license->ip as $ip)
                        <div class="flex gap-3">
                            <input type="text" name="ips[]" 
                                   class="flex-1 h-10 text-base rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500 bg-white px-4"
                                   placeholder="192.168.1.1"
                                   value="{{ old('ips[]', $ip) }}">
                            <button type="button" class="remove-ip px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors duration-200">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="addIp" 
                        class="mt-3 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>Ajouter une IP
                </button>
                <p class="mt-2 text-sm text-gray-600">Modifiez la liste des adresses IP autorisées à utiliser cette licence</p>
            </div>

            <!-- Options de Durée -->
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-clock mr-2 text-gray-600"></i>
                    Options de Durée
                </h3>
                
                <!-- Lifetime -->
                <div class="flex items-center space-x-3 mb-4 p-3 bg-white rounded-lg border border-gray-200">
                    <input type="checkbox" name="lifetime" id="lifetime" value="1" 
                           class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-500"
                           {{ old('lifetime', $license->lifetime) ? 'checked' : '' }}>
                    <label for="lifetime" class="text-base font-medium text-gray-700">Licence à vie (sans expiration)</label>
                </div>

                <!-- Durée -->
                <div id="duration-container" class="space-y-3">
                    <label class="block text-base font-medium text-gray-700">Durée de la licence</label>
                    <div class="flex gap-4">
                        <input type="number" name="duration_value" min="1" 
                               class="w-32 h-10 text-base rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500 bg-white px-4"
                               placeholder="Durée"
                               value="{{ old('duration_value', $license->lifetime ? '' : 1) }}">
                        <select name="duration_unit" 
                                class="flex-1 h-10 text-base rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500 bg-white px-4 appearance-none bg-no-repeat bg-right pr-10"
                                style="background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 20 20%27%3E%3Cpath stroke=%27%236B7280%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%271.5%27 d=%27M6 8l4 4 4-4%27/%3E%3C/svg%3E');">
                            <option value="days" {{ old('duration_unit', 'days') == 'days' ? 'selected' : '' }}>Jours</option>
                            <option value="months" {{ old('duration_unit', 'months') == 'months' ? 'selected' : '' }}>Mois</option>
                            <option value="years" {{ old('duration_unit', 'years') == 'years' ? 'selected' : '' }}>Années</option>
                        </select>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">Modifiez la durée de validité de la licence</p>
                </div>
            </div>

            <!-- Statut -->
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-toggle-on mr-2 text-gray-600"></i>
                    Statut de la Licence
                </h3>
                <div class="p-3 bg-white rounded-lg border border-gray-200">
                    <select name="status" 
                            class="w-full h-10 text-base rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500 bg-white px-4 appearance-none bg-no-repeat bg-right pr-10"
                            style="background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 20 20%27%3E%3Cpath stroke=%27%236B7280%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%271.5%27 d=%27M6 8l4 4 4-4%27/%3E%3C/svg%3E');">
                        <option value="active" {{ old('status', $license->status) == 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="suspended" {{ old('status', $license->status) == 'suspended' ? 'selected' : '' }}>Suspendu</option>
                    </select>
                    <p class="mt-2 text-sm text-gray-600">Choisissez si la licence doit être active ou suspendue</p>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t">
                <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-[#000000] to-[#33001c] text-white rounded-lg hover:from-[#33001c] hover:to-[#000000] transition-colors duration-200 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Mettre à jour la licence
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Génération de licence
    const generateLicense = () => {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let license = 'DIGMMA-';
        for (let i = 0; i < 4; i++) {
            for (let j = 0; j < 4; j++) {
                license += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            if (i < 3) license += '-';
        }
        document.getElementById('license').value = license;
    };

    document.getElementById('generateLicense').addEventListener('click', generateLicense);

    // Gestion des domaines
    const addInput = (containerId, inputName, placeholder) => {
        const container = document.getElementById(containerId);
        const div = document.createElement('div');
        div.className = 'flex gap-3';
        div.innerHTML = `
            <input type="text" name="${inputName}[]" 
                   class="flex-1 h-10 text-base rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500 bg-white px-4"
                   placeholder="${placeholder}">
            <button type="button" class="remove-input px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors duration-200">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
    };

    document.getElementById('addDomain').addEventListener('click', () => 
        addInput('domains-container', 'domains', 'exemple.com'));
    document.getElementById('addIp').addEventListener('click', () => 
        addInput('ips-container', 'ips', '192.168.1.1'));

    // Suppression des champs
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-input') || e.target.closest('.remove-domain') || e.target.closest('.remove-ip')) {
            e.target.closest('.flex').remove();
        }
    });

    // Gestion de la durée
    const lifetimeCheckbox = document.getElementById('lifetime');
    const durationContainer = document.getElementById('duration-container');

    const toggleDuration = () => {
        durationContainer.style.display = lifetimeCheckbox.checked ? 'none' : 'block';
    };

    lifetimeCheckbox.addEventListener('change', toggleDuration);
    toggleDuration(); // État initial
});
</script>
@endsection 