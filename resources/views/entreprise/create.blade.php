@extends('layouts.app')

@section('title', 'Créer une entreprise')

@section('content')
<div class="p-4 sm:ml-64 mt-16">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Ajouter une nouvelle entreprise</h1>
        
        @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
        @endif
        
        <form action="{{ route('entreprises.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom de l'entreprise</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#e6007e] focus:border-transparent">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email de contact</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#e6007e] focus:border-transparent">
                </div>
                
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type d'entreprise</label>
                    <select id="type" name="type" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#e6007e] focus:border-transparent">
                        <option value="">Sélectionnez un type</option>
                        <option value="SAS/SASU" {{ old('type') == 'SAS/SASU' ? 'selected' : '' }}>SAS/SASU</option>
                        <option value="SARL" {{ old('type') == 'SARL' ? 'selected' : '' }}>SARL</option>
                        <option value="Micro/Entrepreneur" {{ old('type') == 'Micro/Entrepreneur' ? 'selected' : '' }}>Micro/Entrepreneur</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Offres</label>
                    <div class="border border-gray-300 rounded-lg p-3 max-h-40 overflow-y-auto">
                        @foreach($categories as $category)
                            <div class="flex items-center mb-2">
                                <input type="checkbox" id="category_{{ $category->id }}" name="category_id[]" value="{{ $category->id }}"
                                    {{ in_array($category->id, old('category_id', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-[#e6007e] focus:ring-[#e6007e] border-gray-300 rounded">
                                <label for="category_{{ $category->id }}" class="ml-2 text-sm text-gray-700">{{ $category->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#e6007e] focus:border-transparent">
                </div>
                
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#e6007e] focus:border-transparent">
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('entreprises.index') }}" 
                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                    Annuler
                </a>
                <button type="submit" 
                    class="px-6 py-2 bg-gradient-to-r from-[#000000] to-[#33001c] text-white rounded-lg hover:from-[#33001c] hover:to-[#000000] transition-colors duration-200">
                    Créer l'entreprise
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 