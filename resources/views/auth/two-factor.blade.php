@extends('layouts.app')

@section('title', 'Authentification à deux facteurs')

@section('content')
<div class="bg-gray-50 min-h-screen flex flex-col">
  <div class="flex justify-center pt-6">
    <img class="h-16 w-auto" src="/assets/img/logo/logo_dark.png" alt="Logo">
  </div>
  
  <div class="flex-1 flex items-center justify-center py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6">
      <div>
        <h2 class="text-center text-3xl font-extrabold text-gray-900">
          Authentification à deux facteurs
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Entrez le code à 6 chiffres envoyé à votre appareil
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

      <form class="mt-6 space-y-6" action="two-factor" method="POST" id="twoFactorForm">
        @csrf
        <div class="flex justify-center space-x-2">
          <input type="text" name="code_1" inputmode="numeric" pattern="\d*" class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" maxlength="1" autofocus>
          <input type="text" name="code_2" inputmode="numeric" pattern="\d*" class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" maxlength="1">
          <input type="text" name="code_3" inputmode="numeric" pattern="\d*" class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" maxlength="1">
          <input type="text" name="code_4" inputmode="numeric" pattern="\d*" class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" maxlength="1">
          <input type="text" name="code_5" inputmode="numeric" pattern="\d*" class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" maxlength="1">
          <input type="text" name="code_6" inputmode="numeric" pattern="\d*" class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" maxlength="1">
        </div>

        <div class="mt-6">
          <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
              <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
              </svg>
            </span>
            Vérifier
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    const inputs = document.querySelectorAll('#twoFactorForm input[type="text"]');
  
    inputs.forEach((input, index) => {
      input.addEventListener('input', (e) => {
        const value = e.target.value.replace(/[^0-9]/g, '');
        if (value) {
          input.value = value[0];
          if (index < inputs.length - 1) {
            inputs[index + 1].focus();
          }
        } else {
          input.value = '';
        }
      });
  
      input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !input.value && index > 0) {
          inputs[index - 1].focus();
        }
      });
  
      input.addEventListener('paste', (e) => {
        e.preventDefault();
        const paste = (e.clipboardData || window.clipboardData).getData('text');
        const digits = paste.replace(/\D/g, '').split('');
        digits.forEach((digit, i) => {
          if (i < inputs.length) {
            inputs[i].value = digit;
          }
        });
        const nextIndex = digits.length < inputs.length ? digits.length : inputs.length - 1;
        inputs[nextIndex].focus();
      });
    });
  </script>
  
  

@endsection 