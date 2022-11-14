<form action="<?= route("Home") ?>" method="POST">
  <?= csrf() ?>
  <input type="text" name="name">
  <input type="submit">Submit</input>
</form>

<a href="./home">Test</a>