@extends('layouts.app')

@section('title', 'Gestion des Licences')

@section('content')
<div class="p-4 sm:ml-64 mt-16">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="mb-6 flex flex-wrap gap-4 items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Gestion des Licences</h2>
            <a href="{{ route('licenses.create') }}" 
               class="px-4 py-2 bg-gradient-to-r from-[#000000] to-[#33001c] text-white rounded-lg hover:from-[#33001c] hover:to-[#000000] transition-colors duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Nouvelle Licence
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Licence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Domaine</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">IP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">À vie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Date d'expiration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($licenses as $license)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $license->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $license->license }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($license->domain as $domain)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $domain }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                @foreach ($license->ip as $ip)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $ip }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                {{ $license->status }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $license->lifetime ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $license->lifetime ? 'Oui' : 'Non' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                {{ $license->expiration_date ? $license->expiration_date->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                <a href="{{ route('licenses.edit', $license) }}" 
                                   class="bg-gray-900 text-white px-3 py-1 rounded-full hover:bg-gray-800 mr-2">
                                    Modifier
                                </a>
                                <form action="{{ route('licenses.destroy', $license) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-600 text-white px-3 py-1 rounded-full hover:bg-red-700"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette licence ?')">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 