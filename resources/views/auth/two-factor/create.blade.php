@extends('layouts.app')

@section('title', 'Configuration de l\'authentification à deux facteurs')

@section('content')
<div class="bg-gray-50 min-h-screen flex flex-col">
  <div class="flex justify-center pt-6">
    <img class="h-16 w-auto" src="/assets/img/logo/logo_dark.png" alt="Logo">
  </div>
  
  <div class="flex-1 flex items-center justify-center py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6">
      <div>
        <h2 class="text-center text-3xl font-extrabold text-gray-900">
          Configuration A2F
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Sécurisez votre compte avec l'authentification à deux facteurs
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

      <div class="bg-white shadow rounded-lg p-6">
        <div class="space-y-6">
          <div>
            <h3 class="text-lg font-medium text-gray-900">Étape 1: Scannez le QR code</h3>
            <p class="mt-1 text-sm text-gray-500">
              Utilisez votre application d'authentification pour scanner le QR code ci-dessous.
            </p>
            <div class="mt-4 flex justify-center">
              <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <!-- Remplacer par le vrai QR code généré par le backend -->
                <img src="{{ $qrCodeUrl }}" alt="QR Code" class="w-48 h-48">
              </div>
            </div>
          </div>

          

          <div>
            <h3 class="text-lg font-medium text-gray-900">Étape 2: Vérifiez la configuration</h3>
            <p class="mt-1 text-sm text-gray-500">
              Entrez le code à 6 chiffres généré par votre application d'authentification pour vérifier que tout fonctionne correctement.
            </p>
            <form class="mt-4" action="{{ route('create.a2f.user') }}" method="POST" id="verifyForm">
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
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                  Activer l'authentification à deux facteurs
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const inputs = document.querySelectorAll('#verifyForm input[type="text"]');
  
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

  function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
      alert('Clé copiée dans le presse-papiers!');
    }).catch(err => {
      console.error('Erreur lors de la copie: ', err);
    });
  }
</script>
@endsection 