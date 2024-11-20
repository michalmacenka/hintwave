<h1 class="mb-xl">All Hints</h1>
<div class="flex flex-wrap gap-md">
  <?php foreach ($hints as $hint): ?>
    <?php echo $hint->render() ?>
  <?php endforeach; ?>
</div>