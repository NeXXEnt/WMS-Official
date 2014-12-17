<div class="success">
	<?php if(! isset($message)): ?>
		<p>All Data entered succesfully</p>
	<?php else: ?>
		<?= $message ?>
	<?php endif; ?>
</div>