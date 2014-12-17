<div class="background"></div>
<div class="modal span-19 modal-form" id="new-module">
	<div class="close-modal">
		<a class="button negative" href="/products/lists"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1>New Product List</h1>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open('products/lists/0'); ?>
		<div class="span-19">
			<?= form_fieldset('General Info'); ?>
				<p class="required">
					<?= form_label('List Name', 'name'); ?><br>
					<?= form_input('name', set_value('name'), 'class="title"'); ?>
				</p>
				<p class="required">
					<?= form_dropdown('warehouse_id', Warehouse::options($user_id), set_value('warehouse_id')); ?>
				</p>
				<p>
					<?= form_label('Short Description', 'shortDescription'); ?><br>
					<?= form_input('shortDescription', set_value('shortDescription')); ?>
				</p>
				<p>
					<?= form_label('Long Description', 'longDescription'); ?><br>
					<?= form_textarea('longDescription', set_value('longDescription'), 'class="span-18"'); ?>
				</p>

			<?= form_fieldset_close(); ?>
		</div>
		
			<div class="span-19 last">
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive" value="Submit">
			</div>
		  
	<?= form_close(); ?>
</div>