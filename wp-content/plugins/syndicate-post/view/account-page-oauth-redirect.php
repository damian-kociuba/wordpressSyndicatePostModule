<?php if (!empty($errorMessage)): ?>
<h2>There is some problem!</h2>
<?php echo $errorMessage;?>
<?php else: ?>
<h2>Success!</h2>
<a href="<?php echo $comeBackURL;?>">Come back to settings</a>
<?php endif; ?>
