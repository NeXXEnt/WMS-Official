<div class="background"></div>
<div class="modal draggable modal-form" id="edit-module-permission">
	<div class="close-modal">
		<a class="button negative" href="/admin/module_permissions"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1><?= $editModulePermission->module->moduleName ?><br>
	    <?= $editModulePermission->user->userName ?></h1>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open('admin/module_permissions/'.$editModulePermission->module_permission_id); ?>
		<?= form_hidden('module_permission_id', $editModulePermission->module_permission_id); ?>
		<div>
			<?= form_fieldset('Edit Permissions'); ?>
				<?= form_label('Module', 'module_id'); ?>
				<?= form_dropdown('module_id', Module::module_options(), $editModulePermission->module_id); ?>
				<?= form_label('User Name', 'user_id'); ?>
				<?= form_dropdown('user_id', User::user_options(), $editModulePermission->user_id); ?><br>

				<?= form_checkbox('create', 1, $editModulePermission->create) ?>
				<?= form_label('Create', 'create'); ?><br>				

				<?= form_checkbox('read', 1, $editModulePermission->read) ?>
				<?= form_label('Read', 'read'); ?><br>

				<?= form_checkbox('update', 1, $editModulePermission->update) ?>
				<?= form_label('Update', 'update'); ?><br>

				<?= form_checkbox('delete', 1, $editModulePermission->delete) ?>
				<?= form_label('Delete', 'delete'); ?><br>

			<?= form_fieldset_close(); ?>
		</div>
		
			<div>
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive" value="Submit">
			</div>
		  
	<?= form_close(); ?>
</div>