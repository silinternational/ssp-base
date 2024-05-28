<?php
if (! empty($this->data['announcement'])) {
?>
  <div class="mdl-typography--subhead mdl-typography--text-center alert margin" layout-children="column">
      <?= $this->data['announcement'] ?>
  </div>
<?php
}
?>

