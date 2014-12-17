<div class="background"></div>
<div class="modal draggable modal-form" id="new-group">
	<div class="close-modal">
		<a class="button negative" href="/admin/user"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1>New Group</h1>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open('admin/group/0'); ?>
		<?= form_hidden('group_id', '0'); ?>
		<div>
			<?= form_fieldset('Group Information'); ?>
				<?= form_label('Group Name', 'groupName'); ?>
				<?= form_input('groupName', set_value('groupName')); ?><br>
				<?= form_label('Group Description', 'groupDescription'); ?><br>
				<?= form_textarea('groupDescription', set_value('groupDescription')); ?>
			<?= form_fieldset_close(); ?>
		</div>
			<div>
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive" value="Submit">
			</div>
	<?= form_close(); ?>
</div>