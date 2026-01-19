<?php
require_once __DIR__ . '/../../core/Auth.php';
require_once __DIR__ . '/../../core/Csrf.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Thoth LMS</title>
  <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
  <nav class="nav">
    <div class="container nav__inner">
      <a class="brand" href="/">Thoth</a>

      <div class="nav__links">
        <?php if (Auth::check()): ?>
          <a href="/student/dashboard">Dashboard</a>
          <form class="inline" method="POST" action="/logout">
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">
            <button class="btn btn--ghost" type="submit">Logout</button>
          </form>
        <?php else: ?>
          <a href="/login">Login</a>
          <a class="btn btn--primary" href="/register">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <main class="container">
