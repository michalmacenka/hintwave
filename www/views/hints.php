<h1>Doporučení</h1>
<ul>
  <?php foreach ($hints as $hint): ?>
    <li>
      <h2><?php echo htmlspecialchars($hint->getTitle()); ?></h2>
      <p><?php echo htmlspecialchars($hint->getDescription()); ?></p>
      <p>Kategorie: <?php echo htmlspecialchars($hint->getCategory()); ?></p>

      <h3>Plusy:</h3>
      <ul>
        <?php
        $pros = $hint->getPros();
        if (!empty($pros)) {
          foreach ($pros as $pro) {
            echo '<li>' . htmlspecialchars($pro) . '</li>';
          }
        } else {
          echo '<li>Žádné plusy nejsou k dispozici.</li>';
        }
        ?>
      </ul>

      <h3>Minusy:</h3>
      <ul>
        <?php
        $cons = $hint->getCons();
        if (!empty($cons)) {
          foreach ($cons as $con) {
            echo '<li>' . htmlspecialchars($con) . '</li>';
          }
        } else {
          echo '<li>Žádné minusy nejsou k dispozici.</li>';
        }
        ?>
      </ul>
    </li>
  <?php endforeach; ?>
</ul>