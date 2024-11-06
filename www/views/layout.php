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
<html lang="cs">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HintWave</title>
  <style>
    .profile-section {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .profile-image {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
    }
  </style>
</head>

<body>
  <header>
    <h1>HintWave</h1>
    <nav>
      <a href="hints.php">All hints</a>

      <?php if ($isLoggedIn) : ?>
        <a href="index.php">Home</a>
        <div class="profile-section">
          <img
            src="public/uploads/profiles/<?= $user->getProfileImage() ?>"
            alt="Profile picture of <?= htmlspecialchars($user->getUsername()) ?>"
            class="profile-image">
          <p>Hello, <?= htmlspecialchars($user->getUsername()) ?> <span class="role-badge"><?= $user->getRole() === 1 ? 'Admin' : '' ?></span> </p>
        </div>
        <a href="logout.php">Logout</a>
        <a href="add.php">Add hint</a>
      <?php else : ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
      <?php endif; ?>

    </nav>


  </header>
  <main>
    <?php echo $content; ?>
  </main>
</body>
<script src="/public/scripts/pages/admin.js" type="module"></script>

</html>