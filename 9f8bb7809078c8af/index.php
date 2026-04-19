<?php
require_once 'auth.php';

$content = content_load();
$csrf    = csrf_generate();
$success = isset($_GET['success']);
$error   = isset($_GET['error']) ? $_GET['error'] : null;

// Raccourcis
$meta     = $content['meta']     ?? [];
$nav      = $content['nav']      ?? [];
$hero     = $content['hero']     ?? [];
$about    = $content['about']    ?? [];
$skills   = $content['skills']   ?? [];
$projects = $content['projects'] ?? [];
$contact  = $content['contact']  ?? [];
$footer   = $content['footer']   ?? [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Administration – Portfolio</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        body { background: #1a1a2e; color: #e0e0e0; }
        .admin-header { background: #16213e; border-bottom: 2px solid #8b5cf6; padding: 1rem 2rem; }
        .admin-header h1 { font-size: 1.4rem; margin: 0; color: #a78bfa; }
        .nav-tabs .nav-link { color: #9ca3af; border-color: transparent; }
        .nav-tabs .nav-link.active { background: #2d2d4e; color: #a78bfa; border-color: #8b5cf6 #8b5cf6 #2d2d4e; }
        .card { background: #2d2d4e; border: 1px solid #3d3d6e; }
        .form-control, .form-select { background: #1e1e3e; color: #e0e0e0; border-color: #4d4d8e; }
        .form-control:focus, .form-select:focus { background: #1e1e3e; color: #e0e0e0; border-color: #8b5cf6; box-shadow: 0 0 0 0.2rem rgba(139,92,246,.25); }
        .form-label { color: #a78bfa; font-weight: 600; font-size: .85rem; }
        .section-block { background: #252545; border: 1px solid #3d3d6e; border-radius: 8px; padding: 1.5rem; margin-bottom: 1rem; }
        .section-block h6 { color: #7c3aed; border-bottom: 1px solid #3d3d6e; padding-bottom: .5rem; margin-bottom: 1rem; }
        .skill-row { display: flex; gap: .5rem; align-items: center; margin-bottom: .5rem; }
        .skill-row input { flex: 1; }
        .btn-remove { background: #7f1d1d; border: none; color: #fca5a5; padding: .25rem .5rem; border-radius: 4px; cursor: pointer; font-size: .8rem; }
        .btn-add { background: #1e3a5f; border: 1px solid #3b82f6; color: #93c5fd; font-size: .82rem; }
        .feature-row { display: flex; gap: .5rem; align-items: center; margin-bottom: .5rem; }
        .feature-row input { flex: 1; }
        .alert-success { background: #14532d; border-color: #15803d; color: #86efac; }
        .alert-danger { background: #7f1d1d; border-color: #991b1b; color: #fca5a5; }
        .logout-btn { color: #f87171; text-decoration: none; font-size: .85rem; }
        .logout-btn:hover { color: #fca5a5; }
    </style>
</head>
<body>

<div class="admin-header d-flex justify-content-between align-items-center">
    <h1>🛠 Administration – Portfolio</h1>
    <a href="logout.php" class="logout-btn">Déconnexion ✕</a>
</div>

<div class="container-fluid py-4 px-4">

    <?php if ($success): ?>
        <div class="alert alert-success">Contenu sauvegardé avec succès.</div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger">Erreur : <?= h($error) ?></div>
    <?php endif; ?>

    <!-- Onglets -->
    <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-general">Général</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-hero">Hero</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-about">À propos</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-skills">Compétences</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-projects">Projets</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-footer">Pied de page</button></li>
    </ul>

    <div class="tab-content">

        <!-- ==================== GÉNÉRAL ==================== -->
        <div class="tab-pane fade show active" id="tab-general">
            <form method="post" action="save.php">
                <input type="hidden" name="csrf_token" value="<?= h($csrf) ?>">
                <input type="hidden" name="section" value="meta_nav">
                <div class="section-block">
                    <h6>Métadonnées</h6>
                    <div class="mb-3">
                        <label class="form-label">Titre de la page (onglet navigateur)</label>
                        <input type="text" name="meta_title" class="form-control" value="<?= h($meta['title'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nom affiché dans la navbar</label>
                        <input type="text" name="nav_brand" class="form-control" value="<?= h($nav['brand'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Copyright (pied de page)</label>
                        <input type="text" name="footer_copyright" class="form-control" value="<?= h($footer['copyright'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Titre section Contact</label>
                        <input type="text" name="contact_title" class="form-control" value="<?= h($contact['title'] ?? '') ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>

        <!-- ==================== HERO ==================== -->
        <div class="tab-pane fade" id="tab-hero">
            <form method="post" action="save.php" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= h($csrf) ?>">
                <input type="hidden" name="section" value="hero">
                <div class="section-block">
                    <h6>Section d'accueil</h6>
                    <div class="mb-3">
                        <label class="form-label">Phrase d'accroche</label>
                        <input type="text" name="greeting" class="form-control" value="<?= h($hero['greeting'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nom complet</label>
                        <input type="text" name="name" class="form-control" value="<?= h($hero['name'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Titre / Poste</label>
                        <input type="text" name="role" class="form-control" value="<?= h($hero['role'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description courte</label>
                        <textarea name="description" class="form-control" rows="2"><?= h($hero['description'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Texte du bouton</label>
                        <input type="text" name="cta" class="form-control" value="<?= h($hero['cta'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Photo de profil actuelle</label>
                        <div class="mb-2">
                            <?php if (!empty($hero['photo'])): ?>
                                <img src="../<?= h($hero['photo']) ?>" alt="Photo actuelle" style="max-height:100px; border-radius:8px;">
                            <?php endif; ?>
                        </div>
                        <label class="form-label">Remplacer la photo (JPG/PNG/WebP, max 2 Mo)</label>
                        <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png,image/webp">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>

        <!-- ==================== À PROPOS ==================== -->
        <div class="tab-pane fade" id="tab-about">
            <form method="post" action="save.php">
                <input type="hidden" name="csrf_token" value="<?= h($csrf) ?>">
                <input type="hidden" name="section" value="about">
                <div class="section-block">
                    <h6>Section « À propos »</h6>
                    <div class="mb-4">
                        <label class="form-label">Titre de la section</label>
                        <input type="text" name="about_title" class="form-control" value="<?= h($about['title'] ?? '') ?>">
                    </div>
                    <?php foreach (($about['cards'] ?? []) as $i => $card): ?>
                        <div class="section-block mb-3">
                            <h6>Carte <?= $i + 1 ?></h6>
                            <div class="mb-2">
                                <label class="form-label">Icône Font Awesome (ex: fas fa-cloud)</label>
                                <input type="text" name="cards[<?= $i ?>][icon]" class="form-control" value="<?= h($card['icon'] ?? '') ?>">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Titre</label>
                                <input type="text" name="cards[<?= $i ?>][title]" class="form-control" value="<?= h($card['title'] ?? '') ?>">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Description</label>
                                <textarea name="cards[<?= $i ?>][text]" class="form-control" rows="2"><?= h($card['text'] ?? '') ?></textarea>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>

        <!-- ==================== COMPÉTENCES ==================== -->
        <div class="tab-pane fade" id="tab-skills">
            <form method="post" action="save.php" id="skillsForm">
                <input type="hidden" name="csrf_token" value="<?= h($csrf) ?>">
                <input type="hidden" name="section" value="skills">
                <div class="section-block">
                    <h6>Section Compétences</h6>
                    <div class="mb-4">
                        <label class="form-label">Titre de la section</label>
                        <input type="text" name="skills_title" class="form-control" value="<?= h($skills['title'] ?? '') ?>">
                    </div>
                    <?php foreach (($skills['categories'] ?? []) as $ci => $cat): ?>
                        <div class="section-block mb-3">
                            <h6>Catégorie <?= $ci + 1 ?></h6>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="form-label">Icône catégorie</label>
                                    <input type="text" name="categories[<?= $ci ?>][icon]" class="form-control" value="<?= h($cat['icon'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Titre catégorie</label>
                                    <input type="text" name="categories[<?= $ci ?>][title]" class="form-control" value="<?= h($cat['title'] ?? '') ?>">
                                </div>
                            </div>
                            <label class="form-label">Compétences</label>
                            <div id="skills-cat-<?= $ci ?>">
                                <?php foreach (($cat['items'] ?? []) as $si => $skill): ?>
                                    <div class="skill-row">
                                        <input type="text" name="categories[<?= $ci ?>][items][<?= $si ?>][icon]" class="form-control" style="max-width:220px;" placeholder="Icône FA" value="<?= h($skill['icon'] ?? '') ?>">
                                        <input type="text" name="categories[<?= $ci ?>][items][<?= $si ?>][name]" class="form-control" placeholder="Nom" value="<?= h($skill['name'] ?? '') ?>">
                                        <button type="button" class="btn-remove" onclick="this.parentElement.remove()">✕</button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-sm btn-add mt-2" onclick="addSkill(<?= $ci ?>)">+ Ajouter une compétence</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>

        <!-- ==================== PROJETS ==================== -->
        <div class="tab-pane fade" id="tab-projects">
            <form method="post" action="save.php" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= h($csrf) ?>">
                <input type="hidden" name="section" value="projects">
                <div class="section-block">
                    <h6>Section Projets</h6>
                    <div class="mb-4">
                        <label class="form-label">Titre de la section</label>
                        <input type="text" name="projects_title" class="form-control" value="<?= h($projects['title'] ?? '') ?>">
                    </div>
                    <?php foreach (($projects['items'] ?? []) as $pi => $proj): ?>
                        <div class="section-block mb-3">
                            <h6>Projet <?= $pi + 1 ?></h6>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="form-label">Titre</label>
                                    <input type="text" name="items[<?= $pi ?>][title]" class="form-control" value="<?= h($proj['title'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Description courte (carte)</label>
                                    <input type="text" name="items[<?= $pi ?>][description]" class="form-control" value="<?= h($proj['description'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Image (carte) – URL ou chemin relatif</label>
                                <input type="text" name="items[<?= $pi ?>][image]" class="form-control" value="<?= h($proj['image'] ?? '') ?>">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Image modale – URL ou chemin relatif</label>
                                <input type="text" name="items[<?= $pi ?>][modal_image]" class="form-control" value="<?= h($proj['modal_image'] ?? '') ?>">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Introduction modale</label>
                                <textarea name="items[<?= $pi ?>][modal_intro]" class="form-control" rows="2"><?= h($proj['modal_intro'] ?? '') ?></textarea>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Fonctionnalités (une par ligne)</label>
                                <textarea name="items[<?= $pi ?>][modal_features_raw]" class="form-control" rows="4"><?= h(implode("\n", $proj['modal_features'] ?? [])) ?></textarea>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Technologies utilisées</label>
                                <input type="text" name="items[<?= $pi ?>][tech]" class="form-control" value="<?= h($proj['tech'] ?? '') ?>">
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="form-label">Lien GitHub</label>
                                    <input type="text" name="items[<?= $pi ?>][github]" class="form-control" value="<?= h($proj['github'] ?? '#') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Lien « Voir le projet »</label>
                                    <input type="text" name="items[<?= $pi ?>][url]" class="form-control" value="<?= h($proj['url'] ?? '#') ?>">
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Uploader une image pour ce projet (JPG/PNG/WebP, max 2 Mo)</label>
                                <input type="file" name="project_image_<?= $pi ?>" class="form-control" accept="image/jpeg,image/png,image/webp">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>

        <!-- ==================== PIED DE PAGE ==================== -->
        <div class="tab-pane fade" id="tab-footer">
            <form method="post" action="save.php">
                <input type="hidden" name="csrf_token" value="<?= h($csrf) ?>">
                <input type="hidden" name="section" value="footer">
                <div class="section-block">
                    <h6>Réseaux sociaux & Pied de page</h6>
                    <div class="mb-3">
                        <label class="form-label">LinkedIn (URL)</label>
                        <input type="text" name="linkedin" class="form-control" value="<?= h($footer['linkedin'] ?? '#') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">GitHub (URL)</label>
                        <input type="text" name="github" class="form-control" value="<?= h($footer['github'] ?? '#') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Twitter (URL)</label>
                        <input type="text" name="twitter" class="form-control" value="<?= h($footer['twitter'] ?? '#') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Instagram (URL)</label>
                        <input type="text" name="instagram" class="form-control" value="<?= h($footer['instagram'] ?? '#') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Texte copyright</label>
                        <input type="text" name="copyright" class="form-control" value="<?= h($footer['copyright'] ?? '') ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>

    </div><!-- /tab-content -->
</div>

<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap-bundle.min.js"></script>
<script>
// Compteurs d'index par catégorie pour l'ajout dynamique de compétences
const skillCounts = <?= json_encode(array_map(fn($cat) => count($cat['items'] ?? []), $skills['categories'] ?? [])) ?>;

function addSkill(catIndex) {
    const container = document.getElementById('skills-cat-' + catIndex);
    const idx = skillCounts[catIndex]++;
    const row = document.createElement('div');
    row.className = 'skill-row';
    row.innerHTML = `
        <input type="text" name="categories[${catIndex}][items][${idx}][icon]" class="form-control" style="max-width:220px;" placeholder="Icône FA (ex: fab fa-docker)">
        <input type="text" name="categories[${catIndex}][items][${idx}][name]" class="form-control" placeholder="Nom de la compétence">
        <button type="button" class="btn-remove" onclick="this.parentElement.remove()">✕</button>
    `;
    container.appendChild(row);
}
</script>
</body>
</html>
