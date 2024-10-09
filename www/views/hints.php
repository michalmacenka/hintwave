<h1>Doporučení</h1>
<?php foreach ($hints as $hint): ?>
  <h2><?php echo htmlspecialchars($hint->getTitle()); ?></h2>
  <p><?php echo htmlspecialchars($hint->getDescription()); ?></p>
  <p>Kategorie: <?php echo htmlspecialchars($hint->getCategory()->getName()); ?></p> <!-- Access the category name -->
  <p>Reasons:</p>
  <ul>
    <?php foreach ($hint->getReasons() as $reason): ?>
      <li><?php echo htmlspecialchars($reason); ?></li>
    <?php endforeach; ?>
  </ul>
<?php endforeach; ?>