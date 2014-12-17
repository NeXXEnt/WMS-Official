<div class="background"></div>
<div class="modal draggable modal-form" id="take">
	<div class="close-modal">
		<a class="button negative" href="/shipping/items"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1>Take</h1>
	<h2>
		From: <?= $warehouse->name ?><br>
		And: <?= $productList->name ?>
	</h2>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open("/shipping/items/-1/4"); ?>
		<div>
			<?= form_hidden('method', 'ipc'); ?>
			<?= form_hidden('product_list_id', $productList->product_list_id); ?>

			<?= form_fieldset('Step 3'); ?>
				<?= form_label('IPC/SCU#', 'ipc'); ?>
				<?= form_input('ipc', set_value('ipc')); ?><br>
			<?= form_fieldset_close(); ?>
		</div>
		
			<div>
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive" value="Submit">
			</div>
		  
	<?= form_close(); ?>
</div>