<?php
require_once __DIR__ . "/../common/CSRF.php";
$isEdit = isset($hint);
?>

<form action="add.php" method="post" class="form-container glass">
  <?php if ($isEdit): ?>
    <input type="hidden" name="hint_id" value="<?= $hint->getId() ?>">
  <?php endif; ?>

  <label for="title">Title:</label>
  <input type="text"
    name="title"
    id="title"
    required
    value="<?= $isEdit ? htmlspecialchars($hint->getTitle()) : '' ?>">
  <div class="errMsg"></div>

  <label for="description">Description:</label>
  <textarea name="description"
    id="description"
    required><?= $isEdit ? htmlspecialchars($hint->getDescription()) : '' ?></textarea>
  <div class="errMsg"></div>

  <label for="category">Category:</label>
  <select name="category" id="category" required>
    <?php foreach ($categories as $category): ?>
      <option value="<?= $category->getId() ?>"
        <?= $isEdit && $hint->getCategory()->getId() === $category->getId() ? 'selected' : '' ?>>
        <?= htmlspecialchars($category->getName()) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <div class="errMsg"></div>

  <div id="reasons-container" class="flex flex-col gap-md">
    <h3>Reasons</h3>
    <?php if ($isEdit && !empty($hint->getReasons())): ?>
      <?php foreach ($hint->getReasons() as $index => $reason): ?>
        <div class="reason-input">
          <input type="text"
            placeholder="Reason <?= $index + 1 ?>"
            name="reasons[]"
            value="<?= htmlspecialchars($reason->getValue()) ?>"
            required>
          <?php if (count($hint->getReasons()) > 2): ?>
            <button type="button" class="remove-reason-button" onclick="removeReason(this)">
              <i class='bx bx-x'></i>
            </button>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="reason-input">
        <input type="text" placeholder="Reason 1" name="reasons[]" required>
      </div>
      <div class="reason-input">
        <input type="text" placeholder="Reason 2" name="reasons[]" required>
      </div>
    <?php endif; ?>
  </div>
  <div class="errMsg"></div>

  <button type="button" id="add-reason-button">
    <i class='bx bx-plus'></i>
    Add Another Reason
  </button>

  <?php CSRF::generate(); ?>

  <input type="submit" value="<?= $isEdit ? 'Update' : 'Add' ?> Hint">
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

  .remove-reason-button {
    background: transparent;
    border: none;
    color: #666;
    cursor: pointer;
    padding: var(--spacing-xs);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .remove-reason-button:hover {
    color: var(--color-danger);
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