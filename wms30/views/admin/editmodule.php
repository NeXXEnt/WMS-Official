<div class="background"></div>
<div class="modal span-19 modal-form" id="edit-module">
	<div class="close-modal">
		<a class="button negative" href="/admin/module"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1><?= $editModule->moduleName ?></h1>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open('admin/module/'.$editModule->module_id); ?>
		<?= form_hidden('module_id', $editModule->module_id); ?>
		<div class="span-19 prepend-1">
			<?= form_fieldset('Module Information'); ?>
				<?= form_label('Module Name', 'moduleName'); ?>
				<?= form_input('moduleName', $editModule->moduleName); ?>
				<?= form_label('Module Key', 'moduleKey'); ?>
				<?= form_input('moduleKey', $editModule->moduleKey); ?><br>
				<?= form_Label('Short Description', 'moduleShortDescription'); ?>
				<?= form_input('moduleShortDescription', $editModule->moduleShortDescription); ?><br>
				<?= form_label('Long Description', 'moduleLongDescription'); ?><br>
				<?= form_textarea('moduleLongDescription', $editModule->moduleLongDescription); ?>
			<?= form_fieldset_close(); ?>
		</div>
		
			<div class="span-19 last">
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive" value="Submit">
			</div>
		  
	<?= form_close(); ?>
</div>