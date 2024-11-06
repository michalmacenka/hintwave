<div class="hint" data-hint-id="<?php echo $this->getId(); ?>">
  <h2><?php echo htmlspecialchars($this->title); ?></h2>
  <p>username: <?= htmlspecialchars($this->user->getUsername()) ?></p>
  <p><?php echo htmlspecialchars($this->description); ?></p>
  <p>Kategorie: <?php echo htmlspecialchars($this->category->getName()); ?></p>
  <p>Reasons:</p>
  <ul>
    <?php foreach ($this->reasons as $reason): ?>
      <li><?php echo htmlspecialchars($reason->getValue()); ?></li>
    <?php endforeach; ?>
  </ul>

  <?php
  global $authRepository;
  $currentUser = $authRepository->getUser();
  if ($currentUser && $currentUser->getRole() === 1): ?>
    <button class="delete-hint-btn" data-hint-id="<?php echo $this->getId(); ?>">Delete Hint</button>
  <?php endif; ?>

  <br><br>
</div>