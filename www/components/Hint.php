<div class="hint gradient-primary" data-hint-id="<?php echo $this->getId(); ?>">
  <a href="index.php?id=<?= $this->getId() ?>">
    <h3><?php echo htmlspecialchars($this->title); ?> <span class="badge"><?php echo htmlspecialchars($this->category->getName()); ?></span> </h3>
  </a>
  <div class="hint-user m-0">
    <i class='bx bx-at'></i>
    <p><?= htmlspecialchars($this->user->getUsername()) ?></p>
  </div>
  <p class="hint-desc line-clamp-2"><?php echo htmlspecialchars($this->description); ?></p>
  <box-icon name='calendar'></box-icon>
  <ul class="reasons">
    <?php foreach ($this->reasons as $reason): ?>
      <li class="reason"><i class='bx bxs-star'></i>
        <p><?php echo htmlspecialchars($reason->getValue()); ?></p>
      </li>
    <?php endforeach; ?>
  </ul>

  <div class="hint-actions">
    <?php
    global $authRepository;
    $currentUser = $authRepository->getUser();
    if ($currentUser && ($currentUser->getId() === $this->user->getId() || $currentUser->isAdmin())): ?>
      <button class="delete-hint-btn" data-hint-id="<?php echo $this->getId(); ?>"> <i class='bx bx-trash'></i> Delete Hint</button>
    <?php endif; ?>

    <?php if ($currentUser && ($currentUser->getId() === $this->user->getId() || $currentUser->isAdmin())): ?>
      <a href="/add.php?edit=<?= $this->getId() ?>">
        <i class='bx bx-edit'></i>
        Edit Hint
      </a>
    <?php endif; ?>
  </div>
</div>

<link rel="stylesheet" href="/~macenmic/public/styles/scoped/hint.css">