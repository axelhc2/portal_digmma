@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div x-data="{
    showEditModal: false,
    showDeleteModal: false,
    showCreateModal: false,
    categoryId: null,
    categoryName: '',
    newCategoryName: '',
    openEditModal(id, name) {
        this.categoryId = id;
        this.categoryName = name;
        this.showEditModal = true;
    },
    closeEditModal() {
        this.showEditModal = false;
        this.categoryId = null;
        this.categoryName = '';
    },
    openDeleteModal(id) {
        this.categoryId = id;
        this.showDeleteModal = true;
    },
    closeDeleteModal() {
        this.showDeleteModal = false;
        this.categoryId = null;
    },
    openCreateModal() {
        this.showCreateModal = true;
        this.newCategoryName = '';
    },
    closeCreateModal() {
        this.showCreateModal = false;
        this.newCategoryName = '';
    },
    saveEdit() {
        fetch(`/entreprise/categories/${this.categoryId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').content
            },
            body: JSON.stringify({ name: this.categoryName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
    },
    createCategory() {
        const token = document.querySelector('meta[name=\'csrf-token\']');
        if (!token) {
            console.error('CSRF token not found');
            return;
        }

        if (!this.newCategoryName || this.newCategoryName.trim() === '') {
            alert('Veuillez entrer un nom de catégorie');
            return;
        }

        fetch('/entreprise/categories', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token.content
            },
            body: JSON.stringify({ name: this.newCategoryName })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Une erreur est survenue');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Une erreur est survenue');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'Une erreur est survenue lors de la création de la catégorie');
        });
    },
    confirmDelete() {
        const token = document.querySelector('meta[name=\'csrf-token\']');
        if (!token) {
            console.error('CSRF token not found');
            return;
        }
        
        if (this.categoryId) {
            fetch(`/entreprise/categories/${this.categoryId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token.content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }
}">
    <div class="p-4 sm:ml-64 mt-16">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Catégories d'Entreprises</h1>
                <button 
                    @click="openCreateModal()"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Ajouter une catégorie
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($categories as $category)
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $category->name }}</h3>
                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                            <button x-on:click="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}')"
                                    class="edit-category inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Modifier
                            </button>
                            <button x-on:click="openDeleteModal({{ $category->id }})"
                                    class="delete-category inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal de création -->
    <div 
        x-show="showCreateModal" 
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[9999] flex items-center justify-center px-4 sm:px-6"
    >
        <!-- Overlay -->
        <div 
            class="fixed inset-0 bg-gray-500/70 bg-opacity-50 transition-opacity"
            @click="closeCreateModal()"
            aria-hidden="true"
        ></div>

        <!-- Modal -->
        <div 
            class="relative z-[10000] bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full"
            @click.away="closeCreateModal()"
        >
            <div class="px-6 py-5">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto sm:mx-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg font-medium text-gray-900">
                            Créer une nouvelle catégorie
                        </h3>
                        <div class="mt-4">
                            <label for="newCategoryName" class="block text-sm font-medium text-gray-700">Nom de la catégorie</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input 
                                    type="text" 
                                    x-model="newCategoryName"
                                    id="newCategoryName"
                                    class="block w-full rounded-xl border border-gray-300 bg-gray-50 py-2 px-4 pr-10 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-200 ease-in-out"
                                    placeholder="Nom de la catégorie"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse">
                <button 
                    @click="createCategory()"
                    class="ml-2 w-full sm:w-auto inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                >
                    Créer
                </button>
                <button 
                    @click="closeCreateModal()"
                    class="mt-3 sm:mt-0 sm:ml-3 w-full sm:w-auto inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Annuler
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de modification -->
    <div 
        x-show="showEditModal" 
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[9999] flex items-center justify-center px-4 sm:px-6"
    >
        <!-- Overlay -->
        <div 
            class="fixed inset-0 bg-gray-500/70 bg-opacity-50 transition-opacity"
            @click="closeEditModal()"
            aria-hidden="true"
        ></div>

        <!-- Modal -->
        <div 
            class="relative z-[10000] bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full"
            @click.away="closeEditModal()"
        >
            <div class="px-6 py-5">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto sm:mx-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg font-medium text-gray-900">
                            Modifier la catégorie
                        </h3>
                        <div class="mt-4">
                            <label for="categoryName" class="block text-sm font-medium text-gray-700">Nom de la catégorie</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input 
                                    type="text" 
                                    x-model="categoryName"
                                    id="categoryName"
                                    class="block w-full rounded-xl border border-gray-300 bg-gray-50 py-2 px-4 pr-10 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-200 ease-in-out"
                                    placeholder="Nom de la catégorie"
                                >
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse">
                <button 
                    @click="saveEdit()"
                    class="ml-2 w-full sm:w-auto inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Enregistrer
                </button>
                <button 
                    @click="closeEditModal()"
                    class="mt-3 sm:mt-0 sm:ml-3 w-full sm:w-auto inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Annuler
                </button>
            </div>
        </div>
    </div>

    <div 
    x-show="showDeleteModal" 
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[9999] flex items-center justify-center px-4 sm:px-6"
    >
    <!-- Overlay -->
    <div 
        class="fixed inset-0 bg-gray-500/75 bg-opacity-75 transition-opacity" 
        @click="closeDeleteModal()"
        aria-hidden="true"
    ></div>

    <!-- Modal -->
    <div 
        class="relative z-[10000] bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full"
        @click.away="closeDeleteModal()"
    >
        <div class="px-6 py-5">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto sm:mx-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>

                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg font-medium text-gray-900">
                        Confirmer la suppression
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Êtes-vous sûr de vouloir supprimer cette catégorie ? Cette action est irréversible.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse">
            <button 
                @click="confirmDelete()"
                class="ml-2 w-full sm:w-auto inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
            >
                Supprimer
            </button>
            <button 
                @click="closeDeleteModal()"
                class="mt-3 sm:mt-0 sm:ml-3 w-full sm:w-auto inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                Annuler
            </button>
        </div>
    </div>
    </div>
</div>
@endsection
