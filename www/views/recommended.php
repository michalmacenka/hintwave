<h1>Recommended Hints</h1>
<?php foreach ($recommendedHints as $hint): ?>
  <h2><?php echo htmlspecialchars($hint->getCategory()->getName()); ?></h2>
  <h3><?php echo htmlspecialchars($hint->getTitle()); ?></h3>
  <p><?php echo htmlspecialchars($hint->getDescription()); ?></p>
  <p>Reasons:</p>
  <ul>
    <?php foreach ($hint->getReasons() as $reason): ?>
      <li><?php echo htmlspecialchars($reason); ?></li>
    <?php endforeach; ?>
  </ul>
<?php endforeach; ?>