<?php
require_once 'auth.php';

// Détruire toutes les données de session
$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

session_destroy();

// Forcer le navigateur à oublier les credentials Basic Auth
// (envoyer un 401 avec des credentials invalides)
header('WWW-Authenticate: Basic realm="logout"');
http_response_code(401);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Déconnexion</title>
    <meta http-equiv="refresh" content="2;url=../">
    <style>
        body { background: #1a1a2e; color: #a78bfa; font-family: sans-serif;
               display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .box { text-align: center; }
    </style>
</head>
<body>
    <div class="box">
        <p>Vous êtes déconnecté. Redirection en cours…</p>
        <a href="../" style="color:#8b5cf6;">Retour au site</a>
    </div>
</body>
</html>
