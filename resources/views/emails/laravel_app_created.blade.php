<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Votre projet Laravel a démarré !</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #1f2937;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #1f2937; min-height: 100vh;">
        <tr>
            <td align="center" style="padding: 20px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #111827; border-radius: 12px; border: 3px solid #4b5563;">
                    <tr>
                        <td style="padding: 40px; text-align: center;">
                            <!-- Logo -->
                            <img src="https://www.digmma.fr/assets/img/logo/logo2.png" alt="Digmma" style="height: 24px; margin: 0 auto 44px auto; display: block;" />
                            
                            <!-- Contenu principal -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding-bottom: 24px;">
                                        <h1 style="color: #e6007e; font-size: 20px; font-weight: bold; margin: 0; padding: 0;">Votre projet Laravel est lancé 🚀</h1>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td style="padding-bottom: 24px;">
                                        <p style="color: #d1d5db; font-size: 16px; line-height: 1.5; margin: 0; padding: 0;">Bonjour {{ $app->first_name }} {{ $app->last_name }},</p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td style="padding-bottom: 24px;">
                                        <p style="color: #d1d5db; font-size: 16px; line-height: 1.5; margin: 0; padding: 0;">
                                            Nous avons le plaisir de vous informer que votre projet <strong>{{ $app->site_name }}</strong> est en cours de création !
                                        </p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td style="padding-bottom: 24px;">
                                        <p style="color: #d1d5db; font-size: 16px; line-height: 1.5; margin: 0; padding: 0;">
                                            Vous pouvez suivre l'avancement ou accéder à votre projet via le lien ci-dessous :
                                        </p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td style="padding-bottom: 24px;">
                                        <p style="color: #e6007e; font-size: 16px; font-weight: 600; margin: 0; padding: 0;">{{ $url }}</p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td style="padding-bottom: 24px;">
                                        <table cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                                            <tr>
                                                <td style="background-color: #e6007e; border-radius: 8px; padding: 8px 20px;">
                                                    <a href="https://{{ $url }}" style="color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 500; display: inline-block;">
                                                        Voir mon projet
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td style="padding-bottom: 24px;">
                                        <p style="color: #d1d5db; font-size: 16px; line-height: 1.5; margin: 0; padding: 0;">N'hésitez pas à nous contacter si vous avez la moindre question ou besoin d'assistance.</p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td style="padding-bottom: 24px;">
                                        <p style="color: #e6007e; font-size: 16px; font-weight: 600; margin: 0; padding: 0;">Bienvenue dans l'aventure Digmma !</p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Séparateur -->
                            <hr style="border: none; border-top: 1px solid rgba(230, 0, 126, 0.3); margin: 24px 0;" />
                            
                            <!-- Footer -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="text-align: center;">
                                        <p style="color: #9ca3af; font-size: 12px; margin: 0; padding: 0;">© 2025 Digmma. Tous droits réservés.<br />
                                            <a href="https://www.digmma.fr" style="color: #9ca3af; text-decoration: underline;">www.digmma.fr</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html> 