<?php
// Shared page header: opens the HTML document and draws the navbar.
// $currentUser is used to change the navbar depending on the login state.
$currentUser = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alzikrayat - Photo Sharing</title>

    <!-- Bootstrap 5 (CDN) for the responsive layout -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        /* hero banner on the home page */
        .hero {
            background: linear-gradient(135deg, #4b6cb7, #182848);
            color: #fff;
            padding: 70px 15px;
            border-radius: 10px;
        }
        /* keep gallery images the same height so the grid looks tidy */
        .photo-thumb {
            height: 220px;
            object-fit: cover;
            width: 100%;
        }
        /* smaller thumbnail height on phones */
        @media (max-width: 576px) {
            .photo-thumb {
                height: 180px;
            }
            .hero {
                padding: 45px 15px;
            }
        }
    </style>
</head>
<body>

<!-- Navigation bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/">📷 Alzikrayat</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNav" aria-controls="mainNav"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/photos">Gallery</a>
                </li>
                <?php if ($currentUser): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/photo/create">Upload</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/#about">About Us</a>
                </li>
            </ul>

            <ul class="navbar-nav">
                <?php if ($currentUser): ?>
                    <li class="nav-item">
                        <span class="navbar-text text-white me-3">
                            Hi <?= htmlspecialchars($currentUser['first_name']) ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm"
                           href="<?= BASE_URL ?>/logout">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <span class="navbar-text text-warning me-3">Please Login</span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm me-2"
                           href="<?= BASE_URL ?>/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-light btn-sm"
                           href="<?= BASE_URL ?>/register">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">

<?php
// Flash message (shown once after a redirect, e.g. after registering)
if (!empty($_SESSION['flash'])):
?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash']) ?></div>
<?php
    unset($_SESSION['flash']);
endif;
?>
