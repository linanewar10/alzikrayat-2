<?php
// Photo gallery.
// Expects: $photos (array) and $grid (chosen display style).
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Gallery</h1>

    <!-- grid style switcher: reloads the page with a ?grid= choice -->
    <div class="btn-group" role="group" aria-label="Display style">
        <a href="<?= BASE_URL ?>/photos?grid=grid-3"
           class="btn btn-outline-secondary btn-sm <?= $grid === 'grid-3' ? 'active' : '' ?>">3 Columns</a>
        <a href="<?= BASE_URL ?>/photos?grid=grid-4"
           class="btn btn-outline-secondary btn-sm <?= $grid === 'grid-4' ? 'active' : '' ?>">4 Columns</a>
        <a href="<?= BASE_URL ?>/photos?grid=list"
           class="btn btn-outline-secondary btn-sm <?= $grid === 'list' ? 'active' : '' ?>">List</a>
    </div>
</div>

<?php if (empty($photos)): ?>
    <p class="text-muted">No photos yet. Be the first to upload one!</p>

<?php elseif ($grid === 'list'): ?>
    <!-- List style: one wide card per photo -->
    <?php foreach ($photos as $photo): ?>
        <div class="card mb-3 shadow-sm">
            <div class="row g-0">
                <div class="col-md-4">
                    <a href="<?= BASE_URL ?>/photo/<?= (int) $photo['id'] ?>">
                        <img src="<?= BASE_URL ?>/images/uploads/<?= rawurlencode($photo['file_name']) ?>"
                             class="photo-thumb rounded-start"
                             alt="<?= htmlspecialchars($photo['title']) ?>">
                    </a>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="<?= BASE_URL ?>/photo/<?= (int) $photo['id'] ?>"
                               class="text-decoration-none">
                                <?= htmlspecialchars($photo['title']) ?>
                            </a>
                        </h5>
                        <p class="card-text"><?= htmlspecialchars($photo['description']) ?></p>
                        <small class="text-muted">
                            by <?= htmlspecialchars($photo['first_name'] . ' ' . $photo['last_name']) ?>
                            on <?= htmlspecialchars($photo['date_time']) ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

<?php else: ?>
    <!-- Grid style: 3 or 4 columns depending on the chosen style -->
    <?php $columns = ($grid === 'grid-4') ? 'row-cols-1 row-cols-sm-2 row-cols-lg-4' : 'row-cols-1 row-cols-sm-2 row-cols-md-3'; ?>
    <div class="row <?= $columns ?> g-4">
        <?php foreach ($photos as $photo): ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <a href="<?= BASE_URL ?>/photo/<?= (int) $photo['id'] ?>">
                        <img src="<?= BASE_URL ?>/images/uploads/<?= rawurlencode($photo['file_name']) ?>"
                             class="card-img-top photo-thumb"
                             alt="<?= htmlspecialchars($photo['title']) ?>">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($photo['title']) ?></h5>
                        <small class="text-muted">
                            by <?= htmlspecialchars($photo['first_name'] . ' ' . $photo['last_name']) ?>
                        </small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
