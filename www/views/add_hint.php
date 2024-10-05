<h2>Přidat nový nápad</h2>
<form action="add.php" method="POST">
  <label for="title">Název:</label>
  <input type="text" id="title" name="title" required>

  <label for="description">Popis:</label>
  <textarea id="description" name="description" required></textarea>

  <label for="pros">Plusy (oddělte čárkou):</label>
  <input type="text" id="pros" name="pros" required>

  <label for="cons">Mínusy (oddělte čárkou):</label>
  <input type="text" id="cons" name="cons" required>

  <label for="category">Kategorie:</label>
  <input type="text" id="category" name="category" required>

  <button type="submit">Přidat nápad</button>
</form>