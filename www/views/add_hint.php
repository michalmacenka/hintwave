<?php
require_once __DIR__ . "/../common/CSRF.php";

?>

<style>
  .errMsg {
    color: red;
    display: none;
    margin-bottom: 10px;
  }
</style>

<form action="add.php" method="post">
  <label for="title">Title:</label>
  <input type="text" name="title" id="title" required>
  <div class="errMsg"></div>
  <label for="description">Description:</label>
  <textarea name="description" id="description" required></textarea>
  <div class="errMsg"></div>
  <label for="category">Category:</label>
  <select name="category" id="category" required>
    <?php foreach ($categories as $category): ?>
      <option value="<?php echo $category->getId(); ?>">
        <?php echo htmlspecialchars($category->getName()); ?>
      </option>
    <?php endforeach; ?>
  </select>
  <div class="errMsg"></div>
  <div id="reasons-container">
    <label for="reasons[]">Reason 1:</label>
    <input type="text" name="reasons[]" required>

    <label for="reasons[]">Reason 2:</label>
    <input type="text" name="reasons[]" required>
    <?php CSRF::generate(); ?>

  </div>
  <div class="errMsg"></div>
  <button type="button" id="add-reason-button">Add Another Reason</button>

  <input type="submit" value="Add Hint">
  <div class="errMsg" id="globalErrMsg"></div>

</form>

<!-- Na konec souboru -->
<script src="/public/scripts/pages/hint.js" type="module"></script>