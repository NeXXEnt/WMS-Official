<div class="background"></div>
<div class="modal draggable modal-form" id="add-to-group">
	<div class="close-modal">
		<a class="button negative" href="/admin/user"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1>Add <?=$user->userName?> to Group</h1>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open('/admin/addtogroup/'.$user->user_id); ?>
		<?= form_hidden('user_id', $user->user_id); ?>
		<div>
			<?= form_fieldset('Select a Group'); ?>
				<?= form_label('Group Name', 'group_id'); ?>
				<?= form_dropdown('group_id', Wms::fetch_options('Groups', 'group_id', 'groupName', 'Select a Group'), set_value('group_id')); ?><br>				
			<?= form_fieldset_close(); ?>
		</div>
			<div>
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive" value="Submit">
			</div>
	<?= form_close(); ?>
</div>