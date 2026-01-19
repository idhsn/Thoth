<h2>Dashboard</h2>
<p class="muted">Browse courses and enroll.</p>

<div class="grid">
  <?php foreach ($courses as $course): ?>
    <div class="card">
      <h3><?= htmlspecialchars($course['title']) ?></h3>
      <p><?= htmlspecialchars(mb_strimwidth($course['description'], 0, 140, '...')) ?></p>

      <div class="card__actions">
        <a class="btn btn--ghost" href="/student/course/<?= (int)$course['id'] ?>">Details</a>

        <?php if (in_array((int)$course['id'], $enrolledIds, true)): ?>
          <span class="badge">Enrolled</span>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>
