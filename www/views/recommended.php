<h1 class="mb-xl">Recommended Hints</h1>
<div class="flex flex-col gap-md">
  <?php foreach ($recommendedHints as $hint): ?>
    <div>

      <h3><?php echo $hint->getCategory()->getName(); ?></h3>
      <?php echo $hint->render() ?>
    </div>
  <?php endforeach; ?>
</div>