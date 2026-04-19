<?php
$content_file = __DIR__ . '/data/content.json';
$content = file_exists($content_file)
    ? (json_decode(file_get_contents($content_file), true) ?? [])
    : [];

// Raccourcis avec valeurs de fallback
$meta     = $content['meta']     ?? [];
$nav      = $content['nav']      ?? [];
$hero     = $content['hero']     ?? [];
$about    = $content['about']    ?? [];
$skills   = $content['skills']   ?? [];
$projects = $content['projects'] ?? [];
$contact  = $content['contact']  ?? [];
$footer   = $content['footer']   ?? [];

function h(mixed $v): string
{
    return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="<?= h($meta['lang'] ?? 'fr') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($meta['title'] ?? 'Portfolio') ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Font-Awesome via CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"><?= h($nav['brand'] ?? 'Mon Portfolio') ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#accueil"><i class="fa-solid fa-house"></i> Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="#apropos">À propos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#competences">Compétences</a></li>
                    <li class="nav-item"><a class="nav-link" href="#projets">Projets</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="accueil" class="hero">
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-6 fade-in-up">
                    <h1><?= h($hero['greeting'] ?? 'Bonjour, je suis') ?><br><?= h($hero['name'] ?? '') ?></h1>
                    <p><?= h($hero['role'] ?? '') ?></p>
                    <p class="mt-4"><?= h($hero['description'] ?? '') ?></p>
                    <a href="#contact" class="btn btn-light btn-lg mt-3"><?= h($hero['cta'] ?? 'Me contacter') ?></a>
                </div>
                <div class="col-lg-6 text-center fade-in-up" style="animation-delay: 0.2s;">
                    <img src="<?= h($hero['photo'] ?? 'img/P1010394.JPG') ?>" alt="Profile" class="profile-img">
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="apropos">
        <div class="container">
            <h2 class="section-title"><?= h($about['title'] ?? 'À propos de moi') ?></h2>
            <div class="row">
                <?php foreach ($about['cards'] ?? [] as $i => $card): ?>
                <div class="col-md-4 mb-4">
                    <div class="card text-center p-4 fade-in-up" <?= $i > 0 ? 'style="animation-delay:' . ($i * 0.2) . 's;"' : '' ?>>
                        <div class="card-icon"><i class="<?= h($card['icon'] ?? '') ?>"></i></div>
                        <h4><?= h($card['title'] ?? '') ?></h4>
                        <p><?= h($card['text'] ?? '') ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="competences">
        <div class="container">
            <h2 class="section-title"><?= h($skills['title'] ?? 'Mes Compétences') ?></h2>
            <div class="row">
                <?php foreach ($skills['categories'] ?? [] as $cat): ?>
                <div class="col-md-4">
                    <h5 class="skill-category">
                        <i class="<?= h($cat['icon'] ?? '') ?>"></i> <?= h($cat['title'] ?? '') ?>
                    </h5>
                    <?php foreach ($cat['items'] ?? [] as $skill): ?>
                    <div class="skill-item">
                        <div class="skill-name">
                            <i class="<?= h($skill['icon'] ?? '') ?> skill-icon"></i>
                            <?= h($skill['name'] ?? '') ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projets">
        <div class="container">
            <h2 class="section-title"><?= h($projects['title'] ?? 'Mes Projets') ?></h2>
            <div class="row">
                <?php foreach ($projects['items'] ?? [] as $pi => $proj): $mid = 'projectModal' . ($pi + 1); ?>
                <div class="col-md-4 mb-4">
                    <div class="project-card card" data-bs-toggle="modal" data-bs-target="#<?= h($mid) ?>">
                        <img src="<?= h($proj['image'] ?? '') ?>" class="project-img" alt="<?= h($proj['title'] ?? '') ?>">
                        <div class="project-overlay">
                            <div class="project-info">
                                <h4><?= h($proj['title'] ?? '') ?></h4>
                                <p><?= h($proj['description'] ?? '') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <h2 class="section-title"><?= h($contact['title'] ?? 'Contactez-moi') ?></h2>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form class="contact-form">
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Votre nom" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Votre email" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" rows="5" placeholder="Votre message" required></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Envoyer le message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <div class="social-icons mb-3">
                <a href="<?= h($footer['linkedin']  ?? '#') ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin"></i></a>
                <a href="<?= h($footer['github']    ?? '#') ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-github"></i></a>
                <a href="<?= h($footer['twitter']   ?? '#') ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>
                <a href="<?= h($footer['instagram'] ?? '#') ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
            </div>
            <p>&copy; <?= h($footer['copyright'] ?? date('Y') . ' Mon Portfolio. Tous droits réservés.') ?></p>
        </div>
    </footer>

    <!-- Scroll to top button -->
    <button id="scrollTop" type="button" aria-label="Scroll to top"><i class="fas fa-arrow-up"></i></button>

    <!-- Modales des projets -->
    <?php foreach ($projects['items'] ?? [] as $pi => $proj): $mid = 'projectModal' . ($pi + 1); ?>
    <div class="modal fade" id="<?= h($mid) ?>" tabindex="-1" aria-labelledby="<?= h($mid) ?>Label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="<?= h($mid) ?>Label"><?= h($proj['title'] ?? '') ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="<?= h($proj['modal_image'] ?? $proj['image'] ?? '') ?>" class="img-fluid mb-3" alt="<?= h($proj['title'] ?? '') ?>">
                    <p><?= h($proj['modal_intro'] ?? '') ?></p>
                    <?php if (!empty($proj['modal_features'])): ?>
                    <ul>
                        <?php foreach ($proj['modal_features'] as $feature): ?>
                        <li><?= h($feature) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <?php if (!empty($proj['tech'])): ?>
                    <p><strong>Technologies utilisées :</strong> <?= h($proj['tech']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <?php if (!empty($proj['github']) && $proj['github'] !== '#'): ?>
                    <a href="<?= h($proj['github']) ?>" class="btn btn-dark" target="_blank" rel="noopener noreferrer"><i class="fab fa-github"></i> GitHub</a>
                    <?php endif; ?>
                    <?php if (!empty($proj['url']) && $proj['url'] !== '#'): ?>
                    <a href="<?= h($proj['url']) ?>" class="btn btn-primary" target="_blank" rel="noopener noreferrer">Voir le projet</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Bootstrap JS & jQuery -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap-bundle.min.js"></script>
    <script src="js/script.js"></script>

</body>
</html>
