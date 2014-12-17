<div class="background"></div>
<div class="modal modal-form draggable" id="new-module-permission">
	<div class="close-modal">
		<a class="button negative" href="/admin/module_permissions"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1>New Module Permission Form</h1>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open('admin/module_permissions/0'); ?>
		<?= form_hidden('module_permission_id', '0'); ?>
		<div>
			<?= form_fieldset('Assign Module Permission'); ?>
				<?= form_label('Module', 'module_id'); ?>
				<?= form_dropdown('module_id', Module::module_options(), set_value('module_id')); ?>
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