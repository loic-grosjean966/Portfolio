<?php
/**
 * Fonctions de sécurité partagées pour le panneau d'administration.
 * Inclure ce fichier EN PREMIER dans chaque script admin.
 */

// Démarrage sécurisé de la session
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => false,  // false en local — remettre true sur le VPS (HTTPS)
        'httponly' => true,   // Inaccessible via JavaScript
        'samesite' => 'Strict',
    ]);
    session_start();
}

// Régénérer l'ID de session périodiquement (prévention fixation de session)
if (empty($_SESSION['last_regeneration'])) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 300) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

/**
 * Génère un token CSRF et le stocke en session.
 */
function csrf_generate(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Valide le token CSRF soumis via POST.
 * Utilise une comparaison à temps constant pour prévenir les attaques timing.
 */
function csrf_validate(string $token): bool
{
    return !empty($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Échappe une valeur pour une sortie HTML sécurisée.
 */
function h(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Charge le contenu depuis content.json.
 * Retourne le tableau associatif ou un tableau vide en cas d'erreur.
 */
function content_load(): array
{
    $path = __DIR__ . '/../data/content.json';
    if (!file_exists($path)) {
        return [];
    }
    $data = json_decode(file_get_contents($path), true);
    return is_array($data) ? $data : [];
}

/**
 * Sauvegarde le contenu dans content.json de façon atomique.
 * Écriture dans un fichier temporaire puis renommage pour éviter la corruption.
 */
function content_save(array $data): bool
{
    $path = __DIR__ . '/../data/content.json';
    $tmp  = $path . '.tmp.' . bin2hex(random_bytes(4));
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    if ($json === false) {
        return false;
    }
    if (file_put_contents($tmp, $json, LOCK_EX) === false) {
        return false;
    }
    return rename($tmp, $path);
}

/**
 * Redirige vers une URL et arrête l'exécution.
 */
function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

/**
 * Retourne un message d'erreur HTTP et arrête l'exécution.
 */
function abort(int $code, string $message = ''): never
{
    http_response_code($code);
    exit($message ?: $code);
}
