<?php
require_once 'auth.php';

// Refuser toute méthode autre que POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    abort(405, 'Méthode non autorisée');
}

// Valider le token CSRF
if (!csrf_validate($_POST['csrf_token'] ?? '')) {
    abort(403, 'Token CSRF invalide ou expiré. Rechargez la page et réessayez.');
}

$section  = $_POST['section'] ?? '';
$content  = content_load();

// ---- Sanitisation générique ----
function clean(string $value): string
{
    return trim(strip_tags($value));
}

// ---- Valider une URL (autorise "#" comme valeur vide) ----
function clean_url(string $value): string
{
    $v = trim($value);
    if ($v === '' || $v === '#') return '#';
    $filtered = filter_var($v, FILTER_VALIDATE_URL);
    return $filtered !== false ? $filtered : '#';
}

// ---- Upload d'image sécurisé ----
function handle_image_upload(string $field, string $dest_dir): ?string
{
    if (empty($_FILES[$field]['tmp_name'])) return null;
    $file = $_FILES[$field];

    if ($file['error'] !== UPLOAD_ERR_OK) return null;

    // Taille max 2 Mo
    if ($file['size'] > 2 * 1024 * 1024) return null;

    // Valider le type MIME réel (pas juste l'extension déclarée)
    $finfo    = new finfo(FILEINFO_MIME_TYPE);
    $mime     = $finfo->file($file['tmp_name']);
    $allowed  = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($allowed[$mime])) return null;

    $ext      = $allowed[$mime];
    $filename = bin2hex(random_bytes(12)) . '.' . $ext;
    $dest     = rtrim($dest_dir, '/') . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) return null;

    return 'img/' . $filename;
}

switch ($section) {

    // ---- Général (meta + nav + footer copyright + contact title) ----
    case 'meta_nav':
        $content['meta']['title']       = clean($_POST['meta_title']        ?? '');
        $content['nav']['brand']        = clean($_POST['nav_brand']         ?? '');
        $content['footer']['copyright'] = clean($_POST['footer_copyright']  ?? '');
        $content['contact']['title']    = clean($_POST['contact_title']     ?? '');
        break;

    // ---- Hero ----
    case 'hero':
        $content['hero']['greeting']    = clean($_POST['greeting']    ?? '');
        $content['hero']['name']        = clean($_POST['name']        ?? '');
        $content['hero']['role']        = clean($_POST['role']        ?? '');
        $content['hero']['description'] = clean($_POST['description'] ?? '');
        $content['hero']['cta']         = clean($_POST['cta']         ?? '');

        $new_photo = handle_image_upload('photo', __DIR__ . '/../img');
        if ($new_photo !== null) {
            $content['hero']['photo'] = $new_photo;
        }
        break;

    // ---- À propos ----
    case 'about':
        $content['about']['title'] = clean($_POST['about_title'] ?? '');
        $raw_cards = $_POST['cards'] ?? [];
        $cards = [];
        foreach (array_slice($raw_cards, 0, 3) as $card) {
            $icon  = clean($card['icon']  ?? '');
            $title = clean($card['title'] ?? '');
            $text  = clean($card['text']  ?? '');
            if ($title !== '') {
                $cards[] = compact('icon', 'title', 'text');
            }
        }
        $content['about']['cards'] = $cards;
        break;

    // ---- Compétences ----
    case 'skills':
        $content['skills']['title'] = clean($_POST['skills_title'] ?? '');
        $raw_cats = $_POST['categories'] ?? [];
        $categories = [];
        foreach ($raw_cats as $cat) {
            $cat_icon  = clean($cat['icon']  ?? '');
            $cat_title = clean($cat['title'] ?? '');
            $items = [];
            foreach ($cat['items'] ?? [] as $skill) {
                $s_icon = clean($skill['icon'] ?? '');
                $s_name = clean($skill['name'] ?? '');
                if ($s_name !== '') {
                    $items[] = ['icon' => $s_icon, 'name' => $s_name];
                }
            }
            if ($cat_title !== '') {
                $categories[] = ['icon' => $cat_icon, 'title' => $cat_title, 'items' => $items];
            }
        }
        $content['skills']['categories'] = $categories;
        break;

    // ---- Projets ----
    case 'projects':
        $content['projects']['title'] = clean($_POST['projects_title'] ?? '');
        $raw_items = $_POST['items'] ?? [];
        $items = [];
        foreach ($raw_items as $pi => $proj) {
            $title         = clean($proj['title']       ?? '');
            $description   = clean($proj['description'] ?? '');
            $image         = clean($proj['image']       ?? '');
            $modal_image   = clean($proj['modal_image'] ?? '');
            $modal_intro   = clean($proj['modal_intro'] ?? '');
            $tech          = clean($proj['tech']        ?? '');
            $github        = clean_url($proj['github']  ?? '#');
            $url           = clean_url($proj['url']     ?? '#');

            // Fonctionnalités : une par ligne
            $features_raw  = $proj['modal_features_raw'] ?? '';
            $modal_features = array_values(array_filter(
                array_map('trim', explode("\n", $features_raw)),
                fn($l) => $l !== ''
            ));

            // Upload d'image spécifique au projet
            $new_img = handle_image_upload('project_image_' . $pi, __DIR__ . '/../img');
            if ($new_img !== null) {
                $image = $new_img;
            }

            if ($title !== '') {
                $items[] = compact('title', 'description', 'image', 'modal_image', 'modal_intro', 'modal_features', 'tech', 'github', 'url');
            }
        }
        $content['projects']['items'] = $items;
        break;

    // ---- Footer / Réseaux sociaux ----
    case 'footer':
        $content['footer']['linkedin']  = clean_url($_POST['linkedin']  ?? '#');
        $content['footer']['github']    = clean_url($_POST['github']    ?? '#');
        $content['footer']['twitter']   = clean_url($_POST['twitter']   ?? '#');
        $content['footer']['instagram'] = clean_url($_POST['instagram'] ?? '#');
        $content['footer']['copyright'] = clean($_POST['copyright']     ?? '');
        break;

    default:
        abort(400, 'Section invalide.');
}

if (!content_save($content)) {
    redirect('index.php?error=' . urlencode('Impossible d\'écrire le fichier content.json. Vérifiez les permissions.'));
}

redirect('index.php?success=1');
