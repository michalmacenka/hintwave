<?php
require_once __DIR__ . '/../common/CSRF.php';
?>

<div class="hint-detail">
  <div class="hint-header">
    <h1><?= htmlspecialchars($hint->getTitle()) ?></h1>
    <span class="badge"><?= htmlspecialchars($hint->getCategory()->getName()) ?></span>
  </div>

  <div class="hint-meta">
    <div class="hint-user">
      <i class='bx bx-user'></i>
      <span><?= htmlspecialchars($hint->getUser()->getUsername()) ?></span>
    </div>
    <div class="hint-date">
      <i class='bx bx-calendar'></i>
      <span><?= date('n.j.Y', strtotime($hint->getCreatedAt())) ?></span>
    </div>
  </div>

  <div class="hint-content">
    <p class="hint-description"><?= htmlspecialchars($hint->getDescription()) ?></p>

    <div class="hint-reasons">
      <h3>Reasons</h3>
      <ul class="reasons">
        <?php foreach ($hint->getReasons() as $reason): ?>
          <li class="reason">
            <i class='bx bxs-star'></i>
            <p><?= htmlspecialchars($reason->getValue()) ?></p>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>

  <a href="hints.php" class="btn btn-secondary">
    <i class='bx bx-arrow-back'></i>
    Back to Hints
  </a>
</div>

<link rel="stylesheet" href="/public/styles/scoped/hint-detail.css">