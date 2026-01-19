<?php require_once __DIR__ . '/../../core/Csrf.php'; ?>

<a class="link" href="/student/dashboard">← Back</a>

<div class="card">
  <h2><?= htmlspecialchars($course['title']) ?></h2>
  <p><?= nl2br(htmlspecialchars($course['description'])) ?></p>

  <?php if ($isEnrolled): ?>
    <div class="alert alert--success">Already enrolled in this course.</div>
  <?php else: ?>
    <form method="POST" action="/student/course/<?= (int)$course['id'] ?>/enroll">
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">
      <button class="btn btn--primary" type="submit">Enroll</button>
    </form>
  <?php endif; ?>
</div>
