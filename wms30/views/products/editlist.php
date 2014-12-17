<div class="background"></div>
<div class="modal span-19 modal-form" id="edit-list">
	<div class="close-modal">
		<a class="button negative" href="/products/lists"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1><?= $edit->name ?></h1>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open('products/lists/'.$edit->product_list_id); ?>
		<div class="span-19">
			<?= form_fieldset('General Info'); ?>
				<p class="required">
					<?= form_label('List Name', 'name'); ?><br>
					<?= form_input('name', $edit->name, 'class="title"'); ?>
				</p>
				<p class="required">
					<?= form_dropdown('warehouse_id', Warehouse::options($user_id), $edit->warehouse_id); ?>
				</p>
				<p>
					<?= form_label('Short Description', 'shortDescription'); ?><br>
					<?= form_input('shortDescription', $edit->shortDescription); ?>
				</p>
				<p>
					<?= form_label('Long Description', 'longDescription'); ?><br>
					<?= form_textarea('longDescription', $edit->longDescription, 'class="span-18"'); ?>
				</p>

			<?= form_fieldset_close(); ?>
		</div>
		
			<div class="span-19 last">
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive" value="Submit">
			</div>
		  
	<?= form_close(); ?>
</div>