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
          <img src="/public/uploads/profiles/<?= $user->getProfileImage() ?>" alt="Profile image" class="profile-image">
          <div class="user-details">
            <h3><?= htmlspecialchars($user->getUsername()) ?></h3>
            <p>Joined: <?= date('n.j.Y', strtotime($user->getCreatedAt())) ?></p>
          </div>
        </div>
        <div class="user-actions">
          <select class="role-select"
            data-user-id="<?= $user->getId() ?>"
            <?= $currentUser->getId() === $user->getId() ? 'disabled' : '' ?>>
            <option value="0" <?= $user->getRole() === 0 ? 'selected' : '' ?>>User</option>
            <option value="1" <?= $user->getRole() === 1 ? 'selected' : '' ?>>Admin</option>
          </select>
          <?php if ($currentUser->getId() === $user->getId()): ?>
            <small class="text-muted">Cannot change your own role</small>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<style>
  .text-muted {
    color: #666;
    font-style: italic;
    display: block;
    margin-top: 5px;
  }

  .role-select:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }

  .user-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--color-middle);
  }

  .user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
  }

  .profile-image {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
  }
</style>
<script src="/public/scripts/pages/admin_users.js" type="module"></script>