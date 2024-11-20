<?php
require_once __DIR__ . "/../common/CSRF.php";

?>
<form action="add.php" method="post" class="form-container glass">
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

  <div id="reasons-container" class="flex flex-col gap-md">
    <h3>Reasons</h3>
    <div class="reason-input">
      <input type="text" placeholder="Reason 1" name="reasons[]" required>
    </div>

    <div class="reason-input">
      <input type="text" placeholder="Reason 2" name="reasons[]" required>
    </div>
    <?php CSRF::generate(); ?>
  </div>
  <div class="errMsg"></div>

  <button type="button" id="add-reason-button">
    <i class='bx bx-plus'></i>
    Add Another Reason
  </button>

  <input type="submit" value="Add Hint">
  <div class="errMsg" id="globalErrMsg"></div>
</form>

<style>
  .reason-input {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
  }

  .reason-input label {
    min-width: 100px;
  }

  .reason-input input {
    flex: 1;
  }

  #add-reason-button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-md);
    padding: var(--spacing-sm) var(--spacing-lg);
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 8px;
    cursor: pointer;
    margin: var(--spacing-md) 0;
    background: transparent;
    color: #666;
    font-size: 0.9em;
  }
</style>

<script src="/public/scripts/pages/hint.js" type="module"></script>