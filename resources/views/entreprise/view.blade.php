@extends('layouts.app')

@section('title', 'Espace Admin')

@section('content')
@php
$entreprises = $entreprises ?? collect([]);
@endphp

<div class="p-4 sm:ml-64 mt-16" 
x-data="entrepriseList()" 
x-init="initData({{ $entreprises->toJson() }})"
>
<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="mb-6 flex flex-wrap gap-4 items-center justify-between">
        <div class="flex flex-wrap gap-4 flex-1">
            <div class="flex-1">
                <input 
                type="text" 
                x-model="searchQuery"
                placeholder="Rechercher..."
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            <select 
            x-model="searchField"
            class="px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
            <option value="nom_entreprise">Nom de l'entreprise</option>
            <option value="nom_famille">Nom de famille</option>
            <option value="prenom">Prénom</option>
            <option value="email">Email</option>
        </select>
    </div>
    
    <a href="{{ route('entreprises.create') }}" 
    class="px-4 py-2 bg-gradient-to-r from-[#000000] to-[#33001c] text-white rounded-lg hover:from-[#33001c] hover:to-[#000000] transition-colors duration-200 flex items-center">
    <i class="fas fa-plus mr-2"></i>
    Ajouter une entreprise
</a>
</div>

<!-- Message si aucune entreprise -->
<div x-show="filteredEntreprises.length === 0" class="text-center py-8">
    <p class="text-gray-500 text-lg">Aucune entreprise trouvée. <a href="{{ route('entreprises.create') }}" class="text-[#e6007e] hover:underline">Ajouter une entreprise</a></p>
</div>

<!-- Tableau -->
<div class="overflow-x-auto" x-show="filteredEntreprises.length > 0">
    <table class="min-w-full bg-white rounded-lg overflow-hidden">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Nom de l'entreprise</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Mail de contact</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Catégorie</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Nom de famille</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Prénom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Date d'ajout</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <template x-for="entreprise in filteredEntreprises" :key="entreprise.id">
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap" x-text="entreprise.nom_entreprise"></td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap" x-text="entreprise.email"></td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <div class="flex flex-wrap gap-1">
                            <template x-for="categorie in entreprise.categorie.split(',')" :key="categorie">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 whitespace-nowrap" x-text="categorie.trim()"></span>
                            </template>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap" x-text="entreprise.type"></td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap" x-text="entreprise.nom_famille"></td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap" x-text="entreprise.prenom"></td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap" x-text="entreprise.date_ajout"></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span 
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                        :class="{
                            'bg-green-100 text-green-800': entreprise.status === 'send',
                            'bg-red-100 text-red-800': entreprise.status === 'error',
                            'bg-yellow-100 text-yellow-800': entreprise.status === 'waiting_send'
                        }"
                        x-text="{
                            send: 'Envoyé',
                            error: 'Erreur',
                            waiting_send: 'En attente'
                        }[entreprise.status]"
                        ></span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                        <button 
                            @click="openEditModal(entreprise)"
                            class="bg-gray-900 text-white px-3 py-1 rounded-full hover:bg-gray-800 mr-2">
                            Modifier
                        </button>
                        <button 
                            @click="confirmDelete(entreprise.id)"
                            class="bg-red-600 text-white px-3 py-1 rounded-full hover:bg-red-700">
                            Supprimer
                        </button>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
</div>

