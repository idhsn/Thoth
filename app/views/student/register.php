<?php require_once __DIR__ . '/../../core/Csrf.php'; ?>

<h2>Register</h2>

<?php if (!empty($errors)): ?>
  <div class="alert alert--danger">
    <ul>
      <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form class="card form" method="POST" action="/register">
  <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">

  <label>
    Name
    <input name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
  </label>

  <label>
    Email
    <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
  </label>

  <label>
    Password
    <input type="password" name="password" minlength="8" required>
    <small class="muted">Min 8 characters.</small>
  </label>

  <button class="btn btn--primary" type="submit">Create account</button>
</form>
