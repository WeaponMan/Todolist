<div id="message-container">
<?php
foreach($this->messages as $message): ?>
  <div class="alert alert-<?= $this->autoEscape($message[1]) ?>"><?php
if(is_array($message[0])):
  if(count($message[0]) > 1): ?>
       <ul>
<?php foreach($message[0] as $sub_message): ?>
         <li><?= $this->autoEscape($sub_message) ?></li>
<?php endforeach; ?>
       </ul>
<?php
  else:
    echo $this->autoEscape($message[0][0]);
  endif;
else:
  echo $this->autoEscape($message[0]);
endif;
?>
  </div>
<?php endforeach; ?>
</div>
