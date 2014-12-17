<div class="background"></div>
<div class="modal draggable modal-form" id="new-warehouse">
	<div class="close-modal">
		<a class="button negative" href="/warehouses/manage"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1>Assign Warehouse Access</h1>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open("/warehouses/addaccess/$warehouse->warehouse_id"); ?>
		<?= form_hidden('warehouse_id', $warehouse->warehouse_id); ?>
		<div class="">
			<?= form_fieldset($warehouse->name); ?>
				<?= form_label('User Name', 'user_id'); ?>
				<?= form_dropdown('user_id', User::user_options(), set_value('user_id')); ?><br>
				
				<?= form_checkbox('create', 1, set_checkbox('create', '1', FALSE)) ?>
				<?= form_label('Create', 'create'); ?><br>				

				<?= form_checkbox('read', 1, set_checkbox('read', '1', FALSE)) ?>
				<?= form_label('Read', 'read'); ?><br>

				<?= form_checkbox('update', 1, set_checkbox('update', '1', FALSE)) ?>
				<?= form_label('Update', 'update'); ?><br>

				<?= form_checkbox('delete', 1, set_checkbox('delete', '1', FALSE)) ?>
				<?= form_label('Delete', 'delete'); ?><br>
				

			<?= form_fieldset_close(); ?>
		</div>
		
			<div>
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive" value="Submit">
			</div>
		  
	<?= form_close(); ?>
</div>