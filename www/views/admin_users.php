<?php
require_once __DIR__ . "/../common/CSRF.php";
?>

<div class="container">
  <h1>User Management</h1>
  <div class="users-list">
    <?= CSRF::generate() ?>
    <?php foreach ($users as $user): ?>
      <div class="user-card glass">
        <div class="user-info">
<img src="/~macenmic/public/uploads/profiles/<?= $user->getProfileImage() ?>" alt="Profile image" class="profile-image">
          <div class="user-details">
            <h3><?= htmlspecialchars($user->getUsername()) ?></h3>
            <p>Joined: <?= date('n.j.Y', strtotime($user->getCreatedAt())) ?></p>
          </div>
        </div>
        <div class="user-actions">
          <div class="actions-row">
            <select class="role-select"
              data-user-id="<?= $user->getId() ?>"
              <?= $currentUser->getId() === $user->getId() ? 'disabled' : '' ?>>
              <option value="0" <?= $user->getRole() === 0 ? 'selected' : '' ?>>User</option>
              <option value="1" <?= $user->getRole() === 1 ? 'selected' : '' ?>>Admin</option>
            </select>

            <?php if ($currentUser->getId() !== $user->getId()): ?>
              <button class="delete-user-btn" data-user-id="<?= $user->getId() ?>">
                <i class='bx bx-trash'></i> Delete User
              </button>
            <?php endif; ?>
          </div>

          <?php if ($currentUser->getId() === $user->getId()): ?>
            <small class="text-muted">Cannot change your own role</small>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<link rel="stylesheet" href="/~macenmic/public/styles/scoped/admin-users.css">
<script src="/~macenmic/public/scripts/pages/admin_users.js" type="module"></script>
