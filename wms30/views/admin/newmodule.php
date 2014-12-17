<div class="background"></div>
<div class="modal draggable modal-form" id="new-module">
	<div class="close-modal">
		<a class="button negative" href="/admin/module"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1>New Module Form</h1>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open('admin/module/0'); ?>
		<?= form_hidden('module_id', '0'); ?>
		<div>
			<?= form_fieldset('Module Information'); ?>
				<?= form_label('Module Name', 'moduleName'); ?>
				<?= form_input('moduleName', set_value('moduleName')); ?><br>
				<?= form_label('Module Key', 'moduleKey'); ?>
				<?= form_input('moduleKey', set_value('moduleKey')); ?><br>
				<?= form_Label('Short Description', 'moduleShortDescription'); ?>
				<?= form_input('moduleShortDescription', set_value('moduleShortDescription')); ?><br>
				<?= form_label('Long Description', 'moduleLongDescription'); ?><br>
				<?= form_textarea('moduleLongDescription', set_value('moduleLongDescription')); ?>
			<?= form_fieldset_close(); ?>
		</div>
			<div>
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive" value="Submit">
			</div>
	<?= form_close(); ?>
</div>