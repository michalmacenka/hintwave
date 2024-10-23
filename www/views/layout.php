<?php
require_once __DIR__ . '/../common/Database.php';
require_once __DIR__ . '/../repositories/AuthRepository.php';


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
</head>

<body>
  <header>
    <h1>HintWave</h1>
    <nav>
      <a href="hints.php">All hints</a>

      <?php if ($isLoggedIn) : ?>
        <a href="index.php">Home</a>
        <p>Hello, <?= $user->getUsername(); ?></p>
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

</html>