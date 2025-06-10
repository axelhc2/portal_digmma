@extends('layouts.app')

@section('title', 'Applications Laravel')

@section('content')
<div class="p-4 sm:ml-64 mt-16">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="mb-8 flex flex-wrap gap-4 items-center justify-between border-b pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Applications Laravel</h2>
                <p class="text-gray-600 mt-1">Statut de vos applications</p>
            </div>
            <div class="flex gap-4">
                <button onclick="window.location.reload()" 
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Actualiser
                </button>
                <a href="{{ route('laravel.create') }}" 
                   class="px-4 py-2 bg-gradient-to-r from-[#000000] to-[#33001c] text-white rounded-lg hover:from-[#33001c] hover:to-[#000000] transition-colors duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Nouvelle Application
                </a>
            </div>
        </div>

        @if($error)
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2 text-red-500"></i>
                    <p>{{ $error }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($apps as $app)
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ $app['local_info']->site_name }}
                        </h3>
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            @if($app['online_status']['status'] === 'En ligne') 
                                bg-green-100 text-green-800
                            @else
                                bg-red-100 text-red-800
                            @endif">
                            {{ $app['online_status']['status'] }}
                            @if($app['online_status']['status'] === 'En ligne')
                                <span class="text-xs ml-1">({{ $app['online_status']['protocol'] }})</span>
                            @endif
                        </span>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-globe w-5 mr-2"></i>
                            <a href="https://{{ $app['online_status']['domain'] }}" target="_blank" 
                               class="text-sm hover:text-blue-600 hover:underline">
                                {{ $app['online_status']['domain'] }}
                            </a>
                        </div>
                        
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-user w-5 mr-2"></i>
                            <span class="text-sm">
                                {{ $app['local_info']->first_name }} 
                                {{ $app['local_info']->last_name }}
                            </span>
                        </div>

                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-key w-5 mr-2"></i>
                            <span class="text-sm">{{ $app['local_info']->license->license }}</span>
                        </div>
                    </div>

                    <div class="mt-4 flex gap-2">
                        <form action="{{ route('laravel.destroy', $app['local_info']->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette application ?')"
                                    class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200 flex items-center text-sm">
                                <i class="fas fa-trash mr-2"></i>
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                    <p class="text-gray-500">Aucune application trouvée</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection 