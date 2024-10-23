<h2><?php echo htmlspecialchars($this->title); ?></h2>
<p>username: <?= htmlspecialchars($this->user->getUsername()) ?></p>
<p><?php echo htmlspecialchars($this->description); ?></p>
<p>Kategorie: <?php echo htmlspecialchars($this->category->getName()); ?></p>
<p>Reasons:</p>
<ul>
  <?php foreach ($this->reasons as $reason): ?>
    <li><?php echo htmlspecialchars($reason->getValue()); ?></li>
  <?php endforeach; ?>
</ul>
<br><br>