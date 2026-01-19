<?php require_once __DIR__ . '/../../core/Csrf.php'; ?>

<h2>Login</h2>

<?php if (!empty($errors)): ?>
  <div class="alert alert--danger">
    <ul>
      <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form class="card form" method="POST" action="/login">
  <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">

  <label>
    Email
    <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
  </label>

  <label>
    Password
    <input type="password" name="password" required>
  </label>

  <button class="btn btn--primary" type="submit">Login</button>
</form>
