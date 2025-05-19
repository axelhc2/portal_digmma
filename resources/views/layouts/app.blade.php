<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Digma - Agence de Communication à Châtellerault</title>
    
    <meta name="description" content="Axial-Host, fournisseur français d'hébergement web, de serveurs VPS et de solutions pour les entreprises et les particuliers. Offrant des services de haute performance et une sécurité renforcée.">
    <meta name="keywords" content="Axial-Host, hébergement web, serveur VPS, protection DDoS, serveur de jeux, services web, hébergement France, serveur privé, performance web, Cloud, serveurs dédiés, technologie, solutions d'hébergement, support 24/7, cybersécurité">
    <meta name="author" content="Axial-Host">
    <meta name="robots" content="index, follow">
    <meta name="robots" content="noarchive">
    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">
    <meta name="googlebot" content="noarchive">
    <meta name="google-site-verification" content="votre-code-de-verification-google-site-verification">
    <meta name="googlebot-news" content="index, follow">
    <meta name="p:domain_verify" content="votre-code-pinterest-verification">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title')">
    <meta name="twitter:description" content="Axial-Host propose des solutions d'hébergement web et VPS, avec des serveurs puissants et un support technique 24/7 pour une performance optimale.">
    <meta name="twitter:image" content="https://axial-host.fr/images/logo.png">
    <meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="Découvrez Axial-Host, l'expert français en hébergement web, serveurs VPS, et solutions personnalisées pour des entreprises performantes.">
    <meta property="og:image" content="https://axial-host.fr/images/logo.png">
    <meta property="og:image:secure_url" content="https://axial-host.fr/images/logo.png">
    <meta property="og:url" content="https://axial-host.fr">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="Axial-Host">
    <meta property="og:updated_time" content="2025-04-08T12:00:00Z">
    <meta property="og:article:author" content="https://www.facebook.com/axialhost">
    <meta property="og:article:publisher" content="https://www.facebook.com/axialhost">
    <meta property="og:article:published_time" content="2025-04-08T12:00:00Z">
    <meta property="og:article:modified_time" content="2025-04-08T12:00:00Z">
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Axial-Host",
      "url": "https://axial-host.fr",
      "logo": "https://axial-host.fr/images/logo.png",
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+33 1 23 45 67 89",
        "contactType": "Customer Service",
        "areaServed": "FR",
        "availableLanguage": "French"
      },
      "sameAs": [
        "https://www.facebook.com/axialhost",
        "https://twitter.com/axialhost",
        "https://www.linkedin.com/company/axialhost"
      ]
    }
    </script>
    
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://axial-host.fr">
    <meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="Axial-Host est une société spécialisée dans l'hébergement web, les serveurs VPS et la gestion de serveurs de jeux en ligne. Profitez d'une haute disponibilité et d'un support technique 24/7.">
    <meta property="og:image" content="https://axial-host.fr/images/logo.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    
    <meta name="googlebot" content="noarchive">
    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    
    <link rel="icon" href="https://axial-host.fr/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="https://axial-host.fr/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://axial-host.fr/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="https://axial-host.fr/favicon-16x16.png">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    
    <script src="https://kit.fontawesome.com/9aa2220903.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
    <link rel="canonical" href="https://axial-host.fr">
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
      n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '694278813024243'); 
      fbq('track', 'PageView');
    </script>

@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
@vite(['resources/css/app.css', 'resources/js/app.js'])
@else

@endif
    


    
</head>
<body class="bg-gray-50">   
    @if (!in_array(Route::currentRouteName(), ['login', 'two-factor.create', 'two-factor']) && !request()->is('404'))
    @include('layouts.sidebar')
    @endif
<main>
    @yield('content')
</main>

    @if (!in_array(Route::currentRouteName(), ['login', 'two-factor.create', 'two-factor']))
    
@endif

    
    


</body>
</html>