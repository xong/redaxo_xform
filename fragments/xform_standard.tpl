<?php
/*
$this->before
$this->label
$this->field
$this->after
$this->extra
$this->name
$this->class
$this->html_id
*/
?>

<div class="xform-value <?php echo ($this->class != '') ? ' '.$this->class : ''; ?>">
  <?php echo ($this->before != '') ? '<div class="xform-before">'.$this->before.'</div>' : ''; ?>
  <?php echo ($this->label != '') ? '<div class="xform-label">'.$this->label.'</div>' : ''; ?>
  <?php echo ($this->field != '') ? '<div class="xform-element">'.$this->field.'</div>' : ''; ?>
  <?php echo ($this->after != '') ? '<div class="xform-after">'.$this->after.'</div>' : ''; ?>
  <?php echo ($this->extra != '') ? '<div class="xform-extra">'.$this->extra.'</div>' : ''; ?>
</div>