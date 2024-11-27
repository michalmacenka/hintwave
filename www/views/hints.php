<h1 class="mb-xl">All Hints</h1>
<div class="flex flex-wrap gap-md">
  <?php foreach ($hints as $hint): ?>
    <?php echo $hint->render() ?>
  <?php endforeach; ?>
</div>

<?php if ($totalPages > 1): ?>
  <div class="pagination">
    <?php if ($page > 1): ?>
      <a href="hints.php?page=<?= $page - 1 ?>" class="pagination-link">
        <i class='bx bx-chevron-left'></i>
        Previous
      </a>
    <?php endif; ?>

    <div class="pagination-numbers">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="hints.php?page=<?= $i ?>"
          class="pagination-link <?= $i === $page ? 'active' : '' ?>">
          <?= $i ?>
        </a>
      <?php endfor; ?>
    </div>

    <?php if ($page < $totalPages): ?>
      <a href="hints.php?page=<?= $page + 1 ?>" class="pagination-link">
        Next
        <i class='bx bx-chevron-right'></i>
      </a>
    <?php endif; ?>
  </div>
<?php endif; ?>