<!-- Modal de modification -->
<div 
    x-show="showEditModal" 
    class="fixed inset-0 z-[9999] overflow-y-auto" 
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full relative z-[10000]">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Modifier l'entreprise
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nom de l'entreprise</label>
                                <input 
                                    type="text" 
                                    x-model="editingEntreprise.nom_entreprise"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email de contact</label>
                                <input 
                                    type="email" 
                                    x-model="editingEntreprise.email"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Type d'entreprise</label>
                                <select 
                                    x-model="editingEntreprise.type"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="SAS/SASU">SAS/SASU</option>
                                    <option value="SARL">SARL</option>
                                    <option value="Micro/Entrepreneur">Micro/Entrepreneur</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                                <input 
                                    type="text" 
                                    x-model="editingEntreprise.prenom"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nom de famille</label>
                                <input 
                                    type="text" 
                                    x-model="editingEntreprise.nom_famille"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Catégories</label>
                                <div class="border border-gray-300 rounded-lg p-3 max-h-40 overflow-y-auto">
                                    <template x-for="category in categories" :key="category.id">
                                        <div class="flex items-center mb-2">
                                            <input 
                                                type="checkbox" 
                                                :id="'edit_category_' + category.id"
                                                :value="category.id"
                                                x-model="editingEntreprise.category_ids"
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            >
                                            <label :for="'edit_category_' + category.id" class="ml-2 text-sm text-gray-700" x-text="category.name"></label>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button 
                    @click="updateEntreprise()"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Enregistrer
                </button>
                <button 
                    @click="showEditModal = false"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation -->
<div 
    x-show="showDeleteModal" 
    class="fixed inset-0 z-[9999] overflow-y-auto" 
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-[10000]">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Confirmer la suppression
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Êtes-vous sûr de vouloir supprimer cette entreprise ? Cette action est irréversible.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button 
                    @click="deleteEntreprise()"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Supprimer
                </button>
                <button 
                    @click="showDeleteModal = false"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<script>
    function entrepriseList() {
        return {
            searchQuery: '',
            searchField: 'nom_entreprise',
            entreprises: [],
            showDeleteModal: false,
            showEditModal: false,
            selectedEntrepriseId: null,
            editingEntreprise: {
                id: null,
                nom_entreprise: '',
                email: '',
                type: '',
                prenom: '',
                nom_famille: '',
                category_ids: []
            },
            categories: @json($categories ?? []),
            initData(data) {
                this.entreprises = data;
            },
            openEditModal(entreprise) {
                this.editingEntreprise = {
                    id: entreprise.id,
                    nom_entreprise: entreprise.nom_entreprise || '',
                    email: entreprise.email || '',
                    type: entreprise.type || '',
                    prenom: entreprise.prenom || '',
                    nom_famille: entreprise.nom_famille || '',
                    category_ids: (entreprise.category_ids || []).map(id => Number(id))
                };
                this.showEditModal = true;
            },
            async updateEntreprise() {
                try {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const response = await fetch(`{{ url('/entreprise') }}/${this.editingEntreprise.id}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            name: this.editingEntreprise.nom_entreprise,
                            email: this.editingEntreprise.email,
                            type: this.editingEntreprise.type,
                            first_name: this.editingEntreprise.prenom,
                            last_name: this.editingEntreprise.nom_famille,
                            category_id: this.editingEntreprise.category_ids
                        })
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Erreur lors de la modification de l\'entreprise');
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                    alert('Erreur lors de la modification de l\'entreprise');
                }
            },
            confirmDelete(id) {
                this.selectedEntrepriseId = id;
                this.showDeleteModal = true;
            },
            async deleteEntreprise() {
                try {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const response = await fetch(`/entreprise/${this.selectedEntrepriseId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.entreprises = this.entreprises.filter(e => e.id !== this.selectedEntrepriseId);
                        this.showDeleteModal = false;
                    } else {
                        alert('Erreur lors de la suppression de l\'entreprise');
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                    alert('Erreur lors de la suppression de l\'entreprise');
                }
            },
            get filteredEntreprises() {
                const query = this.searchQuery.toLowerCase();
                return this.entreprises.filter((entreprise) => {
                    switch (this.searchField) {
                        case 'nom_entreprise':
                            return entreprise.nom_entreprise?.toLowerCase().includes(query);
                        case 'nom_famille':
                            return entreprise.nom_famille?.toLowerCase().includes(query);
                        case 'prenom':
                            return entreprise.prenom?.toLowerCase().includes(query);
                        case 'email':
                            return entreprise.email?.toLowerCase().includes(query);
                        default:
                            return true;
                    }
                });
            }
        };
    }
</script>
@endsection
