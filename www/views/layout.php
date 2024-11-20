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
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="/public/styles/main.css">

  <!-- <meta http-equiv="refresh" content="3"> * FOR DEV* -->
</head>

<body>
  <header>
    <nav>
      <h1 class="text-lg">HintWave</h1>
      <div class="flex gap-md grow">
        <a href=" hints.php">All hints</a>
        <?php if ($isLoggedIn) : ?>
          <a href="index.php" class="grow">Home</a>
        <?php endif; ?>
      </div>

      <?php if ($isLoggedIn) : ?>
        <a href="add.php">Add hint</a>
        <a href="logout.php" class="text-danger">Logout</a>
        <div class="profile-section">
          <img
            src="public/uploads/profiles/<?= $user->getProfileImage() ?>"
            alt="Profile picture of <?= htmlspecialchars($user->getUsername()) ?>"
            class="profile-image">
          <p class="font-bold"><?= htmlspecialchars($user->getUsername()) ?> <span class="role-badge"><?= $user->getRole() === 1 ? 'Admin' : '' ?></span> </p>
        </div>
      <?php else : ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
      <?php endif; ?>

    </nav>


  </header>
  <main class="container">
    <?php echo $content; ?>
  </main>
</body>
<script src="/public/scripts/pages/admin.js" type="module"></script>
<link rel="stylesheet" href="/public/styles/scoped/layout.css">

</html>