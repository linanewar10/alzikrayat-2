<?php
// Upload photo form.
// Expects: $errors (array) and $old (previous title/description).
$errors = $errors ?? [];
$old = $old ?? [];
?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <h1 class="mb-4">Upload a Photo</h1>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/photo/store" method="POST"
              enctype="multipart/form-data" id="uploadForm">

            <div class="mb-3">
                <label for="photo" class="form-label">Choose Photo</label>
                <input type="file" class="form-control" id="photo" name="photo"
                       accept="image/*" required>
                <div class="form-text">JPG, PNG, GIF or WEBP, up to 5 MB.</div>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title"
                       maxlength="200" required
                       value="<?= htmlspecialchars($old['title'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description"
                          rows="4"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Upload Photo</button>
            <a href="<?= BASE_URL ?>/photos" class="btn btn-link">Back to gallery</a>
        </form>
    </div>
</div>

<!-- Client side (JavaScript) validation -->
<script>
    document.getElementById('uploadForm').addEventListener('submit', function (e) {
        var fileInput = document.getElementById('photo');
        var title = document.getElementById('title').value.trim();

        if (fileInput.files.length === 0) {
            e.preventDefault();
            alert('Please choose an image to upload.');
            return;
        }
        if (title === '') {
            e.preventDefault();
            alert('Please enter a title for your photo.');
        }
    });
</script>
