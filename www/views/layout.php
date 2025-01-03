<?php
require_once __DIR__ . '/../common/Database.php';
require_once __DIR__ . '/../repositories/AuthRepository.php';
require_once __DIR__ . '/../common/CSRF.php';

$db = new Database();
$authRepository = new AuthRepository($db);

$authRepository->startSession();

$isLoggedIn = $authRepository->isLoggedIn();
$user = $authRepository->getUser();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HintWave</title>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="/~macenmic/public/styles/main.css">


</head>

<body>
  <header>
    <nav class="container">
      <div class="nav-left">
        <div class="nav-brand">
          <a href="index.php">HintWave</a>
        </div>
        <div class="nav-primary-links">
          <a href="hints.php">All Hints</a>
          <?php if ($isLoggedIn) : ?>
            <a href="index.php">Recommended</a>
            <a href="add.php">Add hint</a>
            <?php if ($user && $user->isAdmin()) : ?>
              <a href="users.php">Manage Users</a>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>

      <div class="nav-right">
        <?php if ($isLoggedIn) : ?>
          <div class="profile-section">
            <img
              src="public/uploads/profiles/<?= $user->getProfileImage() ?>"
              alt="Profile picture of <?= htmlspecialchars($user->getUsername()) ?>"
              class="profile-image">
            <p class="font-bold"><?= htmlspecialchars($user->getUsername()) ?>
              <?php if ($user->isAdmin()) : ?>
                <span class="role-badge">Admin</span>
              <?php endif; ?>
            </p>
          </div>
          <a href="logout.php" class="text-danger">Logout</a>
        <?php else : ?>
          <a href="login.php">Login</a>
          <a href="register.php">Register</a>
        <?php endif; ?>
      </div>
    </nav>
  </header>
  <main class="container">
    <?php echo $content; ?>
  </main>
  <script src="/~macenmic/public/scripts/pages/admin.js" type="module"></script>
  <link rel="stylesheet" href="/~macenmic/public/styles/scoped/layout.css">
</body>
</html>
