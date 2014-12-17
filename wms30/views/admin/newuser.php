<div class="background"></div>
<div class="modal modal-form draggable" id="new-user">
	<div class="close-modal">
		<a class="button negative" href="/admin/user"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1>New User Form</h1>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open('admin/user/0'); ?>
		<?= form_hidden('user_id', '0'); ?>
		<div style="display: inline-flex;">
			<?= form_fieldset('Personal Info'); ?>
				<p class="required">
					<?= form_label('First Name', 'firstName'); ?><br>
					<?= form_input('firstName', set_value('firstName')); ?>
				</p>
				<p class="required">
					<?= form_label('Last Name', 'lastName'); ?><br>		
					<?= form_input('lastName', set_value('lastName')); ?>
				</p>
				<p class="required">
					<?= form_label('User Name', 'userName'); ?><br>
					<?= form_input('userName', set_value('userName')); ?>
				</p>
				<p class="required">
					<?= form_label('Password', 'userPassword'); ?><br>
					<?= form_password('userPassword', set_value('userPassword')) ?><br>
					<?= form_label('Confirm Password', 'confPassword'); ?><br>
					<?= form_password('confPassword', set_value('confPassword')); ?>
				</p>
				<p>						
					<?= form_label('Home Number', 'homePhoneNumber'); ?><br>
					<?= form_input('homePhoneNumber', set_value('homePhoneNumber')); ?>
				</p>
				<p>
					<?= form_label('Cell Number', 'cellPhoneNumber'); ?><br>
					<?= form_input('cellPhoneNumber', set_value('cellPhoneNumber')); ?>
				</p>
				<p>
					<?= form_label('Home Email', 'homeEmailAddress'); ?><br>
					<?= form_input('homeEmailAddress', set_value('homeEmailAddress')); ?>
				</p>
			<?= form_fieldset_close(); ?>
		
			<?= form_fieldset('Work Info'); ?>
				<p>
					<?= form_label('Address 1', 'address1'); ?><br>
					<?= form_input('address1', set_value('address1')); ?>
				</p>
				<p>
					<?= form_label('Address 2', 'address2'); ?><br>
					<?= form_input('address2', set_value('address2')); ?>
				</p>
				<p class="required">
					<?= form_label('City', 'city_id'); ?><br>
					<?= form_dropdown('city_id', City::city_options(), set_value('city_id')); ?>
				</p>

				<p>
					<?= form_label('Postal Code', 'postalCode'); ?><br>
					<?= form_input('postalCode', set_value('postalCode')); ?>
				</p>
				<p>
					<?= form_label('Work Email', 'workEmailAddress'); ?><br>
					<?= form_input('workEmailAddress', set_value('workEmailAddress')); ?>
				</p>
				<p>
					<?= form_label('Fax Number', 'faxNumber'); ?><br>
					<?= form_input('faxNumber', set_value('faxNumber')); ?>
				</p>
				<p>
					<?= form_label('Office Number', 'officePhoneNumber'); ?><br>
					<?= form_input('officePhoneNumber', set_value('officePhoneNumber')); ?>
				</p>

			
			<?= form_fieldset_close(); ?>
		
			<?= form_fieldset('Account Info'); ?>
				<p class="required">
					<?= form_label('Primary Warehouse', 'warehouse_id'); ?><br>
					<?= form_dropdown('warehouse_id', Warehouse::options(), set_value('warehouse_id')) ?>
				</p>
				<p>
					<?= form_checkbox('accountEnabled', 1, TRUE); ?>
					<?= form_label('Account Enabled', 'accountEnabled'); ?>
					
				</P>
				<p>
					<?= form_checkbox('admin', 1, FALSE); ?>
					<?= form_label('User is Admin', 'admin'); ?>
					
				</p>

			<?= form_fieldset_close(); ?>
			</div>
			<div>
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive" value="Submit">
			</div>
		  
	<?= form_close(); ?>
</div>