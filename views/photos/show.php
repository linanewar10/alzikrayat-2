<?php
// Single photo detail page.
// Expects: $photo (row with author name) and $comments (array).
$currentUser = current_user();
$isOwner = $currentUser && $currentUser['id'] == $photo['user_id'];
?>

<div class="row">
    <div class="col-lg-8">
        <img src="<?= BASE_URL ?>/images/uploads/<?= rawurlencode($photo['file_name']) ?>"
             class="img-fluid rounded shadow-sm mb-3"
             alt="<?= htmlspecialchars($photo['title']) ?>">
    </div>

    <div class="col-lg-4">
        <h1 class="h3"><?= htmlspecialchars($photo['title']) ?></h1>
        <p class="text-muted mb-1">
            by <?= htmlspecialchars($photo['first_name'] . ' ' . $photo['last_name']) ?>
        </p>
        <p class="text-muted"><small><?= htmlspecialchars($photo['date_time']) ?></small></p>

        <?php if (!empty($photo['description'])): ?>
            <p><?= nl2br(htmlspecialchars($photo['description'])) ?></p>
        <?php endif; ?>

        <!-- delete button only shows for the photo owner -->
        <?php if ($isOwner): ?>
            <a href="<?= BASE_URL ?>/photo/<?= (int) $photo['id'] ?>/delete"
               class="btn btn-danger btn-sm"
               onclick="return confirm('Delete this photo? This cannot be undone.');">
                Delete Photo
            </a>
        <?php endif; ?>

        <a href="<?= BASE_URL ?>/photos" class="btn btn-outline-secondary btn-sm">Back</a>
    </div>
</div>

<hr class="my-4">

<!-- Comments -->
<h3>Comments (<?= count($comments) ?>)</h3>

<?php if ($currentUser): ?>
    <form action="<?= BASE_URL ?>/photo/<?= (int) $photo['id'] ?>/comment" method="POST"
          id="commentForm" class="mb-4">
        <div class="mb-2">
            <textarea class="form-control" name="comment" id="comment"
                      rows="2" placeholder="Write a comment..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Add Comment</button>
    </form>
<?php else: ?>
    <p class="text-muted">
        <a href="<?= BASE_URL ?>/login">Login</a> to add a comment.
    </p>
<?php endif; ?>

<?php if (empty($comments)): ?>
    <p class="text-muted">No comments yet.</p>
<?php else: ?>
    <?php foreach ($comments as $comment): ?>
        <div class="border-bottom py-2">
            <strong><?= htmlspecialchars($comment['first_name'] . ' ' . $comment['last_name']) ?></strong>
            <small class="text-muted"><?= htmlspecialchars($comment['date_time']) ?></small>
            <p class="mb-0"><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Client side (JavaScript) validation for the comment box -->
<?php if ($currentUser): ?>
<script>
    document.getElementById('commentForm').addEventListener('submit', function (e) {
        if (document.getElementById('comment').value.trim() === '') {
            e.preventDefault();
            alert('Comment cannot be empty.');
        }
    });
</script>
<?php endif; ?>
