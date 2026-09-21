<?php
// Registration form.
// Expects: $errors (array of messages) and $old (previously typed values).
$errors = $errors ?? [];
$old = $old ?? [];
?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <h1 class="mb-4">Create an account</h1>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- novalidate is off so HTML5 validation runs; JS adds a second check -->
        <form action="<?= BASE_URL ?>/register" method="POST" id="registerForm">
            <div class="mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstName" name="firstName"
                       maxlength="50" required
                       value="<?= htmlspecialchars($old['firstName'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="lastName"
                       maxlength="50" required
                       value="<?= htmlspecialchars($old['lastName'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                       maxlength="100" required
                       value="<?= htmlspecialchars($old['email'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                       minlength="6" required>
                <div class="form-text">At least 6 characters.</div>
            </div>

            <hr>
            <p class="text-muted">The fields below are optional.</p>

            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location"
                       maxlength="100"
                       value="<?= htmlspecialchars($old['location'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="occupation" class="form-label">Occupation</label>
                <input type="text" class="form-control" id="occupation" name="occupation"
                       maxlength="100"
                       value="<?= htmlspecialchars($old['occupation'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">About You</label>
                <textarea class="form-control" id="description" name="description"
                          rows="3"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
            <a href="<?= BASE_URL ?>/login" class="btn btn-link">Already have an account?</a>
        </form>
    </div>
</div>

<!-- Client side (JavaScript) validation -->
<script>
    document.getElementById('registerForm').addEventListener('submit', function (e) {
        var firstName = document.getElementById('firstName').value.trim();
        var lastName = document.getElementById('lastName').value.trim();
        var password = document.getElementById('password').value;
        var namePattern = /^[A-Za-z ]+$/;
        var messages = [];

        if (!namePattern.test(firstName)) {
            messages.push('First name must contain letters only.');
        }
        if (!namePattern.test(lastName)) {
            messages.push('Last name must contain letters only.');
        }
        if (password.length < 6) {
            messages.push('Password must be at least 6 characters.');
        }

        if (messages.length > 0) {
            e.preventDefault(); // stop the form from submitting
            alert(messages.join('\n'));
        }
    });
</script>
