<div class="background"></div>
<div class="modal draggable modal-form" id="edit-product-list-access">
	<div class="close-modal">
		<a class="button negative" href="/products/lists"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1>Edit List Access</h1>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open("/products/editlistAccess/$edit->product_list_permission_id"); ?>
		<div class="">
			<?= form_fieldset('For '.$edit->user->userName); ?>				
				<?= form_checkbox('create', 1, $edit->create) ?>
				<?= form_label('Create', 'create'); ?><br>				

				<?= form_checkbox('read', 1, $edit->read)?>
				<?= form_label('Read', 'read'); ?><br>

				<?= form_checkbox('update', 1, $edit->update) ?>
				<?= form_label('Update', 'update'); ?><br>

				<?= form_checkbox('delete', 1, $edit->delete) ?>
				<?= form_label('Delete', 'delete'); ?><br>
				

			<?= form_fieldset_close(); ?>
		</div>
		
			<div>
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive" value="Submit">
			</div>
		  
	<?= form_close(); ?>
</div>