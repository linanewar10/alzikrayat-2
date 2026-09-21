<?php
// Login form.
// Expects: $errors (array) and $old (previous values).
$errors = $errors ?? [];
$old = $old ?? [];
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <h1 class="mb-4">Login</h1>

        <!-- last login reminder read from the 7-day cookie -->
        <?php if (isset($_COOKIE['last_login'])): ?>
            <div class="alert alert-info">
                Last login from this computer was
                <?= htmlspecialchars($_COOKIE['last_login']) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/login" method="POST" id="loginForm">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required
                       value="<?= htmlspecialchars($old['email'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
            <a href="<?= BASE_URL ?>/register" class="btn btn-link">Need an account?</a>
        </form>
    </div>
</div>

<!-- Client side (JavaScript) validation -->
<script>
    document.getElementById('loginForm').addEventListener('submit', function (e) {
        var email = document.getElementById('email').value.trim();
        var password = document.getElementById('password').value;

        if (email === '' || password === '') {
            e.preventDefault();
            alert('Please enter both your email and password.');
        }
    });
</script>
