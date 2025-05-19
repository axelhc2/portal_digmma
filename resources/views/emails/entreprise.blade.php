@php
    $fullName = ($type === 'Micro/Entrepreneur') ? "$last_name $first_name" : $name;
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Proposition de services pour booster la visibilité de votre entreprise - Digmma</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <p>Bonjour {{ $fullName }},</p>

    <p>Je me permets de vous contacter pour vous proposer mes services, visant à améliorer l’image et la visibilité de votre entreprise. Nous accompagnons des entreprises de toutes tailles, qu'elles soient en pleine croissance ou déjà bien établies, en offrant des solutions sur mesure pour les aider à se démarquer et à optimiser leur présence sur le marché.</p>

    <p>Voici les services que je propose :</p>
    <ul>
        @if(in_array('Développement', $categories))
            <li><strong>Développement Web</strong> : Création ou refonte de site internet (vitrine, boutique en ligne, application web), avec un design moderne et une navigation fluide.</li>
        @endif
        @if(in_array('Audiovisuel', $categories))
            <li><strong>Vidéos promotionnelles</strong> : Réalisation de vidéos sur mesure pour présenter votre entreprise, vos produits ou services de manière professionnelle.</li>
        @endif
        @if(in_array('Graphisme', $categories))
            <li><strong>Identité visuelle</strong> : Conception de logo, charte graphique, cartes de visite, flyers, bannières, etc., afin de renforcer la cohérence visuelle de votre marque.</li>
        @endif
        @if(in_array('Impression', $categories))
            <li><strong>Impressions</strong> : Impression de supports de communication tels que cartes de visite, flyers, roll-ups, banderoles et autres matériels.</li>
        @endif
        @if(in_array('Marketing', $categories))
            <li><strong>Conseils en marketing</strong> : Accompagnement pour optimiser votre présence en ligne, améliorer votre notoriété et booster votre chiffre d'affaires.</li>
        @endif
    </ul>

    <p>Ces services sont conçus pour vous offrir un véritable levier de croissance, en vous aidant à capter l’attention de vos clients et à renforcer votre positionnement sur votre marché.</p>
    <p>Si l’un de ces services vous intéresse ou si vous souhaitez en discuter davantage, je serais ravi de vous accompagner pour définir un plan d’action adapté à vos besoins.</p>

    <p>Bien cordialement,<br>
    <strong>Axel CHETAIL - Digmma</strong><br>
    <a href="mailto:axel.chetail@digmma.fr">axel.chetail@digmma.fr</a><br>
    <a href="https://www.digmma.fr">www.digmma.fr</a><br>
    <a href="tel:+33640905049">+33 6 40 90 50 49</a></p>
</body>
</html>
