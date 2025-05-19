@extends('layouts.app')

@section('title', 'Connexion')

@section('content')


<div class="bg-gray-50 min-h-screen flex flex-col">
  <div class="flex justify-center pt-6">
    <img class="h-16 w-auto" src="/assets/img/logo/logo_dark.png" alt="Logo">
  </div>
  
  <div class="flex-1 flex items-center justify-center py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6">
      <div>
        <h2 class="text-center text-3xl font-extrabold text-gray-900">
          Connexion à votre compte
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Entrez vos identifiants pour accéder à votre espace
        </p>
      </div>

      @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
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

      <form class="mt-6 space-y-6" action="{{ route('login') }}" method="POST">
        @csrf
        <input type="hidden" name="remember" value="true">
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="email-address" class="sr-only">Adresse email</label>
            <input id="email-address" name="email" type="email" autocomplete="email" required 
                   class="appearance-none rounded-none relative block w-full px-3 py-2 border @error('email') border-red-300 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                   placeholder="Adresse email"
                   value="{{ old('email') }}">
            @error('email')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label for="password" class="sr-only">Mot de passe</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required 
                   class="appearance-none rounded-none relative block w-full px-3 py-2 border @error('password') border-red-300 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                   placeholder="Mot de passe">
            @error('password')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="mt-6">
          <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
              <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
              </svg>
            </span>
            Se connecter
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
