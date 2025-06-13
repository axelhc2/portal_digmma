@extends('layouts.app')

@section('title', 'Créer une Nouvelle Configuration Laravel')

@section('content')
<div class="p-4 sm:ml-64 mt-16" x-data="createLaravel()">
    <div class="bg-white rounded-xl shadow-lg p-8 w-full">
        <div x-show="showApiWaitingMessage" class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-clock mr-2 text-blue-500"></i>
                <h3 class="font-medium">La requête API prend du temps, merci de patienter...</h3>
            </div>
        </div>

        <div class="mb-8 flex flex-wrap gap-4 items-center justify-between border-b pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Nouvelle Configuration Laravel</h2>
                <p class="text-gray-600 mt-1">Configurez une nouvelle installation Laravel</p>
            </div>
            <a href="{{ route('laravel.index') }}" 
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour à la liste
            </a>
        </div>

        <div x-show="successMessage" class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2 text-green-500"></i>
                <h3 class="font-medium" x-text="successMessage"></h3>
            </div>
        </div>

        <div x-show="errors.length > 0" class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2 text-red-500"></i>
                <h3 class="font-medium">Veuillez corriger les erreurs suivantes :</h3>
            </div>
            <ul class="mt-2 list-disc list-inside text-sm">
                <template x-for="error in errors" :key="error">
                    <li x-text="error"></li>
                </template>
            </ul>
        </div>

        <form @submit.prevent="submitForm" class="space-y-6">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user mr-2 text-gray-600"></i>
                    Informations Personnelles
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                        <input type="text" name="first_name" id="first_name" 
                               class="w-full px-4 py-2 text-base rounded-lg border-2 border-gray-300 focus:border-[#e6007e] focus:ring focus:ring-pink-200 focus:ring-opacity-50 bg-white"
                               value="{{ old('first_name') }}" required>
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                        <input type="text" name="last_name" id="last_name" 
                               class="w-full px-4 py-2 text-base rounded-lg border-2 border-gray-300 focus:border-[#e6007e] focus:ring focus:ring-pink-200 focus:ring-opacity-50 bg-white"
                               value="{{ old('last_name') }}" required>
                    </div>
                </div>

                <div class="mt-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" 
                           class="w-full px-4 py-2 text-base rounded-lg border-2 border-gray-300 focus:border-[#e6007e] focus:ring focus:ring-pink-200 focus:ring-opacity-50 bg-white"
                           value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-globe mr-2 text-gray-600"></i>
                    Information du Site
                </h3>
                
                <div>
                    <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">Nom du Site</label>
                    <input type="text" name="site_name" id="site_name" 
                           class="w-full px-4 py-2 text-base rounded-lg border-2 border-gray-300 focus:border-[#e6007e] focus:ring focus:ring-pink-200 focus:ring-opacity-50 bg-white"
                           value="{{ old('site_name') }}" required>
                </div>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-link mr-2 text-gray-600"></i>
                    Domaine
                </h3>
                
                <div class="flex gap-4">
                    <input type="text" name="domain" id="domain" 
                           class="flex-1 px-4 py-2 text-base rounded-lg border-2 border-gray-300 bg-gray-100 cursor-not-allowed"
                           readonly>
                    <button type="button" id="generateDomain" 
                            class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors duration-200 flex items-center">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Générer
                    </button>
                </div>
                <p class="mt-2 text-sm text-gray-600">Le domaine sera automatiquement généré avec le format : customer[xxxx].digmma.site</p>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-shield-alt mr-2 text-gray-600"></i>
                    Configuration de la Licence
                </h3>

                <div class="mb-8 bg-white p-6 rounded-lg border border-gray-100">
                    <h4 class="text-md font-medium text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-key mr-2 text-gray-600"></i>
                        Clé de Licence
                    </h4>
                    <div class="flex gap-4">
                        <input type="text" name="license" id="license" value="{{ old('license') }}" 
                               class="flex-1 h-10 text-base rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500 bg-white px-4"
                               readonly>
                        <button type="button" id="generateLicense" 
                                class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors duration-200 flex items-center">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Générer
                        </button>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">Une clé de licence unique sera générée automatiquement</p>
                </div>

                <div class="mb-8 bg-white p-6 rounded-lg border border-gray-100">
                    <h4 class="text-md font-medium text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-network-wired mr-2 text-gray-600"></i>
                        Adresses IP Autorisées
                    </h4>
                    <div id="ips-container" class="space-y-3">
                        <div class="flex gap-3">
                            <input type="text" name="ips[]" 
                                   class="flex-1 h-10 text-base rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500 bg-white px-4"
                                   placeholder="192.168.1.1">
                            <button type="button" class="remove-ip px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors duration-200">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" id="addIp" 
                            class="mt-3 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200 flex items-center">
                        <i class="fas fa-plus mr-2"></i>Ajouter une IP
                    </button>
                    <p class="mt-2 text-sm text-gray-600">Ajoutez toutes les adresses IP autorisées à utiliser cette licence</p>
                </div>

                <div class="bg-white p-6 rounded-lg border border-gray-100">
                    <h4 class="text-md font-medium text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-clock mr-2 text-gray-600"></i>
                        Options de Durée
                    </h4>
                    
                    <div class="flex items-center space-x-3 mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <input type="checkbox" name="lifetime" id="lifetime" value="1" 
                               class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-500">
                        <label for="lifetime" class="text-base font-medium text-gray-700">Licence à vie (sans expiration)</label>
                    </div>

                    <div id="duration-container" class="space-y-3">
                        <label class="block text-base font-medium text-gray-700">Durée de la licence</label>
                        <div class="flex gap-4">
                            <input type="number" name="duration_value" min="1" 
                                   class="w-32 h-10 text-base rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500 bg-white px-4"
                                   placeholder="Durée">
                            <select name="duration_unit" 
                                    class="flex-1 h-10 text-base rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500 bg-white px-4 appearance-none bg-no-repeat bg-right pr-10"
                                    style="background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 20 20%27%3E%3Cpath stroke=%27%236B7280%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%271.5%27 d=%27M6 8l4 4 4-4%27/%3E%3C/svg%3E');">
                                <option value="days">Jours</option>
                                <option value="months">Mois</option>
                                <option value="years">Années</option>
                            </select>
                        </div>
                        <p class="mt-2 text-sm text-gray-600">Définissez la durée de validité de la licence</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t">
                <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-[#000000] to-[#33001c] text-white rounded-lg hover:from-[#33001c] hover:to-[#000000] transition-colors duration-200 flex items-center"
                        :disabled="isSubmitting">
                    <i class="fas" :class="isSubmitting ? 'fa-spinner animate-spin' : 'fa-save'" class="mr-2"></i>
                    <span x-text="isSubmitting ? 'Création en cours...' : 'Créer l\'application laravel'"></span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function createLaravel() {
    return {
        errors: [],
        isSubmitting: false,
        successMessage: '',
        showApiWaitingMessage: false,
        apiWaitingTimer: null,

        async submitForm(e) {
            console.log('Début de la soumission du formulaire');
            this.errors = [];
            this.successMessage = '';
            this.isSubmitting = true;
            this.showApiWaitingMessage = false;
            
            this.apiWaitingTimer = setTimeout(() => {
                console.log('Affichage du message d\'attente API');
                this.showApiWaitingMessage = true;
            }, 10000);

            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);
            console.log('Données du formulaire:', data);
            
            const ipsInputs = document.querySelectorAll('#ips-container input[name="ips[]"]');
            const ips = Array.from(ipsInputs).map(input => input.value).filter(ip => ip.trim() !== '');
            console.log('IPs collectées:', ips);
            
            const generatedDomain = document.getElementById('domain').value;
            console.log('Domaine généré:', generatedDomain);
            
            const token = document.querySelector('input[name="_token"]').value;
            
            const submitData = {
                ...data,
                ips: ips,
                domain: generatedDomain,
                domains: [generatedDomain],
                _token: token,
                lifetime: data.lifetime ? 1 : 0
            };

            Object.keys(submitData).forEach(key => {
                if (submitData[key] === undefined || submitData[key] === '') {
                    delete submitData[key];
                }
            });

            console.log('Données préparées pour l\'envoi:', submitData);

            try {
                console.log('Envoi de la requête au serveur');
                const response = await fetch('{{ route('laravel.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(submitData)
                });

                const responseData = await response.json();
                console.log('Réponse du serveur:', responseData);

                clearTimeout(this.apiWaitingTimer);
                this.showApiWaitingMessage = false;

                if (response.ok) {
                    if (responseData.status === 'success') {
                        console.log('Création réussie');
                        this.successMessage = responseData.message || 'Opération réussie';
                        setTimeout(() => {
                            console.log('Redirection vers la liste');
                            window.location.href = '{{ route('laravel.index') }}';
                        }, 1500);
                    } else {
                        console.error('Erreur de création:', responseData.message);
                        this.errors = [responseData.message || 'Une erreur est survenue'];
                        document.querySelector('.bg-red-50')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                } else {
                    if (response.status === 422) {
                        console.error('Erreur de validation:', responseData.errors);
                        if (responseData.errors) {
                            this.errors = Object.values(responseData.errors).flat();
                        } else {
                            this.errors = [responseData.message || 'Erreur de validation'];
                        }
                    } else {
                        console.error('Erreur serveur:', responseData.error);
                        this.errors = [responseData.error || 'Une erreur est survenue lors de la création de l\'application.'];
                    }
                    document.querySelector('.bg-red-50')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            } catch (error) {
                console.error('Erreur lors de la communication avec le serveur:', error);
                this.errors = ['Une erreur est survenue lors de la communication avec le serveur.'];
                document.querySelector('.bg-red-50')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } finally {
                this.isSubmitting = false;
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Initialisation du formulaire de création');
    
    const generateLicense = () => {
        console.log('Génération d\'une nouvelle licence');
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let license = 'DIGMMA-';
        for (let i = 0; i < 4; i++) {
            for (let j = 0; j < 4; j++) {
                license += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            if (i < 3) license += '-';
        }
        document.getElementById('license').value = license;
        console.log('Nouvelle licence générée:', license);
    };

    document.getElementById('generateLicense').addEventListener('click', generateLicense);
    generateLicense(); 

    const addIpField = () => {
        console.log('Ajout d\'un nouveau champ IP');
        const container = document.getElementById('ips-container');
        const div = document.createElement('div');
        div.className = 'flex gap-3';
        div.innerHTML = `
            <input type="text" name="ips[]" 
                   class="flex-1 h-10 text-base rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500 bg-white px-4"
                   placeholder="192.168.1.1">
            <button type="button" class="remove-ip px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors duration-200">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
        console.log('Nouveau champ IP ajouté');

        div.querySelector('.remove-ip').addEventListener('click', function() {
            console.log('Suppression d\'un champ IP');
            div.remove();
        });
    };

    document.getElementById('addIp').addEventListener('click', addIpField);

    document.querySelectorAll('.remove-ip').forEach(button => {
        button.addEventListener('click', function() {
            console.log('Suppression d\'un champ IP existant');
            this.closest('.flex').remove();
        });
    });

    const lifetimeCheckbox = document.getElementById('lifetime');
    const durationContainer = document.getElementById('duration-container');

    const toggleDuration = () => {
        console.log('Changement du type de licence:', lifetimeCheckbox.checked ? 'à vie' : 'temporaire');
        durationContainer.style.display = lifetimeCheckbox.checked ? 'none' : 'block';
    };

    lifetimeCheckbox.addEventListener('change', toggleDuration);
    toggleDuration();
});

const generateDomain = () => {
    console.log('Génération d\'un nouveau domaine');
    const random = Math.floor(Math.random() * 9000) + 1000;
    const domain = `customer${random}.digmma.site`;
    document.getElementById('domain').value = domain;
    console.log('Nouveau domaine généré:', domain);
};

generateDomain();

document.getElementById('generateDomain').addEventListener('click', generateDomain);
</script>

<style>
.animate-spin-hover:hover {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>

@endsection 