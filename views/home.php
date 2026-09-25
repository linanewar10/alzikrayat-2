<?php
// Home / landing page.
// Expects: $stats (users, photos, comments) and $recentPhotos (array).
?>

<!-- Hero / welcome section -->
<div class="hero text-center mb-5">
    <h1 class="display-4 fw-bold">Welcome to Alzikrayat</h1>
    <p class="lead">Share your memories. Upload your favourite photos and let others comment on them.</p>
    <a href="<?= BASE_URL ?>/photos" class="btn btn-light btn-lg mt-2">Browse the Gallery</a>
    <?php if (!current_user()): ?>
        <a href="<?= BASE_URL ?>/register" class="btn btn-outline-light btn-lg mt-2">Join Now</a>
    <?php endif; ?>
</div>

<!-- Statistics row -->
<div class="row text-center mb-5">
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="text-primary"><?= (int) $stats['users'] ?></h2>
                <p class="mb-0 text-muted">Members</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="text-primary"><?= (int) $stats['photos'] ?></h2>
                <p class="mb-0 text-muted">Photos</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="text-primary"><?= (int) $stats['comments'] ?></h2>
                <p class="mb-0 text-muted">Comments</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent photos preview -->
<h3 class="mb-3">Latest Memories</h3>
<?php if (empty($recentPhotos)): ?>
    <p class="text-muted">No photos have been uploaded yet.</p>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
        <?php foreach ($recentPhotos as $photo): ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <a href="<?= BASE_URL ?>/photo/<?= (int) $photo['id'] ?>">
                        <img
                            src="<?= BASE_URL ?>/images/uploads/<?= rawurlencode($photo['file_name']) ?>"
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

<!-- About Us section -->
<div id="about" class="bg-light p-4 rounded mb-4" dir="rtl">
    <h3>About Us</h3>
    <p>
        الذكريات (Alzikrayat) هو مشروع بسيط لمشاركة الصور، عملناه ضمن مادة Advanced Web Technologies. بنيناه من الصفر باستخدام MVC Framework..
    </p>
    <p class="mb-0">
        المشروع بيدّي الأعضاء إمكانية يعملوا حساب، يرفعوا صورهم، ويتصفحوا الصور في Gallery بأكتر من طريقة. وبرضو ممكن يفتحوا أي صورة ويشوفوها بالحجم الكامل ويكتبوا تعليقات على ذكريات بعض.
    </p>
</div>
