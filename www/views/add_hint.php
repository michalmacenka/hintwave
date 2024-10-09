<form action="add.php" method="post">
  <label for="title">Title:</label>
  <input type="text" name="title" id="title" required>

  <label for="description">Description:</label>
  <textarea name="description" id="description" required></textarea>

  <label for="category">Category:</label>
  <select name="category" id="category" required>
    <?php foreach ($categories as $category): ?>
      <option value="<?php echo $category->getId(); ?>">
        <?php echo htmlspecialchars($category->getName()); ?>
      </option>
    <?php endforeach; ?>
  </select>

  <div id="reasons-container">
    <label for="reasons[]">Reason 1:</label>
    <input type="text" name="reasons[]" required>

    <label for="reasons[]">Reason 2:</label>
    <input type="text" name="reasons[]" required>
  </div>

  <button type="button" id="add-reason-button">Add Another Reason</button>

  <input type="submit" value="Add Hint">
</form>

<script>
  const addReasonButton = document.getElementById('add-reason-button');
  const reasonsContainer = document.getElementById('reasons-container');

  addReasonButton.addEventListener('click', function() {
    const reasonInput = document.createElement('input');
    reasonInput.type = 'text';
    reasonInput.name = 'reasons[]';
    reasonInput.placeholder = 'Reason';

    reasonsContainer.appendChild(reasonInput);
  });
</script>